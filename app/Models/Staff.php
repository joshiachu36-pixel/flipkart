<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Staff extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'staff';

    protected $fillable = [
        'role_id',
        'name',
        'email',
        'phone',
        'password',
        'profile_photo',
        'status',
        'last_login_at',
        'login_count',
        'created_by',
        'created_by_type',
        'updated_by',
        'updated_by_type',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'login_count' => 'integer',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Check if this staff member is a Super Admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role && $this->role->isSuperAdmin();
    }

    /**
     * Check if this staff member has a permission by slug.
     * Super Admin staff always return true.
     */
    public function hasPermission(string $slug): bool
    {
        if (!$this->role) {
            return false;
        }

        return $this->role->hasPermission($slug);
    }

    public function creator()
    {
        return $this->morphTo('creator', 'created_by_type', 'created_by');
    }

    public function updater()
    {
        return $this->morphTo('updater', 'updated_by_type', 'updated_by');
    }
}
