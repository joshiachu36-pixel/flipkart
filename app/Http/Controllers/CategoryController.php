<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
  public function create()
{
    $treeCategories = Category::whereNull('parent_id')
                              ->with('children')
                              ->get();

    $allCategories = Category::orderBy('name')
                             ->get();

    return view('categories.create', compact(
        'treeCategories',
        'allCategories'
    ));
}
    

    public function store(Request $request)
    {
        $image = null;

        if($request->hasFile('image'))
        {
            $image = time().'.'.$request->image->extension();

            $request->image->move(
                public_path('category_images'),
                $image
            );
        }

        Category::create([
            'name'       => $request->name,
            'parent_id'  => $request->parent_id,
            'image'      => $image,
            
        ]);

        return redirect('/category/create');
    }

    public function index()
    {
        $categories = Category::with('parent')
                        ->orderBy('id')
                        ->get();

        return view(
            'categories.create',
            compact('categories')
        );
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);

        $categories = Category::whereNull('parent_id')
                        ->get();

        return view(
            'categories.edit',
            compact('category','categories')
        );
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $category->name = $request->name;
        $category->parent_id = $request->parent_id;

        if($request->hasFile('image'))
        {
            $image = time().'.'.$request->image->extension();

            $request->image->move(
                public_path('uploads/categories'),
                $image
            );

            $category->image = $image;
        }

        $category->save();

        return redirect('/category/create');
    }

      public function destroy($id)
{
    $category = Category::findOrFail($id);

    if($category->children()->count() > 0)
    {
        return redirect()->back()
            ->with('error',
                'Cannot delete category because it contains subcategories.');
    }

    $category->delete();

    return redirect()->back()
        ->with('success',
            'Category deleted successfully.');
}
}
