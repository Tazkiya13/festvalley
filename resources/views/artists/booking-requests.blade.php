@extends('admin.home')
@section('title', 'Booking Requests - Artist Dashboard')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h3 mb-0">
                        <i class="fas fa-calendar-check me-2 text-primary"></i>
                        Booking Requests
                    </h2>
                    <p class="text-muted mb-0">Review and manage booking requests for your packages</p>
                </div>
                <div>
                    <a href="{{ route('artists.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        Back to Packages
                    </a>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-white bg-warning">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $invoices->where('booking_status', 'pending')->count() + $invoices->whereNull('booking_status')->count() }}</h4>
                                    <p class="mb-0">Pending Requests</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-clock fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $invoices->where('booking_status', 'approved')->count() }}</h4>
                                    <p class="mb-0">Approved</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-check-circle fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-danger">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $invoices->where('booking_status', 'rejected')->count() }}</h4>
                                    <p class="mb-0">Rejected</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-times-circle fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-info">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $invoices->total() }}</h4>
                                    <p class="mb-0">Total Requests</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-list fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Requests Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">All Booking Requests</h5>
                </div>
                <div class="card-body">
                    @if($invoices->count() > 0)
                    <!-- Desktop Table View -->
                    <div class="table-responsive d-none d-lg-block">
                        <table class="table table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="15%">Customer Info</th>
                                    <th width="15%">Package</th>
                                    <th width="20%">Event Details</th>
                                    <th width="10%">Amount</th>
                                    <th width="10%">Status</th>
                                    <th width="10%">Date</th>
                                    <th width="15%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoices as $invoice)
                                    <tr>
                                        <td>{{ (($invoices->currentPage() - 1) * $invoices->perPage() + $loop->index + 1) }}</td>
                                        <td>
                                            <div class="customer-info">
                                                <strong>{{ $invoice->customer_name ?? $invoice->user->name ?? 'N/A' }}</strong><br>
                                                <small class="text-muted">
                                                    <i class="fas fa-envelope"></i> {{ $invoice->customer_email ?? $invoice->user->email ?? 'N/A' }}<br>
                                                    @if($invoice->customer_phone)
                                                        <i class="fas fa-phone"></i> {{ $invoice->customer_phone }}
                                                    @endif
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="package-info">
                                                <strong>{{ $invoice->package->package_name ?? 'N/A' }}</strong><br>
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar"></i> {{ $invoice->availableDate ? \Carbon\Carbon::parse($invoice->availableDate->tanggal)->translatedFormat('d F Y') : 'N/A' }}
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="event-info">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        @if($invoice->event_type)
                                                            <div><strong>{{ $invoice->event_type }}</strong></div>
                                                        @endif
                                                        @if($invoice->event_date)
                                                            <small><i class="fas fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($invoice->event_date)->translatedFormat('d F Y') }}</small><br>
                                                        @endif
                                                        @if($invoice->event_location)
                                                            <small><i class="fas fa-map-marker-alt"></i> {{ Str::limit($invoice->event_location, 30) }}</small>
                                                        @endif
                                                    </div>
                                                    @if($invoice->event_description || $invoice->special_requirements)
                                                        <button class="btn btn-sm btn-outline-primary expand-btn" 
                                                                onclick="toggleEventDetails({{ $invoice->id }})"
                                                                title="View full event details">
                                                            <i class="fas fa-chevron-down"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <strong>${{ number_format($invoice->total ?? 0, 2) }}</strong>
                                        </td>
                                        <td>
                                            @if($invoice->booking_status == 'pending' || $invoice->booking_status === null)
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @elseif($invoice->booking_status == 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($invoice->booking_status == 'rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small>{{ $invoice->created_at->format('M j, Y') }}<br>{{ $invoice->created_at->format('g:i A') }}</small>
                                        </td>
                                        <td>
                                            @if($invoice->booking_status == 'pending' || $invoice->booking_status === null)
                                                <div class="btn-group" role="group">
                                                    <form action="{{ route('booking.approve', $invoice->id) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to approve this booking?')">
                                                            <i class="fas fa-check"></i> Approve
                                                        </button>
                                                    </form>
                                                    <button type="button" class="btn btn-danger btn-sm reject-btn" data-invoice-id="{{ $invoice->id }}">
                                                        <i class="fas fa-times"></i> Reject
                                                    </button>
                                                </div>
                                            @elseif($invoice->booking_status == 'approved')
                                                <span class="text-success"><i class="fas fa-check-circle"></i> Approved</span>
                                            @elseif($invoice->booking_status == 'rejected')
                                                <span class="text-danger"><i class="fas fa-times-circle"></i> Rejected</span>
                                                @if($invoice->rejection_reason)
                                                    <br><small class="text-muted">{{ Str::limit($invoice->rejection_reason, 30) }}</small>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                    <!-- Expandable Detail Row -->
                                    @if($invoice->event_description || $invoice->special_requirements)
                                        <tr class="event-detail-row d-none" id="detail-{{ $invoice->id }}">
                                            <td colspan="8">
                                                <div class="event-details-expanded bg-light p-3 rounded">
                                                    @if($invoice->event_description)
                                                        <div class="mb-3">
                                                            <strong class="text-primary">Event Description:</strong>
                                                            <p class="mb-0 mt-1">{{ $invoice->event_description }}</p>
                                                        </div>
                                                    @endif
                                                    @if($invoice->special_requirements)
                                                        <div class="mb-0">
                                                            <strong class="text-warning">Special Requirements:</strong>
                                                            <p class="mb-0 mt-1">{{ $invoice->special_requirements }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="d-block d-lg-none">
                        @foreach($invoices as $invoice)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="card-title mb-0">{{ $invoice->customer_name ?? $invoice->user->name ?? 'N/A' }}</h6>
                                        @if($invoice->booking_status == 'pending' || $invoice->booking_status === null)
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif($invoice->booking_status == 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($invoice->booking_status == 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </div>
                                    
                                    <p class="card-text">
                                        <strong>Package:</strong> {{ $invoice->package->package_name ?? 'N/A' }}<br>
                                        <strong>Event:</strong> {{ $invoice->event_type ?? 'N/A' }}<br>
                                        @if($invoice->event_date)
                                            <strong>Date:</strong> {{ \Carbon\Carbon::parse($invoice->event_date)->translatedFormat('d F Y') }}<br>
                                        @endif
                                        @if($invoice->event_location)
                                            <strong>Location:</strong> {{ Str::limit($invoice->event_location, 50) }}<br>
                                        @endif
                                        <strong>Amount:</strong> ${{ number_format($invoice->total ?? 0, 2) }}<br>
                                        <strong>Requested:</strong> {{ $invoice->created_at->format('M j, Y g:i A') }}
                                    </p>
                                    
                                    @if($invoice->booking_status == 'pending' || $invoice->booking_status === null)
                                        <div class="d-flex gap-2">
                                            <form action="{{ route('booking.approve', $invoice->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to approve this booking?')">
                                                    <i class="fas fa-check"></i> Approve
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-danger btn-sm reject-btn" data-invoice-id="{{ $invoice->id }}">
                                                <i class="fas fa-times"></i> Reject
                                            </button>
                                        </div>
                                    @elseif($invoice->booking_status == 'rejected' && $invoice->rejection_reason)
                                        <div class="alert alert-danger alert-sm">
                                            <strong>Rejection Reason:</strong> {{ $invoice->rejection_reason }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $invoices->links('pagination::bootstrap-4') }}
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h5>No booking requests yet</h5>
                        <p class="text-muted">Booking requests will appear here when customers book your packages.</p>
                        <a href="{{ route('artists.index') }}" class="btn btn-primary">
                            <i class="fas fa-box me-1"></i>
                            Manage Packages
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// Simple modal implementation without Bootstrap dependency
document.addEventListener('DOMContentLoaded', function() {
    console.log('Setting up simple modal system');
    
    // Add styles directly to head to ensure they override everything
    const style = document.createElement('style');
    style.textContent = `
        .simple-modal {
            display: none;
            position: fixed;
            z-index: 10000001;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
        }
        .simple-modal.show {
            display: flex !important;
            align-items: center;
            justify-content: center;
        }
        .simple-modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 0;
            border: 1px solid #888;
            border-radius: 0.375rem;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            animation: modalSlideIn 0.3s ease-out;
        }
        @keyframes modalSlideIn {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .simple-modal-header {
            padding: 1rem 1.5rem;
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            border-radius: 0.375rem 0.375rem 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .simple-modal-body {
            padding: 1.5rem;
        }
        .simple-modal-footer {
            padding: 1rem 1.5rem;
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
            border-radius: 0 0 0.375rem 0.375rem;
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
        }
        .simple-close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            border: none;
            background: none;
        }
        .simple-close:hover,
        .simple-close:focus {
            color: black;
            text-decoration: none;
        }
    `;
    document.head.appendChild(style);
    
    // Handle reject button clicks
    document.querySelectorAll('.reject-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const invoiceId = this.getAttribute('data-invoice-id');
            console.log('Opening reject modal for invoice:', invoiceId);
            
            showRejectModal(invoiceId);
        });
    });
    
    function showRejectModal(invoiceId) {
        // Remove any existing simple modals
        document.querySelectorAll('.simple-modal').forEach(modal => modal.remove());
        
        // Find the invoice data from the table row
        const row = document.querySelector(`[data-invoice-id="${invoiceId}"]`).closest('tr');
        const customerName = row.cells[0].textContent.trim();
        const packageName = row.cells[1].textContent.trim();
        const eventType = row.cells[2].textContent.trim();
        
        // Create modal HTML
        const modalHtml = `
            <div class="simple-modal" id="simpleRejectModal${invoiceId}">
                <div class="simple-modal-content">
                    <div class="simple-modal-header">
                        <h5>Reject Booking Request</h5>
                        <button class="simple-close" onclick="closeSimpleModal('simpleRejectModal${invoiceId}')">&times;</button>
                    </div>
                    <div class="simple-modal-body">
                        <div style="margin-bottom: 1rem; padding: 1rem; background-color: #f8f9fa; border-radius: 0.375rem;">
                            <strong>Customer:</strong> ${customerName}<br>
                            <strong>Package:</strong> ${packageName}<br>
                            <strong>Event:</strong> ${eventType}
                        </div>
                        
                        <form action="/booking/reject/${invoiceId}" method="POST" id="rejectForm${invoiceId}">
                            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                            <input type="hidden" name="_method" value="POST">
                            
                            <div style="margin-bottom: 1rem;">
                                <label for="rejection_reason" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Reason for rejection:</label>
                                <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" required 
                                    placeholder="Please provide a reason for rejecting this booking..." style="width: 100%; padding: 0.5rem; border: 1px solid #ced4da; border-radius: 0.375rem; resize: vertical;"></textarea>
                                <small style="color: #6c757d; font-size: 0.875em;">This will help the customer understand why their request was rejected.</small>
                            </div>
                        </form>
                    </div>
                    <div class="simple-modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="closeSimpleModal('simpleRejectModal${invoiceId}')" style="padding: 0.375rem 0.75rem; margin-right: 0.5rem; border: 1px solid #6c757d; background-color: #6c757d; color: white; border-radius: 0.375rem; cursor: pointer;">Cancel</button>
                        <button type="submit" form="rejectForm${invoiceId}" class="btn btn-danger" style="padding: 0.375rem 0.75rem; border: 1px solid #dc3545; background-color: #dc3545; color: white; border-radius: 0.375rem; cursor: pointer;">Reject Booking</button>
                    </div>
                </div>
            </div>
        `;
        
        // Add modal to body
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        
        // Show modal
        const modal = document.getElementById(`simpleRejectModal${invoiceId}`);
        setTimeout(() => {
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
            
            // Focus on the textarea
            const textarea = modal.querySelector('textarea');
            if (textarea) {
                textarea.focus();
            }
        }, 10);
        
        // Handle click outside to close
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeSimpleModal(`simpleRejectModal${invoiceId}`);
            }
        });
        
        // Handle escape key
        const escapeHandler = function(e) {
            if (e.key === 'Escape') {
                closeSimpleModal(`simpleRejectModal${invoiceId}`);
                document.removeEventListener('keydown', escapeHandler);
            }
        };
        document.addEventListener('keydown', escapeHandler);
        
        console.log('Simple modal created and shown');
    }
    
    // Global function to close modal
    window.closeSimpleModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('show');
            setTimeout(() => {
                modal.remove();
                document.body.style.overflow = '';
            }, 300);
        }
    };

    // Toggle event details function
    window.toggleEventDetails = function(invoiceId) {
        const detailRow = document.getElementById(`detail-${invoiceId}`);
        const expandBtn = document.querySelector(`button[onclick="toggleEventDetails(${invoiceId})"]`);
        const icon = expandBtn.querySelector('i');
        
        if (detailRow.classList.contains('d-none')) {
            // Show details
            detailRow.classList.remove('d-none');
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-up');
            expandBtn.title = 'Hide event details';
        } else {
            // Hide details
            detailRow.classList.add('d-none');
            icon.classList.remove('fa-chevron-up');
            icon.classList.add('fa-chevron-down');
            expandBtn.title = 'View full event details';
        }
    };
});
</script>

<style>
/* Expandable Row Styling */
.expand-btn {
    border: 1px solid #667eea !important;
    color: #667eea !important;
    transition: all 0.3s ease;
    min-width: 32px;
    height: 32px;
    display: flex !important;
    align-items: center;
    justify-content: center;
}

.expand-btn:hover {
    background-color: #667eea !important;
    color: white !important;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
}

.event-detail-row {
    transition: all 0.3s ease;
}

.event-details-expanded {
    border-left: 4px solid #667eea;
    margin: 0.5rem 0;
    animation: slideDown 0.3s ease;
}

.event-details-expanded strong {
    font-size: 14px;
    font-weight: 600;
}

.event-details-expanded p {
    color: #374151;
    line-height: 1.5;
    margin-top: 0.5rem;
    white-space: pre-wrap;
    word-wrap: break-word;
}

@keyframes slideDown {
    from {
        opacity: 0;
        max-height: 0;
    }
    to {
        opacity: 1;
        max-height: 200px;
    }
}

/* Icon rotation animation */
.expand-btn i {
    transition: transform 0.3s ease;
}
</style>
@endsection
