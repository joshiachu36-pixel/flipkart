<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerDocument extends Model
{
    protected $fillable = [
        'seller_id',
        'document_type',
        'document_name',
        'file_path',
        'status',
        'notes',
    ];

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }
}
