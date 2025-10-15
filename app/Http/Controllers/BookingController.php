<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Email;
use App\Models\AvailableDate;
use App\Http\Requests\BookingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingMail;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Show the booking form for a specific package
     */
    public function show($package_id)
    {
        $package = Package::with(['user', 'availableDates' => function($query) {
            $query->where('date', '>=', now()->format('Y-m-d'))
                  ->orderBy('date');
        }])->findOrFail($package_id);

        return view('booking.form', compact('package'));
    }

    /**
     * Process the booking form submission
     */
    public function store(BookingRequest $request)
    {
        DB::beginTransaction();
        
        try {
            $package = Package::findOrFail($request->package_id);
            $availableDate = AvailableDate::findOrFail($request->available_date_id);
            
            // Handle user creation or authentication
            $user = null;
            
            if (Auth::check()) {
                // User is logged in
                $user = Auth::user();
            } else {
                // User is not logged in - account creation is required
                Log::info('Creating new user account for booking', [
                    'email' => $request->customer_email,
                    'name' => $request->customer_name,
                    'has_password' => !empty($request->password)
                ]);
                
                $existingUser = User::where('email', $request->customer_email)->first();
                
                if ($existingUser) {
                    Log::warning('Existing user found with email: ' . $request->customer_email);
                    return back()->withErrors([
                        'customer_email' => 'An account with this email already exists. Please login first.'
                    ])->withInput();
                } else {
                    // Create new user account
                    $userData = [
                        'name' => $request->customer_name,
                        'email' => $request->customer_email,
                        'password' => Hash::make($request->password),
                        'email_verified_at' => now(),
                    ];
                    
                    Log::info('Creating user with data:', $userData);
                    
                    $user = User::create($userData);
                    
                    if ($user) {
                        Log::info('User created successfully:', ['id' => $user->id, 'email' => $user->email]);
                    } else {
                        Log::error('Failed to create user');
                        throw new \Exception('Failed to create user account');
                    }
                    
                    // Assign Customer role
                    try {
                        $user->assignRole('Customer');
                    } catch (\Exception $e) {
                        Log::error('Failed to assign Customer role to user: ' . $user->email, ['error' => $e->getMessage()]);
                        // Continue with the process even if role assignment fails
                    }
                    
                    // Auto-login the new user
                    Auth::login($user);
                    
                    // Verify the user is actually logged in
                    if (!Auth::check()) {
                        Log::error('Failed to auto-login user after creation: ' . $user->email);
                        // Don't throw exception, let them login manually
                    }
                }
            }

            // Generate invoice number
            $invoiceNumber = 'INV-' . date('Y') . date('m') . '-' . str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);

            // Create invoice with event details
            $invoice = Invoice::create([
                'user_id' => $user->id,
                'package_id' => $package->id,
                'invoice_number' => $invoiceNumber,
                'total' => $package->price,
                'status' => 'unpaid', // Use valid enum value
                'available_date_id' => $availableDate->id,
                'transaction_date' => null,
                'transaction_id' => null,
                // Event details from booking form
                'event_type' => $request->event_type,
                'event_date' => $request->event_date,
                'event_time' => $request->event_time,
                'event_location' => $request->event_location,
                'venue_address' => $request->venue_address,
                'event_description' => $request->event_description,
                'special_requirements' => $request->special_requirements,
                'technical_requirements' => $request->technical_requirements,
                'guest_count' => $request->guest_count,
                'event_duration' => $request->event_duration,
                'budget_range' => $request->budget_range,
                'customer_phone' => $request->customer_phone,
                'customer_company' => $request->customer_company,
            ]);

            // Set initial booking status as pending approval
            $invoice->update(['booking_status' => 'pending']);

            // Create email record for the booking request
            $email = Email::create([
                'invoice_id' => $invoice->id,
                'user_id' => $user->id,
                'package_id' => $package->id,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'event_type' => $request->event_type,
                'event_date' => $request->event_date,
                'event_location' => $request->event_location,
                'event_description' => $request->event_description,
                'special_requirements' => $request->special_requirements,
                'subject' => 'New Booking Request for ' . $package->package_name,
                'body' => $this->generateBookingEmailBody($request, $package, $user),
                'status' => 'waiting',
                'sent_at' => now(),
            ]);

            // Send email notification to artist via Mailtrap
            try {
                Mail::to($package->user->email)->send(new BookingMail($invoice, null, $user, $email));
                Log::info('Booking notification email sent to artist: ' . $package->user->email);
            } catch (\Exception $e) {
                Log::error('Failed to send booking email: ' . $e->getMessage());
                // Don't fail the booking if email fails
            }

            // Log the booking creation
            Log::info('Booking created for package: ' . $package->id . ', invoice: ' . $invoice->id);

            DB::commit();

            // Redirect based on user authentication status
            if (Auth::check()) {
                // Check user role to redirect to appropriate dashboard
                $user = Auth::user();
                
                // Get user role name
                $roleName = $user->roles->first()?->name ?? '';
                
                if ($roleName === 'Admin') {
                    return redirect()->route('admin.home')->with('success', 
                        'Booking request submitted successfully! You can track your booking in your dashboard.');
                } elseif ($roleName === 'Artist') {
                    return redirect()->route('artists.index')->with('success', 
                        'Booking request submitted successfully! You can track your booking in your dashboard.');
                } elseif ($roleName === 'Customer') {
                    return redirect()->route('customer.bookings')->with('success', 
                        'Booking request submitted successfully! You can track your booking in your dashboard.');
                } else {
                    return redirect()->route('home')->with('success', 
                        'Booking request submitted successfully! You can track your booking in your dashboard.');
                }
            } else {
                return redirect()->route('invoice.details', ['invoice_id' => $invoice->id])
                    ->with('success', 
                    'Booking request submitted successfully! The artist will review your request.');
            }

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Booking error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Something went wrong. Please try again.'])->withInput();
        }
    }

    /**
     * Show booking confirmation page
     */
    public function confirmation($invoice_id)
    {
        $invoice = Invoice::with(['package', 'user', 'availableDate'])->findOrFail($invoice_id);
        
        // Check if user can view this invoice
        if (Auth::check() && Auth::id() !== $invoice->user_id) {
            abort(403);
        }

        return view('booking.confirmation', compact('invoice'));
    }

    /**
     * Show payment form
     */
    public function payment($invoice_id)
    {
        $invoice = Invoice::with(['package', 'user', 'availableDate'])->findOrFail($invoice_id);
        
                // Check if booking is approved
        if ($invoice->booking_status !== 'approved') {
            return redirect()->back()->with('error', 'This booking has not been approved yet. Please wait for artist approval.');
        }

        return view('booking.payment', compact('invoice'));
    }

    /**
     * Process payment submission
     */
    public function processPayment(Request $request, $invoice_id)
    {
        try {
            $request->validate([
                'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:25600',
            ]);

            $invoice = Invoice::findOrFail($invoice_id);
            
            Log::info('Processing payment for invoice: ' . $invoice_id, [
                'user_id' => $invoice->user_id,
                'file_uploaded' => $request->hasFile('payment_proof'),
                'file_name' => $request->file('payment_proof') ? $request->file('payment_proof')->getClientOriginalName() : null,
            ]);
            
            // Store the file
            $paymentProofPath = $request->file('payment_proof')->store('order', 'public');
            Log::info('Payment proof stored at: ' . $paymentProofPath);
            
            // Update invoice status
            $invoice->update([
                'status' => 'waiting',
                'transaction_id' => (string) random_int(100000, 999999),
                'transaction_date' => now(),
            ]);

            // Create order record
            $order = Order::create([
                'invoice_id' => $invoice->id,
                'user_id' => $invoice->user_id,
                'payment_proof' => $paymentProofPath,
                'status' => 'waiting approval',
            ]);
            
            Log::info('Order created successfully: ' . $order->id);

            return redirect()->route('booking.confirmation', $invoice_id)
                ->with('success', 'Payment proof uploaded successfully! Your payment is being reviewed.');
                
        } catch (\Exception $e) {
            Log::error('Payment processing error: ' . $e->getMessage(), [
                'invoice_id' => $invoice_id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withErrors(['error' => 'Failed to upload payment proof. Please try again.'])->withInput();
        }
    }

    /**
     * Show customer's bookings
     */
    public function myBookings()
    {
        // If user is not authenticated, show all bookings by email
        if (!Auth::check()) {
            return redirect()->route('home')->with('error', 'Please login to view your bookings');
        }

        $invoices = Invoice::with(['package', 'order'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('customer.bookings', compact('invoices'));
    }

    /**
     * Show all bookings for admin dashboard
     */
    public function adminIndex()
    {
        $invoices = Invoice::with(['package.user', 'user', 'availableDate', 'order'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('booking.index', compact('invoices'));
    }

    /**
     * Generate email body for booking request
     */
    /**
     * Approve a booking request (for artists and admins)
     */
    public function approve($id)
    {
        try {
            $invoice = Invoice::with('package')->findOrFail($id);
            $user = Auth::user();
            
            // Check if the authenticated user is authorized to approve this booking
            // Either the artist who owns the package OR an admin
            $isArtist = $invoice->package && Auth::id() === $invoice->package->user_id;
            $roleName = $user->roles->first()?->name ?? '';
            $isAdmin = $roleName === 'Admin';
            
            if (!$isArtist && !$isAdmin) {
                return redirect()->back()->with('error', 'You are not authorized to approve this booking.');
            }

            $invoice->update(['booking_status' => 'approved']);
            
            return redirect()->back()->with('success', 'Booking approved successfully! Customer can now proceed with payment.');
            
        } catch (\Exception $e) {
            Log::error('Error approving booking: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to approve booking. Please try again.');
        }
    }

    /**
     * Reject a booking request (for artists and admins)
     */
    public function reject(Request $request, $id)
    {
        try {
            $invoice = Invoice::with('package')->findOrFail($id);
            $user = Auth::user();
            
            // Check if the authenticated user is authorized to reject this booking
            // Either the artist who owns the package OR an admin
            $isArtist = $invoice->package && Auth::id() === $invoice->package->user_id;
            $roleName = $user->roles->first()?->name ?? '';
            $isAdmin = $roleName === 'Admin';
            
            if (!$isArtist && !$isAdmin) {
                return redirect()->back()->with('error', 'You are not authorized to reject this booking.');
            }

            $rejectionReason = $request->input('rejection_reason', 'No reason provided');
            
            $invoice->update([
                'booking_status' => 'rejected',
                'rejection_reason' => $rejectionReason
            ]);
            
            return redirect()->back()->with('success', 'Booking rejected successfully.');
            
        } catch (\Exception $e) {
            Log::error('Error rejecting booking: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to reject booking. Please try again.');
        }
    }

    /**
     * Generate email body for booking request
     */
    private function generateBookingEmailBody($request, $package, $user)
    {
        return "
        New booking request received:
        
        Package: {$package->package_name}
        Customer: {$request->customer_name}
        Email: {$request->customer_email}
        Phone: {$request->customer_phone}
        
        Event Details:
        Type: {$request->event_type}
        Date: {$request->event_date}
        Location: {$request->event_location}
        Description: {$request->event_description}
        Special Requirements: {$request->special_requirements}
        
        Please review and respond to this booking request.
        ";
    }
}
