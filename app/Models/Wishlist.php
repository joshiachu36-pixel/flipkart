<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Collection;

class Wishlist extends Model
{
    protected $fillable = [
        'customer_id',
        'product_id',
        'collection_id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function collection()
{
    return $this->belongsTo(Collection::class);
}
}