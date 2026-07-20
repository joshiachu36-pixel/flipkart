<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Seller extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'business_name',
        'owner_name',
        'email',
        'phone',
        'password',
        'gst_number',
        'pan_number',
        'business_address',
        'bank_name',
        'bank_account_number',
        'ifsc_code',
        'business_logo',
        'status',
        'rejection_reason',
        'rejected_at',
        'suspension_reason',
        'suspended_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password'     => 'hashed',
        'rejected_at'  => 'datetime',
        'suspended_at' => 'datetime',
    ];

    // ── Status Helpers ────────────────────────────────────────────────────

    public function isPending(): bool
    {
        return $this->status === 'Pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'Approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'Rejected';
    }

    public function isSuspended(): bool
    {
        return $this->status === 'Suspended';
    }

    // ── Relationships ─────────────────────────────────────────────────────

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
