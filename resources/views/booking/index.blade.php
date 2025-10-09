@extends('admin.home')
@section('title', 'Customer Booking List')
@section('content')
    <div>
        <div class="main-content" style="padding: 20px;">
            <!-- Desktop Table View -->
            <div class="desktop-table-container">
                <table class="table table-striped table-hover table-bordered desktop-table">
                    <thead class="thead-dark">
                        <tr>
                            <th>No</th>
                            <th>Customer Info</th>
                            <th>Package Details</th>
                            <th>Event Details</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $invoice)
                            <tr>
                                <td>{{(($invoices->currentPage() - 1) * $invoices->perPage() + $loop->index + 1) }}</td>
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
                                            <i class="fas fa-user"></i> {{ $invoice->package->user->name ?? 'N/A' }}<br>
                                            <i class="fas fa-calendar"></i> {{ $invoice->availableDate ? \Carbon\Carbon::parse($invoice->availableDate->tanggal)->translatedFormat('d F Y') : 'N/A' }}
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <div class="event-info">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="event-info">
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
                                    </div>
                                </td>
                                <td>
                                    @if($invoice->booking_status == 'pending')
                                        <span class="bg-warning text-dark" style="padding: 5px; border-radius: 5px;">pending</span>
                                    @elseif($invoice->booking_status == 'approved')
                                        <span class="bg-success text-dark" style="padding: 5px; border-radius: 5px;">approved</span>
                                    @elseif($invoice->booking_status == 'rejected')
                                        <span class="bg-danger text-light" style="padding: 5px; border-radius: 5px;">rejected</span>
                                    @else
                                        <span class="bg-warning text-dark" style="padding: 5px; border-radius: 5px;">pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if($invoice->booking_status == 'pending' || $invoice->booking_status === null)
                                        <div class="action-buttons">
                                            <form action="{{ route('booking.approve', $invoice->id) }}" method="POST" style="display: inline-block;">
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
                                            <br><small class="text-muted">{{ $invoice->rejection_reason }}</small>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            <!-- Expandable Detail Row -->
                            @if($invoice->event_description || $invoice->special_requirements)
                                <tr class="event-detail-row d-none" id="detail-{{ $invoice->id }}">
                                    <td colspan="6">
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

                        @empty
                            <tr>
                                <td colspan="6" class="text-center empty-state">
                                    <i class="material-icons">book_online</i>
                                    <h5>No bookings found</h5>
                                    <p>Booking requests will appear here</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="mobile-cards">
                @forelse($invoices as $invoice)
                    <div class="mobile-card">
                        <div class="mobile-card-header">
                            <span>Booking #{{(($invoices->currentPage() - 1) * $invoices->perPage() + $loop->index + 1) }}</span>
                            @if($invoice->booking_status == 'pending' || $invoice->booking_status === null)
                                <span class="bg-warning text-dark" style="padding: 5px; border-radius: 5px;">pending</span>
                            @elseif($invoice->booking_status == 'approved')
                                <span class="bg-success text-dark" style="padding: 5px; border-radius: 5px;">approved</span>
                            @elseif($invoice->booking_status == 'rejected')
                                <span class="bg-danger text-light" style="padding: 5px; border-radius: 5px;">rejected</span>
                            @endif
                        </div>

                        <div class="mobile-card-body">
                            <!-- Customer Info -->
                            <div class="mobile-card-row">
                                <div class="mobile-card-label">Customer:</div>
                                <div class="mobile-card-value">
                                    <strong>{{ $invoice->customer_name ?? $invoice->user->name ?? 'N/A' }}</strong><br>
                                    <small class="text-muted">{{ $invoice->customer_email ?? $invoice->user->email ?? 'N/A' }}</small><br>
                                    @if($invoice->customer_phone)
                                        <small class="text-muted">{{ $invoice->customer_phone }}</small>
                                    @endif
                                </div>
                            </div>

                            <!-- Artist Name -->
                            <div class="mobile-card-row">
                                <div class="mobile-card-label">Artist Name:</div>
                                <div class="mobile-card-value">
                                    @if ($invoice->package && $invoice->package->user && $invoice->package->user->name)
                                        <strong>{{ $invoice->package->user->name }}</strong>
                                    @else
                                        <em>No artist</em>
                                    @endif
                                </div>
                            </div>

                            <!-- Package Name -->
                            <div class="mobile-card-row">
                                <div class="mobile-card-label">Package Name:</div>
                                <div class="mobile-card-value">
                                    @if ($invoice->package && $invoice->package->package_name)
                                        {{ $invoice->package->package_name }}
                                    @else
                                        <em>No package</em>
                                    @endif
                                </div>
                            </div>

                            <!-- Available Date -->
                            <div class="mobile-card-row">
                                <div class="mobile-card-label">Date:</div>
                                <div class="mobile-card-value">
                                    {{ $invoice->availableDate ? \Carbon\Carbon::parse($invoice->availableDate->tanggal)->translatedFormat('d F Y') : 'No available date' }}
                                </div>
                            </div>

                            <!-- Event Details -->
                            @if($invoice->event_type || $invoice->event_date || $invoice->event_location)
                            <div class="mobile-card-row">
                                <div class="mobile-card-label">Event Details:</div>
                                <div class="mobile-card-value">
                                    @if($invoice->event_type)
                                        <strong>{{ $invoice->event_type }}</strong><br>
                                    @endif
                                    @if($invoice->event_date)
                                        <small><i class="fas fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($invoice->event_date)->translatedFormat('d F Y') }}</small><br>
                                    @endif
                                    @if($invoice->event_location)
                                        <small><i class="fas fa-map-marker-alt"></i> {{ $invoice->event_location }}</small><br>
                                    @endif
                                </div>
                            </div>
                            @endif

                            <!-- Event Description -->
                            @if($invoice->event_description)
                            <div class="mobile-card-row">
                                <div class="mobile-card-label">Description:</div>
                                <div class="mobile-card-value">
                                    <textarea class="form-control form-control-sm" 
                                              rows="2" 
                                              readonly 
                                              style="resize: none; font-size: 12px; background: #f8f9fa;">{{ $invoice->event_description }}</textarea>
                                </div>
                            </div>
                            @endif

                            <!-- Special Requirements -->
                            @if($invoice->special_requirements)
                            <div class="mobile-card-row">
                                <div class="mobile-card-label">Special Requirements:</div>
                                <div class="mobile-card-value">
                                    <textarea class="form-control form-control-sm" 
                                              rows="2" 
                                              readonly 
                                              style="resize: none; font-size: 12px; background: #fff3cd;">{{ $invoice->special_requirements }}</textarea>
                                </div>
                            </div>
                            @endif

                            <!-- Actions -->
                            @if($invoice->booking_status == 'pending' || $invoice->booking_status === null)
                                <div class="mobile-card-row">
                                    <div class="mobile-card-label">Actions:</div>
                                    <div class="mobile-card-value">
                                        <form action="{{ route('booking.approve', $invoice->id) }}" method="POST" style="display: inline-block; margin-right: 10px;">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to approve this booking?')">
                                                <i class="fas fa-check"></i> Approve
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-danger btn-sm reject-btn" data-invoice-id="{{ $invoice->id }}">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </div>
                                </div>
                            @elseif($invoice->booking_status == 'approved')
                                <div class="mobile-card-row">
                                    <div class="mobile-card-label">Status:</div>
                                    <div class="mobile-card-value">
                                        <span class="text-success"><i class="fas fa-check-circle"></i> Approved</span>
                                    </div>
                                </div>
                            @elseif($invoice->booking_status == 'rejected')
                                <div class="mobile-card-row">
                                    <div class="mobile-card-label">Status:</div>
                                    <div class="mobile-card-value">
                                        <span class="text-danger"><i class="fas fa-times-circle"></i> Rejected</span>
                                        @if($invoice->rejection_reason)
                                            <br><small class="text-muted">{{ $invoice->rejection_reason }}</small>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="material-icons">book_online</i>
                        <h5>No bookings found</h5>
                        <p>Booking requests will appear here</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="paginate">
        {{ $invoices->links('pagination::bootstrap-4') }}
    </div>

