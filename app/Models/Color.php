<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $fillable = [
        'seller_id',
        'name',
        'code',
        'status',
    ];

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function scopeForSeller($query, $sellerId)
    {
        return $query->where(function ($q) use ($sellerId) {
            $q->where('seller_id', $sellerId)
              ->orWhereNull('seller_id');
        });
    }

    public function productColors()
    {
        return $this->hasMany(ProductColor::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}