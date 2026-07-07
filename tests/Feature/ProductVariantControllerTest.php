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
        $product = Product::create([
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

        $response = $this->post(route('products.variants.store', $product), [
            'variants' => [
                [
                    'color_id' => $color->id,
                    'stock' => 12,
                    'status' => 1,
                ],
            ],
        ]);

        $response->assertRedirect(route('products.variants', $product));

        $this->assertDatabaseHas('product_variants', [
            'product_id' => $product->id,
            'color_id' => $color->id,
            'stock' => 12,
        ]);

        $this->assertEquals(1, ProductVariant::count());
    }
}
