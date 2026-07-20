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
        return $this->hasMany(ProductVariant::class)->orderBy('priority', 'asc')->orderBy('id', 'asc');
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function approvalHistories()
    {
        return $this->hasMany(ProductApprovalHistory::class)->latest('acted_at');
    }

    public function getDefaultVariantAttribute()
    {
        if ($this->relationLoaded('variants')) {
            return $this->variants->where('status', 1)->first() ?? $this->variants->first();
        }
        return $this->variants()->where('status', 1)->first() ?? $this->variants()->first();
    }

    public function getEffectiveImageAttribute()
    {
        $default = $this->default_variant;
        if ($default && !empty($default->image)) {
            return $default->image;
        }

        // Fallback to first available active variant with an image
        if ($this->relationLoaded('variants')) {
            $activeWithImage = $this->variants->where('status', 1)->first(fn($v) => !empty($v->image));
            if ($activeWithImage) {
                return $activeWithImage->image;
            }
        } else {
            $activeWithImage = $this->variants()->where('status', 1)->whereNotNull('image')->where('image', '!=', '')->first();
            if ($activeWithImage) {
                return $activeWithImage->image;
            }
        }

        return $this->image;
    }

    public function getEffectiveImageUrlAttribute(): string
    {
        $imgPath = $this->effective_image;

        if ($imgPath) {
            if (\Illuminate\Support\Str::startsWith($imgPath, ['http://', 'https://', 'data:'])) {
                return $imgPath;
            }
            if (\Illuminate\Support\Str::startsWith($imgPath, ['variant-images/', 'products/'])) {
                return asset('storage/' . $imgPath);
            }
            return asset('uploads/' . $imgPath);
        }

        return 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect width="100" height="100" fill="%23f1f5f9"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" font-family="sans-serif" font-size="12" fill="%2394a3b8">No Image</text></svg>';
    }

    public function getEffectivePriceAttribute()
    {
        $default = $this->default_variant;
        if ($default && $default->price > 0) {
            return $default->price;
        }
        return $this->price;
    }

    public function getEffectiveOriginalPriceAttribute()
    {
        $default = $this->default_variant;
        if ($default && $default->original_price > 0) {
            return $default->original_price;
        }
        return $this->original_price;
    }

    public function getEffectiveStockAttribute(): int
    {
        if ($this->relationLoaded('variants')) {
            $active = $this->variants->where('status', 1);
            if ($active->count() > 0) {
                return (int) $active->sum('stock');
            }
        } else {
            $active = $this->variants()->where('status', 1)->get();
            if ($active->count() > 0) {
                return (int) $active->sum('stock');
            }
        }
        return (int) ($this->stock ?? 0);
    }

    public function getDiscountPercentageAttribute()
    {
        $default = $this->default_variant;
        if ($default && $default->original_price > 0) {
            $originalPrice = (float) $default->original_price;
            $sellingPrice  = (float) $default->price;
            if ($originalPrice > 0 && $sellingPrice < $originalPrice) {
                return round((($originalPrice - $sellingPrice) / $originalPrice) * 100);
            }
        }

        $originalPrice = (float) $this->original_price;
        $sellingPrice  = (float) $this->price;

        if ($originalPrice > 0 && $sellingPrice < $originalPrice) {
            return round((($originalPrice - $sellingPrice) / $originalPrice) * 100);
        }

        return 0;
    }
}

