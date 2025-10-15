<?php

namespace App\Http\Controllers;

use App\Models\Email;
use App\Jobs\SendEmail;
use App\Models\Invoice;
use App\Mail\BookingApprovedMail;
use App\Mail\BookingRejectedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class EmailController extends Controller
{
    public function index()
    {
        Gate::authorize('view customer booking'); // Authorize the user to view bookings
        $auth = Auth::user();
        // Admin: lihat semua booking
        if ($auth->hasRole('Admin')) {
            $emails = Email::with(['package', 'availableDate', 'user', 'invoice'])->orderBy('id', 'desc')->paginate(10);
        }
        // Artist: lihat booking untuk package miliknya
        elseif ($auth->hasRole('Artist')) {
            $emails = Email::whereHas('invoice.package', function($q) use ($auth) {
                $q->where('user_id', $auth->id);
            })->with(['package', 'availableDate', 'user', 'invoice'])->orderBy('id', 'desc')->paginate(10);
        }
        // Customer: lihat booking miliknya
        else {
            $emails = Email::whereHas('invoice', function($q) use ($auth) {
                $q->where('user_id', $auth->id);
            })->with(['package', 'availableDate', 'user', 'invoice'])->orderBy('id', 'desc')->paginate(10);
        }
        return view('booking.index', compact('emails'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create customer booking'); // Authorize the user to create a booking
        $request->validate([
            'invoice_id' => 'required|integer|exists:invoices,id',
            'order_id' => 'required|integer|exists:orders,id',
            'user_id' => 'required|integer|exists:users,id',
        ]);

       $mail = Email::create([
            'invoice_id' => $request->invoice_id,
            'order_id' => $request->order_id,
            'user_id' => $request->user_id,
            'status' => 'waiting',
        ]);

        // dd($mail->u);

        SendEmail::dispatch($request->invoice_id, $request->order_id, $request->user_id, $mail->id);

        return redirect()->route('admin.invoice.show')->with('success', 'Booking created successfully.');
    }
        // dd($selectedDate->toArray());
        // return view('invoice.detail', compact('invoice', 'package', 'user', 'selectedDate'));

    public function show()
    {
        $invoices = Invoice::with(['package.user', 'availableDate', 'orders'])->paginate(13); // Get all invoices with pagination

        return view('invoice.show', compact('invoices'));
    }

    public function approve($id)
    {
        $email = Email::with(['invoice.package.user', 'user'])->findOrFail($id);
        $email->update(['status' => 'approved']);

        // Also update the invoice booking status
        $email->invoice->update(['booking_status' => 'approved']);

        // Send approval email to customer
        try {
            Mail::to($email->customer_email)->send(new BookingApprovedMail($email, $email->invoice));
        } catch (\Exception $e) {
            Log::error('Failed to send booking approval email: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Booking approved and customer notified successfully.'
        ]);
    }

    public function reject(Request $request, $id)
    {
        $email = Email::with(['invoice.package.user', 'user'])->findOrFail($id);
        $email->update(['status' => 'rejected']);

        // Also update the invoice booking status
        $rejectionReason = $request->input('rejection_reason', 'No specific reason provided.');
        $email->invoice->update([
            'booking_status' => 'rejected',
            'rejection_reason' => $rejectionReason
        ]);

        // Send rejection email to customer
        try {
            Mail::to($email->customer_email)->send(new BookingRejectedMail($email, $email->invoice, $rejectionReason));
        } catch (\Exception $e) {
            Log::error('Failed to send booking rejection email: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Booking rejected and customer notified successfully.'
        ]);
    }
}
