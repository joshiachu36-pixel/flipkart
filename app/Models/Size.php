<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $fillable = [
        'name',
        'status',
    ];

    public function productVariants()
    {
        return $this->belongsToMany(ProductVariant::class, 'product_variant_size');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
}
