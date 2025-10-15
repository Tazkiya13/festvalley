<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{
    public function index()
    {
        Gate::authorize('view customer order'); // Authorize the user to view orders
        $orders = Order::with('invoice')->orderBy('id', 'desc')->paginate(13); // Ambil semua order dengan pagination

        return view('order.index', compact('orders'));
    }

    public function create()
    {
        return view('order.create');
    }

    public function store(Request $request)
    {
        // dd($request->all());

        // Gate::authorize('create customer order'); // Authorize the user to create an order
        $request->validate([
            'invoice_id' => 'required|integer|exists:invoices,id',
            'user_id' => 'required|integer|exists:users,id',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:25600', // Validate the image file
            // Add other validation rules as needed
        ]);

        Invoice::where('id', $request->invoice_id)->update([
            'status' => 'waiting',
            'transaction_id' => (string) random_int(100000, 999999), // Generate a random transaction ID as string
            'transaction_date' => now(), // Set the current date and time
        ]);

        Order::create([
            'invoice_id' => $request->invoice_id,
            'user_id' => $request->user_id,
            'payment_proof' => $request->file('payment_proof')->store('order', 'public'), // Store the file path
            'status' => 'waiting approval', // Set default status
        ]);

        // Redirect or return a response
        return redirect()->route('admin.invoice.show')->with('success', 'Order created successfully.');
    }

    public function approve($id)
    {
        Gate::authorize('approve customer order'); // Authorize the user to approve an order
        $order = Order::with('invoice')->findOrFail($id);
        // dd($order->toArray());
        $order->update(['status' => 'approved']);
        $order->invoice->update(['status' => 'paid']);

        // Check if request came from admin dashboard
        $referer = request()->header('referer');
        if ($referer && str_contains($referer, 'admin/home')) {
            return redirect()->route('admin.home')->with('success', 'Order approved successfully.');
        }

        return redirect()->route('order.index')->with('success', 'Order approved successfully.');
    }

        public function reject($id)
    {
        Gate::authorize('reject customer order'); // Authorize the user to reject an order
        $order = Order::with('invoice')->findOrFail($id);
        // dd($order->toArray());
        $order->update(['status' => 'rejected']);
        $order->invoice->update(['status' => 'rejected']);

        // Check if request came from admin dashboard
        $referer = request()->header('referer');
        if ($referer && str_contains($referer, 'admin/home')) {
            return redirect()->route('admin.home')->with('success', 'Order rejected successfully.');
        }

        return redirect()->route('order.index')->with('success', 'Order rejected successfully.');
    }

    // public function edit($id)
    // {
    //     // Logic to edit the order
    // }

    public function update(Request $request, $id)
    {
        // Gate::authorize('edit customer order'); // Authorize the user to update an order
        // dd($request->all(), $id); // Hapus atau komentar baris ini
        $request->validate([
            'invoice_id' => 'required|integer|exists:invoices,id',
            'user_id' => 'required|integer|exists:users,id',
            'payment_proof' => 'image|mimes:jpeg,png,jpg,gif|max:25600', // Validate the image file
            'order_id' => 'required|integer|exists:orders,id',
        ]);

        Order::where('id', $request->order_id)->update([
            'invoice_id' => $request->invoice_id,
            'user_id' => $request->user_id,
            'payment_proof' => $request->file('payment_proof')->store('order', 'public'), // Store the file path
            'status' => 'waiting approval', // Set default status
        ]);
        Invoice::where('id', $id)->update([
            'status' => 'waiting',
            'transaction_id' => (string) random_int(100000, 999999), // Generate a random transaction ID as string
            'transaction_date' => now(), // Set the current date and time
        ]);

        // Redirect or return a response
        return redirect()->route('admin.invoice.show')->with('success', 'Order updated successfully.');
    }

    // public function destroy($id)
    // {
    //     // Logic to delete the order
    // }
}
