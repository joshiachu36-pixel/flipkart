<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'color_id',
        'price',
        'image',
        'stock',
        'status',
    ];

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
    ->withPivot('stock')
    ->withTimestamps();
}
}