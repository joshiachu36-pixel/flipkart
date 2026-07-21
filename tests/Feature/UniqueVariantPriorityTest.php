<?php

namespace Tests\Feature;

use App\Models\Color;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Seller;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UniqueVariantPriorityTest extends TestCase
{
    use RefreshDatabase;

    private Seller $seller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seller = Seller::create([
            'business_name' => 'Test Seller Store',
            'owner_name' => 'Test Owner',
            'email' => 'seller@test.com',
            'phone' => '1234567890',
            'password' => bcrypt('password'),
            'business_address' => '123 Test St',
            'gst_number' => '22AAAAA0000A1Z5',
            'pan_number' => 'ABCDE1234F',
            'bank_name' => 'Test Bank',
            'bank_account_number' => '123456789012',
            'ifsc_code' => 'TEST0001234',
            'status' => 'Approved',
        ]);
    }

    public function test_seller_can_create_variants_with_unique_priorities(): void
    {
        $product = Product::create([
            'seller_id' => $this->seller->id,
            'name' => 'Smartphone Alpha',
            'description' => 'Test phone',
            'price' => 499.00,
        ]);

        $red = Color::create(['seller_id' => $this->seller->id, 'name' => 'Red', 'code' => '#ff0000', 'status' => 1]);
        $blue = Color::create(['seller_id' => $this->seller->id, 'name' => 'Blue', 'code' => '#0000ff', 'status' => 1]);

        $response = $this->actingAs($this->seller, 'seller')
            ->post(route('seller.products.variants.store', $product), [
                'variants' => [
                    [
                        'color_id' => $red->id,
                        'priority' => 1,
                        'sku' => 'PHONE-RED',
                    ],
                    [
                        'color_id' => $blue->id,
                        'priority' => 2,
                        'sku' => 'PHONE-BLUE',
                    ],
                ],
            ]);

        $response->assertRedirect(route('seller.products.variants.manage', $product));
        $this->assertDatabaseHas('product_variants', ['product_id' => $product->id, 'color_id' => $red->id, 'priority' => 1]);
        $this->assertDatabaseHas('product_variants', ['product_id' => $product->id, 'color_id' => $blue->id, 'priority' => 2]);
    }

    public function test_duplicate_priorities_in_same_product_fails_validation(): void
    {
        $product = Product::create([
            'seller_id' => $this->seller->id,
            'name' => 'Smartphone Beta',
            'description' => 'Test phone',
            'price' => 599.00,
        ]);

        $red = Color::create(['seller_id' => $this->seller->id, 'name' => 'Red Beta', 'code' => '#ff0000', 'status' => 1]);
        $blue = Color::create(['seller_id' => $this->seller->id, 'name' => 'Blue Beta', 'code' => '#0000ff', 'status' => 1]);

        $response = $this->actingAs($this->seller, 'seller')
            ->post(route('seller.products.variants.store', $product), [
                'variants' => [
                    [
                        'color_id' => $red->id,
                        'priority' => 1,
                    ],
                    [
                        'color_id' => $blue->id,
                        'priority' => 1, // Duplicate priority
                    ],
                ],
            ]);

        $response->assertSessionHasErrors(['variants.0.priority', 'variants.1.priority']);
        $this->assertEquals(0, ProductVariant::where('product_id', $product->id)->count());
    }

    public function test_different_products_can_share_the_same_priority(): void
    {
        $productA = Product::create(['seller_id' => $this->seller->id, 'name' => 'Product A', 'description' => 'Desc A', 'price' => 100]);
        $productB = Product::create(['seller_id' => $this->seller->id, 'name' => 'Product B', 'description' => 'Desc B', 'price' => 200]);

        $colorA = Color::create(['seller_id' => $this->seller->id, 'name' => 'Color A', 'code' => '#111', 'status' => 1]);
        $colorB = Color::create(['seller_id' => $this->seller->id, 'name' => 'Color B', 'code' => '#222', 'status' => 1]);

        $this->actingAs($this->seller, 'seller')
            ->post(route('seller.products.variants.store', $productA), [
                'variants' => [
                    ['color_id' => $colorA->id, 'priority' => 1],
                ],
            ]);

        $responseB = $this->actingAs($this->seller, 'seller')
            ->post(route('seller.products.variants.store', $productB), [
                'variants' => [
                    ['color_id' => $colorB->id, 'priority' => 1], // Same priority 1, but different product
                ],
            ]);

        $responseB->assertRedirect(route('seller.products.variants.manage', $productB));
        $this->assertDatabaseHas('product_variants', ['product_id' => $productA->id, 'priority' => 1]);
        $this->assertDatabaseHas('product_variants', ['product_id' => $productB->id, 'priority' => 1]);
    }

    public function test_editing_variant_priority_to_existing_priority_fails(): void
    {
        $product = Product::create(['seller_id' => $this->seller->id, 'name' => 'Product Gamma', 'description' => 'Desc Gamma', 'price' => 300]);
        $red = Color::create(['seller_id' => $this->seller->id, 'name' => 'Red Gamma', 'code' => '#f00', 'status' => 1]);
        $blue = Color::create(['seller_id' => $this->seller->id, 'name' => 'Blue Gamma', 'code' => '#00f', 'status' => 1]);

        ProductVariant::create(['product_id' => $product->id, 'color_id' => $red->id, 'priority' => 1, 'price' => 100, 'stock' => 5, 'status' => 1, 'image' => 'default.jpg']);
        ProductVariant::create(['product_id' => $product->id, 'color_id' => $blue->id, 'priority' => 2, 'price' => 100, 'stock' => 5, 'status' => 1, 'image' => 'default.jpg']);

        // Attempt to edit Blue priority from 2 to 1 (conflicts with Red)
        $response = $this->actingAs($this->seller, 'seller')
            ->post(route('seller.products.variants.store', $product), [
                'variants' => [
                    ['color_id' => $red->id, 'priority' => 1],
                    ['color_id' => $blue->id, 'priority' => 1], // Conflict
                ],
            ]);

        $response->assertSessionHasErrors();
        $this->assertDatabaseHas('product_variants', ['product_id' => $product->id, 'color_id' => $blue->id, 'priority' => 2]);
    }

    public function test_swapping_variant_priorities_succeeds(): void
    {
        $product = Product::create(['seller_id' => $this->seller->id, 'name' => 'Product Swap', 'description' => 'Desc Swap', 'price' => 400]);
        $red = Color::create(['seller_id' => $this->seller->id, 'name' => 'Red Swap', 'code' => '#f00', 'status' => 1]);
        $blue = Color::create(['seller_id' => $this->seller->id, 'name' => 'Blue Swap', 'code' => '#00f', 'status' => 1]);

        ProductVariant::create(['product_id' => $product->id, 'color_id' => $red->id, 'priority' => 1, 'price' => 100, 'stock' => 5, 'status' => 1, 'image' => 'default.jpg']);
        ProductVariant::create(['product_id' => $product->id, 'color_id' => $blue->id, 'priority' => 2, 'price' => 100, 'stock' => 5, 'status' => 1, 'image' => 'default.jpg']);

        // Swap Red to 2 and Blue to 1
        $response = $this->actingAs($this->seller, 'seller')
            ->post(route('seller.products.variants.store', $product), [
                'variants' => [
                    ['color_id' => $red->id, 'priority' => 2],
                    ['color_id' => $blue->id, 'priority' => 1],
                ],
            ]);

        $response->assertRedirect(route('seller.products.variants.manage', $product));
        $this->assertDatabaseHas('product_variants', ['product_id' => $product->id, 'color_id' => $red->id, 'priority' => 2]);
        $this->assertDatabaseHas('product_variants', ['product_id' => $product->id, 'color_id' => $blue->id, 'priority' => 1]);
    }
}
