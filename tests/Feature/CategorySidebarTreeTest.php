<?php

namespace Tests\Feature;

use App\Models\Category;
use Tests\TestCase;

class CategorySidebarTreeTest extends TestCase
{
    public function test_sidebar_tree_view_renders_nested_categories(): void
    {
        $grandchild = new Category(['id' => 3, 'name' => 'Boots']);
        $grandchild->setRelation('childrenRecursive', collect());

        $child = new Category(['id' => 2, 'name' => 'Men']);
        $child->setRelation('childrenRecursive', collect([$grandchild]));

        $root = new Category(['id' => 1, 'name' => 'Fashion']);
        $root->setRelation('childrenRecursive', collect([$child]));

        $html = view('categories.sidebar-tree', [
            'categories' => collect([$root]),
            'selectedCategoryId' => null,
        ])->render();

        $this->assertStringContainsString('Fashion', $html);
        $this->assertStringContainsString('Men', $html);
        $this->assertStringContainsString('Boots', $html);
        $this->assertStringContainsString('toggle-icon', $html);
    }
}
