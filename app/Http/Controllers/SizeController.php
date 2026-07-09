<?php

namespace App\Http\Controllers;

use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    public function index()
    {
        $sizes = Size::latest()->paginate(10);

        return view('sizes.index', compact('sizes'));
    }

    public function create()
    {
        return view('sizes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:20|unique:sizes,name',
            'status' => 'required|boolean',
        ]);

        Size::create([
            'name' => strtoupper($request->name),
            'status' => $request->status,
        ]);

        return redirect()
            ->route('sizes.index')
            ->with('success', 'Size added successfully.');
    }

    public function edit(Size $size)
    {
        return view('sizes.edit', compact('size'));
    }

    public function update(Request $request, Size $size)
    {
        $request->validate([
            'name' => 'required|max:20|unique:sizes,name,' . $size->id,
            'status' => 'required|boolean',
        ]);

        $size->update([
            'name' => strtoupper($request->name),
            'status' => $request->status,
        ]);

        return redirect()
            ->route('sizes.index')
            ->with('success', 'Size updated successfully.');
    }

    public function destroy(Size $size)
    {
        $size->delete();

        return redirect()
            ->route('sizes.index')
            ->with('success', 'Size deleted successfully.');
    }
}