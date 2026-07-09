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
    return $this->belongsToMany(
        ProductVariant::class,
        'product_variant_size',
        'size_id',
        'product_variant_id'
    )
    ->withPivot('price', 'stock')
    ->withTimestamps();
}

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
}
