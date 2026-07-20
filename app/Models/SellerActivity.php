<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerActivity extends Model
{
    protected $fillable = [
        'seller_id',
        'action',
        'description',
        'ip_address',
    ];

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public static function log(int $sellerId, string $action, string $description): self
    {
        return static::create([
            'seller_id'   => $sellerId,
            'action'      => $action,
            'description' => $description,
            'ip_address'  => request()->ip(),
        ]);
    }
}
