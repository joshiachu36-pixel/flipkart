<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class SellerDashboardController extends Controller
{
    public function index()
    {
        $seller = Auth::guard('seller')->user();

        $stats = [
            'total_products'    => $seller->products()->count(),
            'pending_products'  => $seller->products()->where('approval_status', 'Pending')->count(),
            'approved_products' => $seller->products()->where('approval_status', 'Approved')->count(),
            'rejected_products' => $seller->products()->where('approval_status', 'Rejected')->count(),
        ];

        // Pull any pending notifications from cache (set by admin when updating product status)
        $notifKey      = 'seller_product_notification_' . $seller->id;
        $notifications = cache()->pull($notifKey, []); // pull = get and delete

        return view('seller.dashboard', compact('stats', 'notifications'));
    }
}
