<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\AvailableDate;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function index(request $request, $id)
    {
        Gate::authorize('create customer invoice'); // Authorize the user to create an invoice
        $package = Package::findOrFail($id);
        $user = auth()->user();

        // Cek apakah sudah ada invoice untuk user & package ini dengan status unpaid
        $invoice = Invoice::where('user_id', $user->id)
            ->where('package_id', $package->id)
            ->whereIn('status', ['unpaid', 'waiting', 'rejected'])
            ->orderBy('id', 'desc')
            ->first();

        $request->validate([
            'available_date_id' => 'required|exists:available_dates,id',
        ]);

        if (!$invoice) {
            $invoice = new Invoice();
            $invoice->user_id = $user->id;
            $invoice->package_id = $package->id;
            $invoice->invoice_number = 'INV-' . strtoupper(uniqid());
            $invoice->total = $package->price;
            $invoice->status = 'unpaid';
            $invoice->available_date_id = $request['available_date_id'];
            $invoice->transaction_date = null;
            $invoice->transaction_id = null;
            $invoice->save();
        }

        // Ambil tanggal yang dipilih (jika ada invoice)
        $selectedDate = null;
        if ($invoice && $invoice->available_date_id) {
            $selectedDate = AvailableDate::find($invoice->available_date_id);
        }

        return view('invoice.index', compact('package', 'user', 'invoice', 'selectedDate'));
    }

    public function detail($id)
    {
        $invoice = Invoice::with(['package.user', 'availableDate', 'orders'])->findOrFail($id);
        $package = $invoice->package;
        $user = auth()->user();

        $selectedDate = null;
        if ($invoice && $invoice->available_date_id) {
            $selectedDate = AvailableDate::find($invoice->available_date_id);
        }

        return view('invoice.index', compact('package', 'invoice', 'user', 'selectedDate'));
    }

    /**
     * Show customer invoices (public access for customers)
     */
    public function customerIndex()
    {
        if (!Auth::check()) {
            return redirect()->route('home')->with('error', 'Please login to view your invoices');
        }

        $user = auth()->user();
        // Ambil semua invoice milik user yang login
        $invoices = Invoice::with('email')->where('user_id', $user->id)->with('package.user', 'orders')->paginate(10);
        // Ambil order_id pertama dari semua invoice (jika ada)
        $order_id = null;
        foreach ($invoices as $invoice) {
            $order_id = $invoice->orders->first()->id ?? null;
            if ($order_id !== null) {
            break; // keluar dari loop jika sudah dapat order_id pertama yang ditemukan
            }
        }

        // Ambil semua package terkait invoice user (jika view membutuhkan)
        $packages = Package::whereIn('id', $invoices->pluck('package_id'))->with(['user', 'availableDates'])->get();
        return view('customer.invoices', compact('invoices', 'packages', 'order_id'));
    }

    public function show()
    {
        Gate::authorize('view customer invoice');
        $user = auth()->user();
        // Ambil semua invoice milik user yang login
        $invoices = Invoice::with('email')->where('user_id', $user->id)->with('package.user', 'orders')->paginate(10);
        // Ambil order_id pertama dari semua invoice (jika ada)
        $order_id = null;
        foreach ($invoices as $invoice) {
            $order_id = $invoice->orders->first()->id ?? null;
            if ($order_id !== null) {
            break; // keluar dari loop jika sudah dapat order_id pertama yang ditemukan
            }
        }

        // Ambil semua package terkait invoice user (jika view membutuhkan)
        $packages = Package::whereIn('id', $invoices->pluck('package_id'))->with(['user', 'availableDates'])->get();
        return view('invoice.list-invoice', compact('invoices', 'packages', 'order_id'));
    }
}
