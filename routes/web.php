<?php

use App\Models\Package;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ArtistsController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminMessageController;
use App\Http\Controllers\CustomerMessageController;

// Homepage route - moved from /landingpage to /
Route::get('/', function () {
    $packages = Package::take(6)->get(); // Limit to maximum 6 packages for slider
    return view('landingpage.discover', compact('packages'));
})->name('home');

// Auth routes
Route::middleware(['guest', 'revalidate'])->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/register', [AuthController::class, 'store'])->name('auth.register');

    Route::get('/chat-customer-firtsTime', [CustomerMessageController::class, 'chatCustomer'])->name('chat.customer.firstTime');
});

// Public customer features (no authentication required)
// Package browsing for customers
Route::get('/packages/browse', [CardController::class, 'index'])->name('packages.browse');
Route::get('/packages/search', [CardController::class, 'search'])->name('packages.search');
Route::get('/api/package/{id}', [CardController::class, 'getPackageDetails'])->name('package.details.api');

// Booking system for customers (no admin access required)
Route::get('/book/{package_id}', [BookingController::class, 'show'])->name('booking.form');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/booking/confirmation/{invoice_id}', [BookingController::class, 'confirmation'])->name('booking.confirmation');
Route::get('/booking/payment/{invoice_id}', [BookingController::class, 'payment'])->name('booking.payment');
Route::post('/booking/payment/{invoice_id}', [BookingController::class, 'processPayment'])->name('booking.process-payment');

// Customer booking and invoice tracking
Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('customer.bookings');
Route::get('/my-invoices', [InvoiceController::class, 'customerIndex'])->name('customer.invoices');
Route::get('/invoice/{id}', [InvoiceController::class, 'detail'])->name('invoice.detail');

// Admin-only Profile routes
Route::middleware(['auth', 'revalidate'])->group(function () {
    Route::get('/admin/profiles', [ProfileController::class, 'adminIndex'])->name('profile.admin.index');
    Route::get('/admin/profiles/{id}', [ProfileController::class, 'adminShow'])->name('profile.admin.show');
    Route::get('/admin/profile/create', [ProfileController::class, 'adminCreate'])->name('profile.admin.create');
    Route::post('/admin/profile/store', [ProfileController::class, 'adminStore'])->name('profile.admin.store');
    Route::get('/admin/profile/edit/{id}', [ProfileController::class, 'adminEdit'])->name('profile.admin.edit');
    Route::put('/admin/profile/update/{id}', [ProfileController::class, 'update'])->name('profile.admin.update');
});

// Admin-only Order routes
Route::middleware(['auth', 'revalidate'])->group(function () {
    Route::get('/order', [OrderController::class, 'index'])->name('order.index')->middleware('can:view customer order');
    Route::get('/order/approve/{id}', [OrderController::class, 'approve'])->name('order.approve')->middleware('can:approve customer order');
    Route::get('/order/reject/{id}', [OrderController::class, 'reject'])->name('order.reject')->middleware('can:reject customer order');
    Route::post('/order/store', [OrderController::class, 'store'])->name('order.store')->middleware('can:view customer order');
    Route::post('/order/{id}', [OrderController::class, 'update'])->name('order.update')->middleware('can:view customer order');
});

// Admin-only Invoice routes
Route::middleware(['auth', 'revalidate'])->group(function () {
    Route::post('/admin/invoice/{id}', [InvoiceController::class, 'index'])->name('admin.invoice.index')->middleware('can:view customer invoice');
    Route::get('/admin/invoice', [InvoiceController::class, 'show'])->name('admin.invoice.show')->middleware('can:view customer invoice');
});

// Public card detail route (accessible by anyone)
Route::get('/card/detail/{id}', [CardController::class, 'show'])->name('card.detail');

// Artist routes (artists can only manage their own packages)
Route::middleware(['auth', 'revalidate'])->group(function () {
    Route::get('/artist', [ArtistsController::class, 'index'])->name('artists.index')->middleware('can:view package artists');
    Route::get('/artist/edit/{id}', [ArtistsController::class, 'edit'])->name('artists.edit')->middleware('can:edit package artists');
    Route::patch('/artist/update/{id}', [ArtistsController::class, 'update'])->name('artists.update')->middleware('can:edit package artists');
    Route::get('/artist/booking-requests', [ArtistsController::class, 'bookingRequests'])->name('artists.booking-requests')->middleware('can:view package artists');
});

