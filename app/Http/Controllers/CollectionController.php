<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Collection;
use Illuminate\Support\Str;

class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    $collections = Collection::all();

    return view(
        'collections.create',
        compact('collections')
    );
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Collection::create([
    'name' => $request->name,
    'slug' => Str::slug($request->name),
    'description' => $request->description,
    'discount_type' => $request->discount_type,
    'discount_value' => $request->discount_value,
    'status' => $request->status,
]);

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $collection = Collection::findOrFail($id);

        return response()->json($collection);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $collection = Collection::findOrFail($id);

        $collection->update([
    'name' => $request->name,
    'slug' => Str::slug($request->name),
    'description' => $request->description,
    'discount_type' => $request->discount_type,
    'discount_value' => $request->discount_value,
    'status' => $request->status,
]);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $collection = Collection::findOrFail($id);

        $collection->delete();

        return redirect()->back();
    }
}
