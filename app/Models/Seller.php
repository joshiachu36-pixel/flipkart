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
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
