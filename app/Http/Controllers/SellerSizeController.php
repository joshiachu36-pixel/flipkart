<?php

namespace App\Http\Controllers;

use App\Models\Size;
use App\Models\SellerActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerSizeController extends Controller
{
    private function getSellerId(): int
    {
        return Auth::guard('seller')->id();
    }

    public function index()
    {
        $sellerId = $this->getSellerId();
        // Seller sees only their own sizes
        $sizes = Size::where('seller_id', $sellerId)->latest()->paginate(10);

        return view('seller.sizes.index', compact('sizes'));
    }

    public function create()
    {
        return view('seller.sizes.create');
    }

    public function store(Request $request)
    {
        $sellerId = $this->getSellerId();

        $request->validate([
            'name'   => 'required|string|max:20',
            'status' => 'required|boolean',
        ]);

        $sizeName = strtoupper(trim($request->name));

        // Check uniqueness per seller
        $exists = Size::where('seller_id', $sellerId)->where('name', $sizeName)->exists();
        if ($exists) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Size already exists in your catalog.'], 422);
            }
            return back()->withInput()->withErrors(['name' => 'You already have a size with this name.']);
        }

        $size = Size::create([
            'seller_id' => $sellerId,
            'name'      => $sizeName,
            'status'    => $request->status,
        ]);

        SellerActivity::log($sellerId, 'Created Size', "Created size: {$size->name}");

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'size' => $size]);
        }

        return redirect()
            ->route('seller.sizes.index')
            ->with('success', 'Size created successfully.');
    }

    public function edit(Size $size)
    {
        if ($size->seller_id !== $this->getSellerId()) {
            abort(403, 'Unauthorized action.');
        }

        return view('seller.sizes.edit', compact('size'));
    }

    public function update(Request $request, Size $size)
    {
        $sellerId = $this->getSellerId();

        if ($size->seller_id !== $sellerId) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name'   => 'required|string|max:20',
            'status' => 'required|boolean',
        ]);

        $sizeName = strtoupper(trim($request->name));

        $exists = Size::where('seller_id', $sellerId)
            ->where('name', $sizeName)
            ->where('id', '!=', $size->id)
            ->exists();

        if ($exists) {
            return back()->withInput()->withErrors(['name' => 'You already have a size with this name.']);
        }

        $size->update([
            'name'   => $sizeName,
            'status' => $request->status,
        ]);

        SellerActivity::log($sellerId, 'Updated Size', "Updated size: {$size->name}");

        return redirect()
            ->route('seller.sizes.index')
            ->with('success', 'Size updated successfully.');
    }

    public function destroy(Size $size)
    {
        $sellerId = $this->getSellerId();

        if ($size->seller_id !== $sellerId) {
            abort(403, 'Unauthorized action.');
        }

        $sizeName = $size->name;
        $size->delete();

        SellerActivity::log($sellerId, 'Deleted Size', "Deleted size: {$sizeName}");

        return redirect()
            ->route('seller.sizes.index')
            ->with('success', 'Size deleted successfully.');
    }
}
