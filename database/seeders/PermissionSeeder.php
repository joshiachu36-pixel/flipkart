<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * All application permissions grouped by module.
     * Format: ['module' => [['name' => '...', 'slug' => '...'], ...], ...]
     * sort_order controls display order in the UI.
     */
    private array $permissions = [
        'dashboard' => [
            ['name' => 'View Dashboard', 'slug' => 'dashboard.view'],
        ],
        'products' => [
            ['name' => 'View Products',    'slug' => 'products.view'],
            ['name' => 'Create Products',  'slug' => 'products.create'],
            ['name' => 'Edit Products',    'slug' => 'products.edit'],
            ['name' => 'Delete Products',  'slug' => 'products.delete'],
            ['name' => 'Approve Products', 'slug' => 'products.approve'],
            ['name' => 'Reject Products',  'slug' => 'products.reject'],
        ],
        'categories' => [
            ['name' => 'View Categories',   'slug' => 'categories.view'],
            ['name' => 'Create Categories', 'slug' => 'categories.create'],
            ['name' => 'Edit Categories',   'slug' => 'categories.edit'],
            ['name' => 'Delete Categories', 'slug' => 'categories.delete'],
        ],
        'collections' => [
            ['name' => 'View Collections',   'slug' => 'collections.view'],
            ['name' => 'Create Collections', 'slug' => 'collections.create'],
            ['name' => 'Edit Collections',   'slug' => 'collections.edit'],
            ['name' => 'Delete Collections', 'slug' => 'collections.delete'],
        ],
        'brands' => [
            ['name' => 'View Brands',   'slug' => 'brands.view'],
            ['name' => 'Create Brands', 'slug' => 'brands.create'],
            ['name' => 'Edit Brands',   'slug' => 'brands.edit'],
            ['name' => 'Delete Brands', 'slug' => 'brands.delete'],
        ],
        'variants' => [
            ['name' => 'View Variants',   'slug' => 'variants.view'],
            ['name' => 'Create Variants', 'slug' => 'variants.create'],
            ['name' => 'Edit Variants',   'slug' => 'variants.edit'],
            ['name' => 'Delete Variants', 'slug' => 'variants.delete'],
        ],
        'sellers' => [
            ['name' => 'View Sellers',    'slug' => 'sellers.view'],
            ['name' => 'Approve Sellers', 'slug' => 'sellers.approve'],
            ['name' => 'Reject Sellers',  'slug' => 'sellers.reject'],
            ['name' => 'Edit Sellers',    'slug' => 'sellers.edit'],
            ['name' => 'Delete Sellers',  'slug' => 'sellers.delete'],
            ['name' => 'Suspend Sellers', 'slug' => 'sellers.suspend'],
        ],
        'customers' => [
            ['name' => 'View Customers',   'slug' => 'customers.view'],
            ['name' => 'Edit Customers',   'slug' => 'customers.edit'],
            ['name' => 'Delete Customers', 'slug' => 'customers.delete'],
        ],
        'orders' => [
            ['name' => 'View Orders',   'slug' => 'orders.view'],
            ['name' => 'Update Orders', 'slug' => 'orders.update'],
            ['name' => 'Cancel Orders', 'slug' => 'orders.cancel'],
            ['name' => 'Refund Orders', 'slug' => 'orders.refund'],
        ],
        'reports' => [
            ['name' => 'View Reports',   'slug' => 'reports.view'],
            ['name' => 'Export Reports', 'slug' => 'reports.export'],
            ['name' => 'Print Reports',  'slug' => 'reports.print'],
        ],
        'staff' => [
            ['name' => 'View Staff',   'slug' => 'staff.view'],
            ['name' => 'Create Staff', 'slug' => 'staff.create'],
            ['name' => 'Edit Staff',   'slug' => 'staff.edit'],
            ['name' => 'Delete Staff', 'slug' => 'staff.delete'],
        ],
        'roles' => [
            ['name' => 'View Roles',   'slug' => 'roles.view'],
            ['name' => 'Create Roles', 'slug' => 'roles.create'],
            ['name' => 'Edit Roles',   'slug' => 'roles.edit'],
            ['name' => 'Delete Roles', 'slug' => 'roles.delete'],
        ],
        'settings' => [
            ['name' => 'View Settings', 'slug' => 'settings.view'],
            ['name' => 'Edit Settings', 'slug' => 'settings.edit'],
        ],
    ];

    public function run(): void
    {
        $now = now();
        $sortOrder = 0;

        foreach ($this->permissions as $module => $items) {
            foreach ($items as $item) {
                DB::table('permissions')->updateOrInsert(
                    ['slug' => $item['slug']],
                    [
                        'module'     => $module,
                        'name'       => $item['name'],
                        'slug'       => $item['slug'],
                        'sort_order' => $sortOrder++,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                );
            }
        }

        $this->command->info('✅ Permissions seeded: ' . $sortOrder . ' permissions across ' . count($this->permissions) . ' modules.');
    }
}
