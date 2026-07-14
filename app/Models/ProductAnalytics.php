<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAnalytics extends Model
{
    protected $table = 'product_analytics';

    protected $fillable = [
        'product_id',
        'seller_id',
        'wishlist_count',
        'cart_count',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Retrieve or create the analytics record for a given product.
     * Automatically populates seller_id from the product.
     */
    public static function recordFor(int $productId): self
    {
        $record = self::firstOrCreate(
            ['product_id' => $productId],
            [
                'seller_id'      => Product::find($productId)?->seller_id,
                'wishlist_count' => 0,
                'cart_count'     => 0,
            ]
        );

        return $record;
    }

    /**
     * Safely increment wishlist_count by 1.
     */
    public function incrementWishlist(): void
    {
        $this->increment('wishlist_count');
    }

    /**
     * Safely decrement wishlist_count by 1, never below 0.
     */
    public function decrementWishlist(): void
    {
        if ($this->wishlist_count > 0) {
            $this->decrement('wishlist_count');
        }
    }

    /**
     * Safely increment cart_count by 1.
     */
    public function incrementCart(): void
    {
        $this->increment('cart_count');
    }

    /**
     * Safely decrement cart_count by 1, never below 0.
     */
    public function decrementCart(): void
    {
        if ($this->cart_count > 0) {
            $this->decrement('cart_count');
        }
    }
}
