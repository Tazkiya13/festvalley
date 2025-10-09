@extends('layouts.app')

@section('title', 'Booking Confirmation')

@section('content')
<div class="confirmation-container">
    <div class="container">
        <div class="confirmation-wrapper">
            <!-- Success Header -->
            <div class="confirmation-header">
                <div class="success-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h1>Booking Request Submitted!</h1>
                <p>Thank you for your booking request. We'll get back to you soon.</p>
            </div>

            <!-- Booking Details -->
            <div class="confirmation-content">
                <div class="row">
                    <div class="col-lg-8">
                        <!-- Invoice Details -->
                        <div class="confirmation-card">
                            <div class="card-header">
                                <h3>Booking Details</h3>
                                <div class="status-badge status-{{ strtolower($invoice->status) }}">
                                    {{ ucfirst($invoice->status) }}
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="booking-info">
                                    <div class="info-row">
                                        <span class="label">Invoice Number:</span>
                                        <span class="value">{{ $invoice->invoice_number }}</span>
                                    </div>
                                    
                                    <div class="info-row">
                                        <span class="label">Package:</span>
                                        <span class="value">{{ $invoice->package->package_name }}</span>
                                    </div>
                                    
                                    <div class="info-row">
                                        <span class="label">Artist:</span>
                                        <span class="value">{{ $invoice->package->user->name }}</span>
                                    </div>
                                    
                                    <div class="info-row">
                                        <span class="label">Amount:</span>
                                        <span class="value price">€ {{ number_format($invoice->total, 0, ',', '.') }}</span>
                                    </div>
                                    
                                    @if($invoice->availableDate)
                                    <div class="info-row">
                                        <span class="label">Booking Date:</span>
                                        <span class="value">{{ Carbon\Carbon::parse($invoice->availableDate->tanggal)->format('l, F j, Y') }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Customer Information -->
                        <div class="confirmation-card">
                            <div class="card-header">
                                <h3>Contact Information</h3>
                            </div>

                            <div class="card-body">
                                <div class="booking-info">
                                    <div class="info-row">
                                        <span class="label">Name:</span>
                                        <span class="value">{{ $invoice->user->name }}</span>
                                    </div>
                                    
                                    <div class="info-row">
                                        <span class="label">Email:</span>
                                        <span class="value">{{ $invoice->user->email }}</span>
                                    </div>
                                    
                                    @if($invoice->customer_phone)
                                    <div class="info-row">
                                        <span class="label">Phone:</span>
                                        <span class="value">{{ $invoice->customer_phone }}</span>
                                    </div>
                                    @endif

                                    @if($invoice->customer_company)
                                    <div class="info-row">
                                        <span class="label">Company/Organization:</span>
                                        <span class="value">{{ $invoice->customer_company }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Event Details -->
                        @if($invoice->event_type || $invoice->event_date || $invoice->event_location || $invoice->event_description || $invoice->special_requirements || $invoice->event_time || $invoice->venue_address || $invoice->guest_count || $invoice->event_duration || $invoice->budget_range || $invoice->technical_requirements)
                        <div class="confirmation-card">
                            <div class="card-header">
                                <h3>Event Details</h3>
                            </div>

                            <div class="card-body">
                                <div class="booking-info">
                                    @if($invoice->event_type)
                                    <div class="info-row">
                                        <span class="label">Event Type:</span>
                                        <span class="value">{{ $invoice->event_type }}</span>
                                    </div>
                                    @endif
                                    
                                    @if($invoice->event_date)
                                    <div class="info-row">
                                        <span class="label">Event Date:</span>
                                        <span class="value">{{ \Carbon\Carbon::parse($invoice->event_date)->format('l, F j, Y') }}</span>
                                    </div>
                                    @endif

                                    @if($invoice->event_time)
                                    <div class="info-row">
                                        <span class="label">Event Time:</span>
                                        <span class="value">{{ $invoice->event_time }}</span>
                                    </div>
                                    @endif

                                    @if($invoice->event_duration)
                                    <div class="info-row">
                                        <span class="label">Event Duration:</span>
                                        <span class="value">{{ $invoice->event_duration }}</span>
                                    </div>
                                    @endif
                                    
                                    @if($invoice->event_location)
                                    <div class="info-row">
                                        <span class="label">Location:</span>
                                        <span class="value">{{ $invoice->event_location }}</span>
                                    </div>
                                    @endif

                                    @if($invoice->venue_address)
                                    <div class="info-row">
                                        <span class="label">Full Venue Address:</span>
                                        <span class="value">{{ $invoice->venue_address }}</span>
                                    </div>
                                    @endif

                                    @if($invoice->guest_count)
                                    <div class="info-row">
                                        <span class="label">Expected Guests:</span>
                                        <span class="value">{{ $invoice->guest_count }} guests</span>
                                    </div>
                                    @endif

                                    @if($invoice->budget_range)
                                    <div class="info-row">
                                        <span class="label">Budget Range:</span>
                                        <span class="value">{{ $invoice->budget_range }}</span>
                                    </div>
                                    @endif
                                    
                                    @if($invoice->event_description)
                                    <div class="info-row">
                                        <span class="label">Event Description:</span>
                                        <span class="value">{{ $invoice->event_description }}</span>
                                    </div>
                                    @endif
                                    
                                    @if($invoice->special_requirements)
                                    <div class="info-row">
                                        <span class="label">Special Requirements:</span>
                                        <span class="value text-info">{{ $invoice->special_requirements }}</span>
                                    </div>
                                    @endif

                                    @if($invoice->technical_requirements)
                                    <div class="info-row">
                                        <span class="label">Technical Requirements:</span>
                                        <span class="value text-warning">{{ $invoice->technical_requirements }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="col-lg-4">
                        <!-- Status Timeline -->
                        <div class="confirmation-card">
                            <div class="card-header">
                                <h3>Booking Status</h3>
                            </div>

                            <div class="card-body">
                                <div class="status-timeline">
                                    <div class="timeline-item completed">
                                        <div class="timeline-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h4>Request Submitted</h4>
                                            <p>Your booking request has been submitted</p>
                                            <small>{{ $invoice->created_at->format('M j, Y g:i A') }}</small>
                                        </div>
                                    </div>

                                    <div class="timeline-item {{ $invoice->booking_status === 'approved' ? 'completed' : 'pending' }}">
                                        <div class="timeline-icon">
                                            @if($invoice->booking_status === 'approved')
                                                <i class="fas fa-check"></i>
                                            @elseif($invoice->booking_status === 'rejected')
                                                <i class="fas fa-times"></i>
                                            @else
                                                <i class="fas fa-clock"></i>
                                            @endif
                                        </div>
                                        <div class="timeline-content">
                                            <h4>Artist Review</h4>
                                            @if($invoice->booking_status === 'approved')
                                                <p>Booking approved by artist</p>
                                            @elseif($invoice->booking_status === 'rejected')
                                                <p>Booking rejected by artist</p>
                                                @if($invoice->rejection_reason)
                                                    <small class="text-muted">Reason: {{ $invoice->rejection_reason }}</small>
                                                @endif
                                            @else
                                                <p>Waiting for artist approval</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="timeline-item {{ $invoice->status === 'paid' ? 'completed' : 'pending' }}">
                                        <div class="timeline-icon">
                                            @if($invoice->status === 'paid')
                                                <i class="fas fa-check"></i>
                                            @else
                                                <i class="fas fa-clock"></i>
                                            @endif
                                        </div>
                                        <div class="timeline-content">
                                            <h4>Payment</h4>
                                            @if($invoice->status === 'paid')
                                                <p>Payment confirmed</p>
                                            @else
                                                <p>Awaiting payment</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="timeline-item pending">
                                        <div class="timeline-icon">
                                            <i class="fas fa-calendar"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h4>Event Day</h4>
                                            <p>Enjoy your event!</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Card -->
                        @if($invoice->booking_status === 'approved' && !in_array($invoice->status, ['paid', 'waiting']))
                        <div class="confirmation-card">
                            <div class="card-header">
                                <h3>Next Steps</h3>
                            </div>

                            <div class="card-body">
                                <p>Your booking has been approved! You can now proceed with payment.</p>
                                <a href="{{ route('booking.payment', $invoice->id) }}" class="btn btn-primary btn-block">
                                    <i class="fas fa-credit-card"></i>
                                    Proceed to Payment
                                </a>
                            </div>
                        </div>
                        @endif

                        @if($invoice->status === 'waiting')
                        <div class="confirmation-card">
                            <div class="card-header">
                                <h3>Payment Status</h3>
                            </div>

                            <div class="card-body">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    Your payment is being reviewed. We'll notify you once it's confirmed.
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="confirmation-actions">
                <div class="row">
                    <div class="col-md-6">
                        <a href="{{ route('home') }}" class="btn btn-outline-primary btn-block">
                            <i class="fas fa-arrow-left"></i>
                            Back to Browse
                        </a>
                    </div>
                    <div class="col-md-6">
                        @auth
                            @if(Auth::user()->hasRole('Admin'))
                                <a href="{{ route('admin.home') }}" class="btn btn-primary btn-block">
                                    <i class="fas fa-dashboard"></i>
                                    View Dashboard
                                </a>
                            @elseif(Auth::user()->hasRole('Artist'))
                                <a href="{{ route('artists.index') }}" class="btn btn-primary btn-block">
                                    <i class="fas fa-dashboard"></i>
                                    View Dashboard
                                </a>
                            @elseif(Auth::user()->hasRole('Customer'))
                                <a href="{{ route('customer.bookings') }}" class="btn btn-primary btn-block">
                                    <i class="fas fa-calendar"></i>
                                    View My Bookings
                                </a>
                            @else
                                <a href="{{ route('home') }}" class="btn btn-primary btn-block">
                                    <i class="fas fa-dashboard"></i>
                                    View Dashboard
                                </a>
                            @endif
                        @else
                            <a href="{{ route('home') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-sign-in-alt"></i>
                                Login to Track
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.confirmation-container {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 2rem 0;
}

.confirmation-wrapper {
    max-width: 1000px;
    margin: 0 auto;
}

.confirmation-header {
    text-align: center;
    margin-bottom: 3rem;
    color: white;
}

.success-icon {
    font-size: 4rem;
    color: #48bb78;
    margin-bottom: 1rem;
    background: white;
    width: 100px;
    height: 100px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.confirmation-header h1 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.confirmation-header p {
    font-size: 1.2rem;
    opacity: 0.9;
}

.confirmation-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
    overflow: hidden;
}

.card-header {
    background: #f8f9fa;
    padding: 1.5rem 2rem;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h3 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
    color: #2d3748;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-pending {
    background: #fed7d7;
    color: #c53030;
}

.status-approved {
    background: #c6f6d5;
    color: #2f855a;
}

.status-waiting {
    background: #fefcbf;
    color: #d69e2e;
}

.status-paid {
    background: #bee3f8;
    color: #2b6cb0;
}

.card-body {
    padding: 2rem;
}

.booking-info {
    display: grid;
    gap: 1rem;
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding-bottom: 1rem;
    border-bottom: 1px solid #f7fafc;
}

.info-row:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.info-row .label {
    font-weight: 600;
    color: #4a5568;
    min-width: 140px;
}

.info-row .value {
    color: #2d3748;
    text-align: right;
    flex: 1;
}

.info-row .value.price {
    font-size: 1.25rem;
    font-weight: 700;
    color: #667eea;
}

.status-timeline {
    position: relative;
}

.status-timeline::before {
    content: '';
    position: absolute;
    left: 20px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e2e8f0;
}

.timeline-item {
    position: relative;
    padding-left: 60px;
    margin-bottom: 2rem;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-icon {
    position: absolute;
    left: 0;
    top: 0;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    background: #e2e8f0;
    color: #a0aec0;
    border: 3px solid white;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.timeline-item.completed .timeline-icon {
    background: #48bb78;
    color: white;
}

.timeline-item.pending .timeline-icon {
    background: #fed7d7;
    color: #c53030;
}

.timeline-content h4 {
    margin: 0 0 0.5rem 0;
    font-size: 1rem;
    font-weight: 600;
    color: #2d3748;
}

.timeline-content p {
    margin: 0 0 0.25rem 0;
    color: #718096;
    font-size: 0.875rem;
}

.timeline-content small {
    color: #a0aec0;
    font-size: 0.75rem;
}

.alert {
    padding: 1rem;
    border-radius: 8px;
    border-left: 4px solid;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.alert-info {
    background: #ebf8ff;
    border-color: #3182ce;
    color: #2b6cb0;
}

.confirmation-actions {
    margin-top: 2rem;
}

.btn {
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    border: 2px solid;
    transition: all 0.3s ease;
}

.btn-block {
    width: 100%;
}

.btn-primary {
    background: #667eea;
    border-color: #667eea;
    color: white;
}

.btn-primary:hover {
    background: #5a67d8;
    border-color: #5a67d8;
    color: white;
    text-decoration: none;
}

.btn-outline-primary {
    background: transparent;
    border-color: #667eea;
    color: #667eea;
}

.btn-outline-primary:hover {
    background: #667eea;
    color: white;
    text-decoration: none;
}

@media (max-width: 768px) {
    .confirmation-wrapper {
        margin: 1rem;
    }
    
    .confirmation-header h1 {
        font-size: 2rem;
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    .info-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }
    
    .info-row .value {
        text-align: left;
    }
    
    .confirmation-actions .row > div {
        margin-bottom: 1rem;
    }
}
</style>
@endsection
