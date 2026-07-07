<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Brand;

class Product extends Model
{
       protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'description',
        'price',
        'original_price',
        'stock',
        'status',
        'image'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function collections()
    {
        return $this->belongsToMany(Collection::class);
    }

    public function productColors()
    {
        return $this->hasMany(ProductColor::class);
    }

   public function getDiscountPercentageAttribute()
{
    $originalPrice = (float) $this->original_price;
    $sellingPrice = (float) $this->price;

    if ($originalPrice > 0 && $sellingPrice < $originalPrice) {
        return round((($originalPrice - $sellingPrice) / $originalPrice) * 100);
    }

    return 0;
}

public function carts()
{
    return $this->hasMany(Cart::class);
}

public function wishlists()
{
    return $this->hasMany(Wishlist::class);
}

public function variants()
{
    return $this->hasMany(ProductVariant::class);
}
}
