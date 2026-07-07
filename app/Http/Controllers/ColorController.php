<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Color;

class ColorController extends Controller
{
    public function index()
    {
        $colors = Color::latest()->get();

        return view('colors.index', compact('colors'));
    }

    public function create()
    {
        return view('colors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:100|unique:colors,name',
            'code'   => 'required',
            'status' => 'required|boolean',
        ]);

        Color::create([
            'name'   => $request->name,
            'code'   => $request->code,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('colors.index')
            ->with('success', 'Color added successfully.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $color = Color::findOrFail($id);

        return view(
            'colors.edit',
            compact('color')
        );
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:colors,name,' . $id,
            'code' => 'required',
            'status' => 'required|boolean',
        ]);

        $color = Color::findOrFail($id);

        $color->update([
            'name' => $request->name,
            'code' => $request->code,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('colors.index')
            ->with('success', 'Color updated successfully.');
    }

    public function destroy(string $id)
    {
        $color = Color::findOrFail($id);

        $color->delete();

        return redirect()
            ->route('colors.index')
            ->with('success', 'Color deleted successfully.');
    }
}