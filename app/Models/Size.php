<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $fillable = [
        'seller_id',
        'name',
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

  public function productVariants()
{
    return $this->belongsToMany(
        ProductVariant::class,
        'product_variant_size',
        'size_id',
        'product_variant_id'
    )
    ->withPivot('price', 'original_price', 'stock')
    ->withTimestamps();
}

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
}