// Package routes - accessible by both Admin (view/create/edit/delete package) and Artist (view/create/edit/delete package artists)
Route::middleware(['auth', 'revalidate'])->group(function () {
    // View packages - both Admins and Artists can view
    Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');
    
    // Create package routes - both Admins and Artists can create (but Artists only for themselves)
    Route::get('/packages/create', [PackageController::class, 'create'])->name('packages.create');
    Route::post('/packages', [PackageController::class, 'store'])->name('packages.store');
    
    // Edit/Update/Delete - controlled by policies in the controller
    Route::get('/packages/{id}', [PackageController::class, 'edit'])->name('packages.edit');
    Route::patch('/packages/{id}', [PackageController::class, 'update'])->name('packages.update');
    Route::delete('/packages/{id}', [PackageController::class, 'destroy'])->name('packages.destroy');
});

// Customer Chat routes
Route::post('/send-message', [CustomerMessageController::class, 'sendMessageCustomer'])->name('send.message');
Route::get('/chat-customer/{customer_phone}/{name}', [CustomerMessageController::class, 'passingCustomerToAdmin'])->name('customer.to.admin');
Route::post('/send-message-customer', [CustomerMessageController::class, 'chatCustomerToAdmin'])->name('customer.to.admin.after');

// Admin-only routes (restricted to Admin role)
Route::middleware(['auth', 'revalidate'])->group(function () {
    Route::get('/admin/chat', [AdminMessageController::class, 'viewChat'])->name('view.chat')->middleware('can:view chat');
    Route::get('/api/list-customers', [AdminMessageController::class, 'getCustomers'])->name('customers')->middleware('can:view chat');
    Route::get('admin/list-chat/{customer_phone}', [AdminMessageController::class, 'index'])->name('chat')->middleware('can:view chat');
    Route::post('/send-message-admin', [AdminMessageController::class, 'sendMessageAdmin'])->name('send.message.admin')->middleware('can:view chat');
    Route::get('/admin/home', [AdminController::class, 'dashboard'])->name('admin.home'); // Already has role check in controller
    Route::get('/admin/booking', [BookingController::class, 'adminIndex'])->name('admin.booking.index')->middleware('can:view customer booking');
    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});

// Admin-only Email Booking Management Routes
Route::middleware(['auth', 'revalidate'])->group(function () {
    Route::get('/admin/email-booking', [EmailController::class, 'index'])->name('admin.email.booking.index')->middleware('can:view customer booking');
    Route::post('/booking/email-store', [EmailController::class, 'store'])->name('booking.email-store')->middleware('can:view customer booking');
    Route::post('/resend-email', [EmailController::class, 'resend'])->name('booking.resend-email')->middleware('can:view customer booking');
});

// Email Booking Approval Routes (public routes accessible via email links)
Route::get('/booking/approve/{id}', [EmailController::class, 'approve'])->name('email.booking.approve');
Route::get('/booking/reject/{id}', [EmailController::class, 'reject'])->name('email.booking.reject');

// User Profile Management Routes (accessible to all authenticated users)
Route::middleware(['auth', 'revalidate'])->group(function () {
    Route::get('/my-profile', [ProfileController::class, 'userProfile'])->name('user.profile');
    Route::put('/my-profile/update', [ProfileController::class, 'updateUserProfile'])->name('user.profile.update');
    Route::put('/my-profile/password', [ProfileController::class, 'updatePassword'])->name('user.password.update');
    Route::delete('/my-profile/delete', [ProfileController::class, 'deleteAccount'])->name('user.account.delete');
});

// Artist Profile Management Routes
Route::middleware(['auth', 'revalidate'])->group(function () {
    Route::get('/artist-profile', [ProfileController::class, 'index'])->name('profile.index')->middleware('can:view profile');
    Route::get('/artist-profile/edit', [ProfileController::class, 'edit'])->name('profile.artist.edit')->middleware('can:edit profile');
    Route::put('/artist-profile/update/{id}', [ProfileController::class, 'update'])->name('profile.artist.update')->middleware('can:edit profile');
    Route::get('/artist-profile/{id}', [ProfileController::class, 'show'])->name('profile.show')->middleware('can:view profile');
});

// Booking Approval Routes (for artists and admins)
Route::middleware(['auth', 'revalidate', 'role:Artist|Admin'])->group(function () {
    Route::post('/booking/approve/{id}', [BookingController::class, 'approve'])->name('booking.approve');
    Route::post('/booking/reject/{id}', [BookingController::class, 'reject'])->name('booking.reject');
});
