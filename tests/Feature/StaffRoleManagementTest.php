<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StaffRoleManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $superAdmin;
    private Role $adminRole;
    private Role $supportRole;

    protected function setUp(): void
    {
        parent::setUp();

        // Create Super Admin User (users table)
        $this->superAdmin = User::factory()->create([
            'name' => 'Super Admin User',
            'email' => 'superadmin@marketplace.com',
            'role' => 'admin',
            'password' => bcrypt('Admin@123'),
        ]);

        // Create Default Roles
        $this->adminRole = Role::create([
            'name' => 'Super Admin',
            'description' => 'Full access role',
            'status' => 'Active',
        ]);

        $this->supportRole = Role::create([
            'name' => 'Support',
            'description' => 'Customer support desk',
            'status' => 'Active',
        ]);
    }

    public function test_super_admin_can_access_roles_and_staff(): void
    {
        $response = $this->actingAs($this->superAdmin, 'web')
            ->get(route('admin.roles.index'));

        $response->assertStatus(200);
        $response->assertSee('Role Management');

        $response = $this->actingAs($this->superAdmin, 'web')
            ->get(route('admin.staff.index'));

        $response->assertStatus(200);
        $response->assertSee('Staff Management');
    }

    public function test_non_super_admin_staff_cannot_access_roles_and_staff(): void
    {
        // Create active staff with non-super-admin role
        $staff = Staff::create([
            'name' => 'Support Staff',
            'email' => 'support@marketplace.com',
            'phone' => '1234567890',
            'password' => Hash::make('Admin@123'),
            'role_id' => $this->supportRole->id,
            'status' => 'Active',
        ]);

        $response = $this->actingAs($staff, 'staff')
            ->get(route('admin.roles.index'));

        $response->assertStatus(403);

        $response = $this->actingAs($staff, 'staff')
            ->get(route('admin.staff.index'));

        $response->assertStatus(403);
    }

    public function test_staff_login_verification(): void
    {
        $staff = Staff::create([
            'name' => 'John Doe',
            'email' => 'john@marketplace.com',
            'phone' => '1234567890',
            'password' => Hash::make('Admin@123'),
            'role_id' => $this->supportRole->id,
            'status' => 'Active',
        ]);

        // Successful login
        $response = $this->post(route('admin.login.store'), [
            'email' => 'john@marketplace.com',
            'password' => 'Admin@123',
        ]);

        $response->assertRedirect(route('admin.sellers.index'));
        $this->assertTrue(Auth::guard('staff')->check());
        $this->assertEquals($staff->id, Auth::guard('staff')->id());
    }

    public function test_suspended_and_inactive_staff_cannot_login(): void
    {
        // Inactive Staff
        $inactive = Staff::create([
            'name' => 'Inactive Staff',
            'email' => 'inactive@marketplace.com',
            'password' => Hash::make('Admin@123'),
            'role_id' => $this->supportRole->id,
            'status' => 'Inactive',
        ]);

        $response = $this->post(route('admin.login.store'), [
            'email' => 'inactive@marketplace.com',
            'password' => 'Admin@123',
        ]);

        $response->assertSessionHas('error', 'Your account is inactive.');
        $this->assertFalse(Auth::guard('staff')->check());

        // Suspended Staff
        $suspended = Staff::create([
            'name' => 'Suspended Staff',
            'email' => 'suspended@marketplace.com',
            'password' => Hash::make('Admin@123'),
            'role_id' => $this->supportRole->id,
            'status' => 'Suspended',
        ]);

        $response = $this->post(route('admin.login.store'), [
            'email' => 'suspended@marketplace.com',
            'password' => 'Admin@123',
        ]);

        $response->assertSessionHas('error', 'Your account is suspended.');
        $this->assertFalse(Auth::guard('staff')->check());
    }

    public function test_cannot_delete_role_with_assigned_staff(): void
    {
        // Assign a staff to support role
        Staff::create([
            'name' => 'Agent Smith',
            'email' => 'smith@marketplace.com',
            'password' => Hash::make('Admin@123'),
            'role_id' => $this->supportRole->id,
            'status' => 'Active',
        ]);

        $response = $this->actingAs($this->superAdmin, 'web')
            ->delete(route('admin.roles.destroy', $this->supportRole));

        $response->assertSessionHas('error', 'Cannot delete this role because staff members are assigned to it.');
        $this->assertDatabaseHas('roles', ['id' => $this->supportRole->id]);
    }

    public function test_delete_protection_rules_for_staff(): void
    {
        // Create another Super Admin staff
        $superStaff = Staff::create([
            'name' => 'Super Staff',
            'email' => 'superstaff@marketplace.com',
            'password' => Hash::make('Admin@123'),
            'role_id' => $this->adminRole->id,
            'status' => 'Active',
        ]);

        // 1. Prevent Delete own account / currently logged in user
        $response = $this->actingAs($superStaff, 'staff')
            ->delete(route('admin.staff.destroy', $superStaff));

        $response->assertSessionHas('error', 'You cannot delete your own account.');
        $this->assertDatabaseHas('staff', ['id' => $superStaff->id]);

        // 2. Prevent Change own role
        $response = $this->actingAs($superStaff, 'staff')
            ->put(route('admin.staff.update', $superStaff), [
                'name' => 'Super Staff Updated',
                'email' => 'superstaff@marketplace.com',
                'role_id' => $this->supportRole->id, // Attempt to downgrade role
                'status' => 'Active',
            ]);

        $response->assertSessionHas('error', 'You cannot change your own role.');
        $this->assertEquals($this->adminRole->id, $superStaff->fresh()->role_id);
    }
}
