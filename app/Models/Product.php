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
        'image',
        'seller_id',
        'approval_status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'rejected_by',
        'rejected_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    // ── Approval State Helpers ────────────────────────────────────────────────

    /**
     * Once approved, the approval status is permanently locked.
     * No further approve/reject actions are allowed via the workflow.
     */
    public function isApprovalLocked(): bool
    {
        return $this->approval_status === 'Approved';
    }

    /**
     * The admin can only act (Approve / Reject) when the product is Pending.
     */
    public function canBeReviewed(): bool
    {
        return $this->approval_status === 'Pending';
    }

    /**
     * The seller can re-upload (edit + re-submit) only when the product is Rejected.
     * Pending products are locked during review; Approved products are permanently locked.
     */
    public function canBeReUploaded(): bool
    {
        return $this->approval_status === 'Rejected';
    }

    // ── Relationships ─────────────────────────────────────────────────────────

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

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function approvalHistories()
    {
        return $this->hasMany(ProductApprovalHistory::class)->latest('acted_at');
    }

    // ── Accessors ─────────────────────────────────────────────────────────────

    public function getDiscountPercentageAttribute()
    {
        $originalPrice = (float) $this->original_price;
        $sellingPrice  = (float) $this->price;

        if ($originalPrice > 0 && $sellingPrice < $originalPrice) {
            return round((($originalPrice - $sellingPrice) / $originalPrice) * 100);
        }

        return 0;
    }
}

