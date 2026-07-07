<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $fillable = [
        'name',
        'code',
        'status',
    ];

    public function productColors()
    {
        return $this->hasMany(ProductColor::class);
    }

    public function variants()
{
    return $this->hasMany(ProductVariant::class);
}
}