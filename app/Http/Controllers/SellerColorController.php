<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\SellerActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerColorController extends Controller
{
    private function getSellerId(): int
    {
        return Auth::guard('seller')->id();
    }

    public function index()
    {
        $sellerId = $this->getSellerId();
        // Seller sees only their own colors
        $colors = Color::where('seller_id', $sellerId)->latest()->get();

        return view('seller.colors.index', compact('colors'));
    }

    public function create()
    {
        return view('seller.colors.create');
    }

    public function store(Request $request)
    {
        $sellerId = $this->getSellerId();

        $request->validate([
            'name'   => 'required|string|max:100',
            'code'   => 'required|string|max:50',
            'status' => 'required|boolean',
        ]);

        // Check uniqueness per seller
        $exists = Color::where('seller_id', $sellerId)->where('name', $request->name)->exists();
        if ($exists) {
            return back()->withInput()->withErrors(['name' => 'You already have a color with this name.']);
        }

        $color = Color::create([
            'seller_id' => $sellerId,
            'name'      => $request->name,
            'code'      => $request->code,
            'status'    => $request->status,
        ]);

        SellerActivity::log($sellerId, 'Created Color', "Created color: {$color->name}");

        return redirect()
            ->route('seller.colors.index')
            ->with('success', 'Color created successfully.');
    }

    public function edit(Color $color)
    {
        if ($color->seller_id !== $this->getSellerId()) {
            abort(403, 'Unauthorized action.');
        }

        return view('seller.colors.edit', compact('color'));
    }

    public function update(Request $request, Color $color)
    {
        $sellerId = $this->getSellerId();

        if ($color->seller_id !== $sellerId) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name'   => 'required|string|max:100',
            'code'   => 'required|string|max:50',
            'status' => 'required|boolean',
        ]);

        // Check uniqueness per seller
        $exists = Color::where('seller_id', $sellerId)
            ->where('name', $request->name)
            ->where('id', '!=', $color->id)
            ->exists();

        if ($exists) {
            return back()->withInput()->withErrors(['name' => 'You already have a color with this name.']);
        }

        $color->update([
            'name'   => $request->name,
            'code'   => $request->code,
            'status' => $request->status,
        ]);

        SellerActivity::log($sellerId, 'Updated Color', "Updated color: {$color->name}");

        return redirect()
            ->route('seller.colors.index')
            ->with('success', 'Color updated successfully.');
    }

    public function destroy(Color $color)
    {
        $sellerId = $this->getSellerId();

        if ($color->seller_id !== $sellerId) {
            abort(403, 'Unauthorized action.');
        }

        $colorName = $color->name;
        $color->delete();

        SellerActivity::log($sellerId, 'Deleted Color', "Deleted color: {$colorName}");

        return redirect()
            ->route('seller.colors.index')
            ->with('success', 'Color deleted successfully.');
    }
}