@endsection

@section('scripts')
<script>
// Simple modal implementation without Bootstrap dependency
document.addEventListener('DOMContentLoaded', function() {
    console.log('Setting up simple modal system for admin booking management');
    
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
        let customerName, packageName, eventType;
        
        if (row) {
            // Desktop view
            customerName = row.cells[1].querySelector('.customer-info strong')?.textContent.trim() || 'N/A';
            packageName = row.cells[2].querySelector('.package-info strong')?.textContent.trim() || 'N/A';
            eventType = row.cells[3].querySelector('.event-info')?.textContent.trim() || 'N/A';
        } else {
            // Mobile view - find the card
            const card = document.querySelector(`[data-invoice-id="${invoiceId}"]`).closest('.mobile-card');
            customerName = card.querySelector('.customer-info strong')?.textContent.trim() || 'N/A';
            packageName = card.querySelector('.package-info strong')?.textContent.trim() || 'N/A';
            eventType = card.querySelector('.event-info')?.textContent.trim() || 'N/A';
        }
        
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
/* Admin Expandable Row Styling */
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

/* Responsive adjustments for mobile */
@media (max-width: 768px) {
    .expand-btn {
        min-width: 28px;
        height: 28px;
    }
    
    .event-details-expanded {
        padding: 0.75rem !important;
    }
    
    .event-details-expanded strong {
        font-size: 13px;
    }
    
    .event-details-expanded p {
        font-size: 13px;
    }
}
</style>
@endsection
