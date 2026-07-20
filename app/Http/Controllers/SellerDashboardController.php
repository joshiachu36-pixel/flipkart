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

        // Pull any account-level notifications (set by admin when updating seller status)
        $accountNotifKey      = 'seller_account_notification_' . $seller->id;
        $accountNotifications = cache()->pull($accountNotifKey, []);

        // For non-approved sellers, show the status page without product stats
        if (!$seller->isApproved()) {
            return view('seller.dashboard', [
                'seller'               => $seller,
                'stats'                => null,
                'notifications'        => [],
                'accountNotifications' => $accountNotifications,
            ]);
        }

        // Approved sellers get full dashboard stats
        $stats = [
            'total_products'    => $seller->products()->count(),
            'pending_products'  => $seller->products()->where('approval_status', 'Pending')->count(),
            'approved_products' => $seller->products()->where('approval_status', 'Approved')->count(),
            'rejected_products' => $seller->products()->where('approval_status', 'Rejected')->count(),
        ];

        // Pull any pending product notifications from cache (set by admin when updating product status)
        $notifKey      = 'seller_product_notification_' . $seller->id;
        $notifications = cache()->pull($notifKey, []); // pull = get and delete

        return view('seller.dashboard', [
            'seller'               => $seller,
            'stats'                => $stats,
            'notifications'        => $notifications,
            'accountNotifications' => $accountNotifications,
        ]);
    }
}
