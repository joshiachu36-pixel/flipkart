<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopSearchTest extends TestCase
{
    use RefreshDatabase;
    public function test_shop_header_search_form_points_to_search_route(): void
    {
        $html = view('layout.shop-header', ['categories' => collect()])->render();

        $this->assertStringContainsString('/search', $html);
        $this->assertStringContainsString('name="search"', $html);
    }
}
