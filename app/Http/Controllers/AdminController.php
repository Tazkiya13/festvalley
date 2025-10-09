<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Check if user has admin role
        if (!Auth::user()->hasRole('Admin')) {
            abort(403, 'Access denied. Admin access required.');
        }

        // Get orders that need approval (payment proof uploaded but not approved/rejected)
        $pendingOrders = Order::whereNotNull('bukti_transfer')
            ->whereNull('approved_at')
            ->whereNull('rejected_at')
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.dashboard', compact('pendingOrders'));
    }
}
