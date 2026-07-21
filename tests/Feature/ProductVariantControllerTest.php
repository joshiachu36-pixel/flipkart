<?php

namespace Tests\Feature;

use App\Models\Color;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductVariantControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_stores_variants_and_redirects_back_to_the_product_variants_page(): void
    {
        $seller = \App\Models\Seller::create([
            'business_name' => 'Test Seller Store',
            'owner_name' => 'Test Owner',
            'email' => 'seller2@test.com',
            'phone' => '1234567891',
            'password' => bcrypt('password'),
            'business_address' => '123 Test St',
            'gst_number' => '22AAAAA0000A1Z5',
            'pan_number' => 'ABCDE1234F',
            'bank_name' => 'Test Bank',
            'bank_account_number' => '123456789012',
            'ifsc_code' => 'TEST0001234',
            'status' => 'Approved',
        ]);

        $product = Product::create([
            'seller_id' => $seller->id,
            'name' => 'Test Product',
            'description' => 'A product for testing',
            'price' => 99.99,
            'image' => 'test.jpg',
        ]);

        $color = Color::create([
            'name' => 'Black',
            'code' => '#000000',
            'status' => 1,
        ]);

        $response = $this->actingAs($seller, 'seller')
            ->post(route('seller.products.variants.store', $product), [
                'variants' => [
                    [
                        'color_id' => $color->id,
                        'priority' => 1,
                        'stock' => 12,
                        'status' => 1,
                    ],
                ],
            ]);

        $response->assertRedirect(route('seller.products.variants.manage', $product));

        $this->assertDatabaseHas('product_variants', [
            'product_id' => $product->id,
            'color_id' => $color->id,
            'priority' => 1,
        ]);

        $this->assertEquals(1, ProductVariant::count());
    }
}
