<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',
        'created_by',
        'created_by_type',
        'updated_by',
        'updated_by_type',
    ];

    public function staff()
    {
        return $this->hasMany(Staff::class);
    }

    /**
     * The permissions belonging to this role.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }

    /**
     * Determine if this role is the Super Admin role.
     */
    public function isSuperAdmin(): bool
    {
        return strtolower(trim($this->name)) === 'super admin';
    }

    /**
     * Check if this role has a specific permission by slug.
     * Super Admin roles always return true.
     */
    public function hasPermission(string $slug): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Use cached relationship if loaded
        if ($this->relationLoaded('permissions')) {
            return $this->permissions->contains('slug', $slug);
        }

        return $this->permissions()->where('slug', $slug)->exists();
    }

    public function creator()
    {
        return $this->morphTo('creator', 'created_by_type', 'created_by');
    }

    public function updater()
    {
        return $this->morphTo('updater', 'updated_by_type', 'updated_by');
    }

    public function getBadgeColorAttribute()
    {
        $name = strtolower($this->name);
        return match ($name) {
            'super admin' => '#dc3545', // Red
            'admin'       => '#0d6efd', // Blue
            'manager'     => '#198754', // Green
            'support'     => '#fd7e14', // Orange
            'finance'     => '#6f42c1', // Purple
            'inventory'   => '#20c997', // Teal
            default       => '#6c757d', // Grey
        };
    }
}
