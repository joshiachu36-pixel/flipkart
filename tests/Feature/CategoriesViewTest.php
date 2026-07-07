<?php

namespace Tests\Feature;

use App\Models\Category;
use Tests\TestCase;

class CategoriesViewTest extends TestCase
{
    public function test_mega_menu_item_view_can_be_rendered(): void
    {
        $category = new Category();
        $category->id = 1;
        $category->name = 'Electronics';
        $category->setRelation('childrenRecursive', collect());
        $category->setRelation('children', collect());

        $html = view('categories.mega-menu-item', ['category' => $category])->render();

        $this->assertStringContainsString('Electronics', $html);
    }
}
