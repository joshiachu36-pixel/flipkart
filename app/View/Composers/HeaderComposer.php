<?php

namespace App\View\Composers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Category;
use App\Models\Collection;

class HeaderComposer
{
    public function compose(View $view)
    {
        $headerCategories = Category::whereNull('parent_id')
            ->with('childrenRecursive')
            ->get();

        $headerCollections = Collection::where('status', 1)
            ->orderBy('name')
            ->get();

        $selectedCollection = null;

        if (request()->routeIs('shop.collection')) {

                $selectedCollection = Collection::where(
                    'slug',
                    request()->route('slug')
                )->first();

        }

        $view->with([
        'headerCategories' => $headerCategories,
        'headerCollections' => $headerCollections,
        'selectedCollection' => $selectedCollection,
        ]);
    }
}