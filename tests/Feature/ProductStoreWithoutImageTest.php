<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductStoreWithoutImageTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_can_be_created_without_an_image(): void
    {
        $category = Category::create([
            'name' => 'Test Category',
            'image' => null,
        ]);

        $response = $this->post('/product/store', [
            'category_id' => $category->id,
            'name' => 'Test Product',
            'description' => 'A sample product',
            'price' => '999',
            'original_price' => '1299',
            'stock' => 10,
            'status' => 1,
        ]);

        $response->assertRedirect('/products');
        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'image' => null,
        ]);
    }
}
