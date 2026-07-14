<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seller;

class AdminSellerController extends Controller
{
    public function index()
    {
        $sellers = Seller::latest()->paginate(10);
        return view('admin.sellers.index', compact('sellers'));
    }

    public function updateStatus(Request $request, Seller $seller)
    {
        $validated = $request->validate([
            'status' => 'required|in:Pending,Approved,Rejected,Suspended'
        ]);

        $seller->update(['status' => $validated['status']]);

        return back()->with('success', "Seller status updated to {$validated['status']}.");
    }
}
