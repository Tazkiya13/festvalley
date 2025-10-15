@extends('layouts.app')

@section('title', 'My Dashboard - Festvalley')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h3 mb-0">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    My Dashboard
                </h2>
                <small class="text-muted">Welcome back, {{ Auth::user()->name }}!</small>
            </div>
        </div>
    </div>

    @if(isset($stats))
    <!-- Dashboard Statistics -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="h5 mb-0">{{ $stats['total_bookings'] }}</div>
                            <small>Total Bookings</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar-alt fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="h5 mb-0">{{ $stats['pending_bookings'] }}</div>
                            <small>Pending Bookings</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="h5 mb-0">{{ $stats['completed_bookings'] }}</div>
                            <small>Completed</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="h5 mb-0">${{ number_format($stats['total_spent'], 2) }}</div>
                            <small>Total Spent</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-dollar-sign fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Tabs Navigation -->
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="dashboardTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ !isset($activeTab) || $activeTab == 'overview' ? 'active' : '' }}" 
                            id="overview-tab" 
                            data-bs-toggle="tab" 
                            data-bs-target="#overview" 
                            type="button" 
                            role="tab" 
                            aria-controls="overview" 
                            aria-selected="true">
                        <i class="fas fa-home me-1"></i>
                        Overview
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ isset($activeTab) && $activeTab == 'bookings' ? 'active' : '' }}" 
                            id="bookings-tab" 
                            data-bs-toggle="tab" 
                            data-bs-target="#bookings" 
                            type="button" 
                            role="tab" 
                            aria-controls="bookings" 
                            aria-selected="false">
                        <i class="fas fa-calendar-alt me-1"></i>
                        My Bookings
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ isset($activeTab) && $activeTab == 'invoices' ? 'active' : '' }}" 
                            id="invoices-tab" 
                            data-bs-toggle="tab" 
                            data-bs-target="#invoices" 
                            type="button" 
                            role="tab" 
                            aria-controls="invoices" 
                            aria-selected="false">
                        <i class="fas fa-file-invoice me-1"></i>
                        My Invoices
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body">
            <div class="tab-content" id="dashboardTabsContent">
                <!-- Overview Tab -->
                <div class="tab-pane fade {{ !isset($activeTab) || $activeTab == 'overview' ? 'show active' : '' }}" 
                     id="overview" 
                     role="tabpanel" 
                     aria-labelledby="overview-tab">
                    
                    @if(isset($recentBookings) && $recentBookings->count() > 0)
                    <h5 class="mb-3">Recent Bookings</h5>
                    <div class="row">
                        @foreach($recentBookings as $booking)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card h-100">
                                @if($booking->package && $booking->package->image)
                                <img src="{{ asset('images/default.jpg') }}" 
                                     data-image="{{ $booking->package->image }}"
                                     class="card-img-top" 
                                     alt="Package Image" 
                                     style="height: 150px; object-fit: cover;">
                                @endif
                                <div class="card-body">
                                    <h6 class="card-title">{{ $booking->package->package_name ?? 'Package Name' }}</h6>
                                    <p class="card-text small">
                                        <strong>Invoice:</strong> {{ $booking->invoice_number }}<br>
                                        <strong>Status:</strong> 
                                        <span class="badge 
                                            @if($booking->status == 'paid') bg-success
                                            @elseif($booking->status == 'waiting') bg-warning
                                            @elseif($booking->status == 'rejected') bg-danger
                                            @else bg-secondary
                                            @endif
                                        ">{{ ucfirst($booking->status) }}</span><br>
                                        <strong>Total:</strong> ${{ number_format($booking->total, 2) }}
                                    </p>
                                    <a href="{{ route('invoice.detail', $booking->id) }}" class="btn btn-sm btn-primary">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                        <h5>No bookings yet</h5>
                        <p class="text-muted">Start by browsing our amazing artist packages!</p>
                        <a href="{{ route('packages.browse') }}" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>
                            Browse Packages
                        </a>
                    </div>
                    @endif
                </div>

                <!-- Bookings Tab -->
                <div class="tab-pane fade {{ isset($activeTab) && $activeTab == 'bookings' ? 'show active' : '' }}" 
                     id="bookings" 
                     role="tabpanel" 
                     aria-labelledby="bookings-tab">
                    
                    @if(isset($invoices) && $invoices->count() > 0)
                    <div class="row">
                        @foreach($invoices as $invoice)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100">
                                @if($invoice->package && $invoice->package->image)
                                <img src="{{ asset('images/default.jpg') }}" 
                                     data-image="{{ $invoice->package->image }}"
                                     class="card-img-top" 
                                     alt="Package Image" 
                                     style="height: 200px; object-fit: cover;">
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">{{ $invoice->package->package_name ?? 'Package Name' }}</h5>
                                    <p class="card-text">
                                        @if($invoice->package && $invoice->package->user)
                                        <strong>Artist:</strong> {{ $invoice->package->user->name }}<br>
                                        @endif
                                        <strong>Invoice #:</strong> {{ $invoice->invoice_number }}<br>
                                        <strong>Status:</strong> 
                                        <span class="badge 
                                            @if($invoice->status == 'paid') bg-success
                                            @elseif($invoice->status == 'waiting') bg-warning
                                            @elseif($invoice->status == 'rejected') bg-danger
                                            @else bg-secondary
                                            @endif
                                        ">{{ ucfirst($invoice->status) }}</span><br>
                                        <strong>Total:</strong> ${{ number_format($invoice->total, 2) }}<br>
                                        @if($invoice->email)
                                            <strong>Booking Status:</strong> 
                                            <span class="badge
                                                @if($invoice->email->status == 'approved') bg-success
                                                @elseif($invoice->email->status == 'waiting') bg-warning
                                                @elseif($invoice->email->status == 'rejected') bg-danger
                                                @else bg-secondary
                                                @endif
                                            ">{{ ucfirst($invoice->email->status) }}</span>
                                        @endif
                                    </p>
                                    <div class="btn-group w-100" role="group">
                                        <a href="{{ route('booking.confirmation', $invoice->id) }}" class="btn btn-outline-primary btn-sm">
                                            View Details
                                        </a>
                                        @if($invoice->email && $invoice->email->status == 'approved' && $invoice->status == 'unpaid')
                                            <a href="{{ route('booking.payment', $invoice->id) }}" class="btn btn-success btn-sm">
                                                Pay Now
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-footer text-muted small">
                                    Booked on {{ $invoice->created_at->format('M d, Y') }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    @if(method_exists($invoices, 'links'))
                        <div class="d-flex justify-content-center">
                            {{ $invoices->links() }}
                        </div>
                    @endif
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                        <h5>No bookings found</h5>
                        <p class="text-muted">You haven't made any bookings yet.</p>
                        <a href="{{ route('packages.browse') }}" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>
                            Browse Packages
                        </a>
                    </div>
                    @endif
                </div>

                <!-- Invoices Tab -->
                <div class="tab-pane fade {{ isset($activeTab) && $activeTab == 'invoices' ? 'show active' : '' }}" 
                     id="invoices" 
                     role="tabpanel" 
                     aria-labelledby="invoices-tab">
                    
                    @if(isset($invoices) && $invoices->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Package</th>
                                    <th>Artist</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoices as $invoice)
                                <tr>
                                    <td>
                                        <strong>{{ $invoice->invoice_number }}</strong>
                                    </td>
                                    <td>{{ $invoice->package->package_name ?? 'N/A' }}</td>
                                    <td>{{ $invoice->package->user->name ?? 'N/A' }}</td>
                                    <td>${{ number_format($invoice->total, 2) }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($invoice->status == 'paid') bg-success
                                            @elseif($invoice->status == 'waiting') bg-warning
                                            @elseif($invoice->status == 'unpaid') bg-info
                                            @elseif($invoice->status == 'rejected') bg-danger
                                            @else bg-secondary
                                            @endif
                                        ">{{ ucfirst($invoice->status) }}</span>
                                    </td>
                                    <td>{{ $invoice->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('invoice.detail', $invoice->id) }}" 
                                               class="btn btn-outline-primary" 
                                               title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($invoice->status == 'unpaid')
                                                <a href="{{ route('booking.payment', $invoice->id) }}" 
                                                   class="btn btn-outline-success" 
                                                   title="Pay Now">
                                                    <i class="fas fa-credit-card"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if(method_exists($invoices, 'links'))
                        <div class="d-flex justify-content-center">
                            {{ $invoices->links() }}
                        </div>
                    @endif
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                        <h5>No invoices found</h5>
                        <p class="text-muted">You don't have any invoices yet.</p>
                        <a href="{{ route('packages.browse') }}" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>
                            Browse Packages
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .card-header-tabs .nav-link {
        border-bottom: 2px solid transparent;
    }
    
    .card-header-tabs .nav-link.active {
        border-bottom-color: #007bff;
        background: none;
        border-top: none;
        border-left: none;
        border-right: none;
    }
    
    .opacity-75 {
        opacity: 0.75;
    }
    
    .table th {
        border-top: none;
        font-weight: 600;
        color: #495057;
    }
    
    .btn-group-sm > .btn, .btn-sm {
        font-size: 0.875rem;
    }
</style>
@endpush

@include('partials.image-url-handler')
@endsection
