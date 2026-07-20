<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductApprovalHistory extends Model
{
    protected $fillable = [
        'product_id',
        'action',
        'actor_id',
        'actor_name',
        'reason',
        'acted_at',
    ];

    protected $casts = [
        'acted_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
