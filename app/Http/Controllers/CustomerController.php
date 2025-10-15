<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Display customer dashboard with bookings and invoices
     */
    public function dashboard()
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            return redirect()->route('home')->with('error', 'Please login to view your dashboard');
        }

        $user = Auth::user();

        // Get recent bookings (invoices with related data)
        $recentBookings = Invoice::with(['package.user', 'email', 'order', 'availableDate'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get all invoices for statistics
        $allInvoices = Invoice::where('user_id', $user->id)->get();
        
        // Get all orders for statistics
        $allOrders = Order::where('user_id', $user->id)->get();

        // Dashboard statistics
        $stats = [
            'total_bookings' => $allInvoices->count(),
            'pending_bookings' => $allInvoices->where('status', 'waiting')->count(),
            'completed_bookings' => $allInvoices->where('status', 'paid')->count(),
            'total_spent' => $allInvoices->where('status', 'paid')->sum('total'),
            'pending_orders' => $allOrders->where('status', 'waiting approval')->count(),
            'approved_orders' => $allOrders->where('status', 'approved')->count(),
        ];

        // Recent activity (last 10 bookings/invoices)
        $recentActivity = Invoice::with(['package.user', 'order'])
            ->where('user_id', $user->id)
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();

        return view('customer.dashboard', compact('recentBookings', 'stats', 'recentActivity'));
    }

    /**
     * Get all customer bookings (for dashboard tabs/sections)
     */
    public function bookings()
    {
        if (!Auth::check()) {
            return redirect()->route('home')->with('error', 'Please login to view your bookings');
        }

        $invoices = Invoice::with(['package.user', 'email', 'order'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('customer.dashboard', compact('invoices'))->with('activeTab', 'bookings');
    }

    /**
     * Get all customer invoices (for dashboard tabs/sections)  
     */
    public function invoices()
    {
        if (!Auth::check()) {
            return redirect()->route('home')->with('error', 'Please login to view your invoices');
        }

        $invoices = Invoice::with(['package.user', 'orders'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('customer.dashboard', compact('invoices'))->with('activeTab', 'invoices');
    }
}
