<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'color_id',
        'price',
        'original_price',
        'image',
        'stock',
        'status',
        'priority',
        'sku',
    ];

    public function getDiscountPercentageAttribute()
    {
        $originalPrice = (float) $this->original_price;
        $sellingPrice  = (float) $this->price;

        if ($originalPrice > 0 && $sellingPrice < $originalPrice) {
            return round((($originalPrice - $sellingPrice) / $originalPrice) * 100);
        }

        return 0;
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

   public function sizes()
{
    return $this->belongsToMany(
        Size::class,
        'product_variant_size',
        'product_variant_id',
        'size_id'
    )
    ->withPivot('price', 'original_price', 'stock')
    ->withTimestamps();
}
}