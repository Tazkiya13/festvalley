@extends('admin.home')
@section('title', 'My Packages - Artist Dashboard')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h3 mb-0">
                        <i class="fas fa-box me-2 text-primary"></i>
                        My Packages
                    </h2>
                    <p class="text-muted mb-0">Manage your performance packages and availability</p>
                </div>
                <div>
                    @can('create package artists')
                        <a href="{{ route('packages.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>
                            Create New Package
                        </a>
                    @endcan
                    <a href="{{ route('artists.booking-requests') }}" class="btn btn-warning">
                        <i class="fas fa-calendar-check me-1"></i>
                        Booking Requests
                    </a>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-white bg-primary">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $packages->total() }}</h4>
                                    <p class="mb-0">Total Packages</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-box fa-2x opacity-75"></i>
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
                                    <h4 class="mb-0">{{ $packages->sum(function($p) { return $p->availableDates->count(); }) }}</h4>
                                    <p class="mb-0">Available Dates</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-calendar fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-warning">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ \App\Models\Invoice::whereHas('package', function($q) { $q->where('user_id', auth()->id()); })->where(function($q) { $q->where('booking_status', 'pending')->orWhereNull('booking_status'); })->count() }}</h4>
                                    <p class="mb-0">Pending Bookings</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-clock fa-2x opacity-75"></i>
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
                                    <h4 class="mb-0">{{ \App\Models\Invoice::whereHas('package', function($q) { $q->where('user_id', auth()->id()); })->where('status', 'paid')->count() }}</h4>
                                    <p class="mb-0">Completed Bookings</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-check-circle fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Packages Grid -->
            @if($packages->count() > 0)
            <div class="row">
                @foreach ($packages as $package)
                <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                    <div class="card package-card h-100 shadow-sm">
                        <div class="card-header bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-music text-primary me-2"></i>
                                    {{ $package->package_name }}
                                </h5>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        @can('edit package artists')
                                        <li>
                                            <a class="dropdown-item" href="{{ route('artists.edit', $package->id) }}">
                                                <i class="fas fa-edit me-2"></i>Edit Package
                                            </a>
                                        </li>
                                        @endcan
                                        @can('delete package artists')
                                        <li>
                                            <form action="{{ route('packages.destroy', $package->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this package?')">
                                                    <i class="fas fa-trash me-2"></i>Delete Package
                                                </button>
                                            </form>
                                        </li>
                                        @endcan
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Package Details -->
                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-6">
                                        <small class="text-muted">Price</small>
                                        <p class="mb-0 fw-bold">€ {{ number_format($package->price ?? 0, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Duration</small>
                                        <p class="mb-0">{{ $package->duration ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Available Dates Section -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="text-muted fw-semibold">Available Dates</small>
                                    <span class="badge bg-primary">{{ $package->availableDates->count() }} dates</span>
                                </div>
                                <div class="available-dates-container">
                                    <input type="text"
                                        class="form-control flatpickr-artist"
                                        id="flatpickr-package-{{ $package->id }}"
                                        data-package-id="{{ $package->id }}"
                                        data-available-dates='@json($package->availableDates->pluck("date")->map(fn($d) => \Carbon\Carbon::parse($d)->format("Y-m-d")))'
                                        placeholder="Click to manage dates"
                                        autocomplete="off">
                                </div>
                            </div>

                            <!-- Quick Stats -->
                            <div class="row text-center">
                                <div class="col-4">
                                    <small class="text-muted d-block">Bookings</small>
                                    <span class="fw-bold text-success">{{ \App\Models\Invoice::where('package_id', $package->id)->where('status', 'paid')->count() }}</span>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">Pending</small>
                                    <span class="fw-bold text-warning">{{ \App\Models\Invoice::where('package_id', $package->id)->where('status', 'waiting')->count() }}</span>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">Revenue</small>
                                    <span class="fw-bold text-info">€ {{ number_format(\App\Models\Invoice::where('package_id', $package->id)->where('status', 'paid')->sum('total'), 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <div class="d-flex gap-2">
                                @can('edit package artists')
                                <a href="{{ route('artists.edit', $package->id) }}" class="btn btn-outline-primary btn-sm flex-fill">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </a>
                                @endcan
                                <button class="btn btn-outline-info btn-sm flex-fill" onclick="viewPackageDetails({{ $package->id }})" type="button">
                                    <i class="fas fa-eye me-1"></i>View Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <!-- Empty State -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-box fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">No Packages Yet</h4>
                            <p class="text-muted">Create your first performance package to start receiving bookings.</p>
                            @can('create package artists')
                                <a href="{{ route('packages.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i>
                                    Create Your First Package
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Pagination -->
            @if($packages->hasPages())
            <div class="d-flex justify-content-center">
                {{ $packages->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Package Details Modal -->
<div class="modal fade" id="packageDetailsModal" tabindex="-1" aria-labelledby="packageDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="packageDetailsModalLabel">Package Details</h5>
                <button type="button" class="btn-close" onclick="closePackageModal()" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="packageDetailsContent">
                    <!-- Content will be loaded via JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.package-card {
    transition: transform 0.2s ease-in-out;
    border: none;
    border-radius: 12px;
}

.package-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.available-dates-container input {
    border-radius: 8px;
    border: 2px solid #e9ecef;
    transition: border-color 0.2s;
}

.available-dates-container input:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.stats-card {
    border-radius: 12px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.btn {
    border-radius: 8px;
}

.card-header {
    border-radius: 12px 12px 0 0 !important;
}

/* Ensure modal displays properly */
#packageDetailsModal {
    z-index: 1055 !important;
}

#packageDetailsModal .modal-backdrop {
    z-index: 1050 !important;
}

#packageDetailsModal.show {
    display: block !important;
    opacity: 1 !important;
}

#packageDetailsModal.fade.show {
    opacity: 1 !important;
}

/* Force modal to show without animation conflicts */
#packageDetailsModal.modal-force-show {
    display: block !important;
    opacity: 1 !important;
    visibility: visible !important;
    transition: none !important;
    animation: none !important;
    /* grey transparent background */
    background-color: rgba(0, 0, 0, 0.5) !important;
}
</style>

<script>
function viewPackageDetails(packageId) {
    console.log('viewPackageDetails called with packageId:', packageId);
    
    try {
        // Check if jQuery is available
        if (typeof $ === 'undefined') {
            console.error('jQuery is not loaded');
            alert('Error: jQuery is required for this function');
            return;
        }
        
        // Check if Bootstrap is available
        if (typeof bootstrap === 'undefined') {
            console.error('Bootstrap is not loaded');
            alert('Error: Bootstrap is required for this function');
            return;
        }
        
        console.log('Bootstrap version:', bootstrap.Modal.VERSION || 'Unknown');
        console.log('jQuery version:', $.fn.jquery || 'Unknown');
        
        // Clear previous content using jQuery
        $('#packageDetailsContent').html(`
            <div class="text-center p-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Loading package details...</p>
            </div>
        `);
        
        // Get modal element
        const modalElement = document.getElementById('packageDetailsModal');
        if (!modalElement) {
            console.error('Modal element with ID "packageDetailsModal" not found');
            alert('Error: Modal element not found');
            return;
        }
        
        console.log('Modal element found:', modalElement);
        
        // Use Bootstrap 5 Modal API directly (more reliable)
        try {
            // Store current scroll position
            const scrollY = window.scrollY;
            
            // Remove all animation classes and force modal to show immediately
            modalElement.classList.remove('fade');
            modalElement.classList.add('modal-force-show', 'show');
            modalElement.style.display = 'block';
            modalElement.style.opacity = '1';
            modalElement.removeAttribute('aria-hidden');
            modalElement.setAttribute('aria-modal', 'true');
            
            // Handle body scroll - prevent scrolling but maintain position
            document.body.classList.add('modal-open');
            document.body.style.top = `-${scrollY}px`;
            document.body.style.position = 'fixed';
            document.body.style.width = '100%';
            
            // Store scroll position for restoration
            modalElement.setAttribute('data-scroll-y', scrollY);
            
            // Create manual backdrop
            let backdrop = document.querySelector('.modal-backdrop');
            if (!backdrop) {
                backdrop = document.createElement('div');
                backdrop.className = 'modal-backdrop fade show';
                backdrop.style.zIndex = '1050';
                document.body.appendChild(backdrop);
            }
            
            console.log('Modal forced to show immediately');
            
        } catch (bootstrapError) {
            console.error('Manual modal show failed:', bootstrapError);
            alert('Error: Could not open modal');
            return;
        }
        
        // Load content after a short delay
        setTimeout(() => {
            // Check if modal is actually visible
            const modalStyles = window.getComputedStyle(modalElement);
            console.log('Modal computed styles:', {
                display: modalStyles.display,
                visibility: modalStyles.visibility,
                opacity: modalStyles.opacity,
                zIndex: modalStyles.zIndex
            });
            
            // Check if modal backdrop exists
            const backdrop = document.querySelector('.modal-backdrop');
            console.log('Modal backdrop found:', backdrop);
            
            $('#packageDetailsContent').html(`
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-box text-primary me-2"></i>
                            <h6 class="mb-0">Package Information</h6>
                        </div>
                        <p class="text-muted">Package ID: <strong>${packageId}</strong></p>
                        <hr>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-success"><i class="fas fa-check-circle me-1"></i>Available Features:</h6>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check text-success me-2"></i>Package description and details</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Available dates calendar view</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Booking history and analytics</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Customer reviews and ratings</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-info"><i class="fas fa-info-circle me-1"></i>Quick Actions:</h6>
                                <div class="d-grid gap-2">
                                    <a href="/artist/edit/${packageId}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit me-1"></i>Edit Package
                                    </a>
                                    <button class="btn btn-outline-secondary btn-sm" onclick="closePackageModal()">
                                        <i class="fas fa-times me-1"></i>Close
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-lightbulb me-2"></i>
                            <strong>Tip:</strong> Use the <strong>Edit</strong> button to modify package details and availability.
                        </div>
                    </div>
                </div>
            `);
            console.log('Modal content updated successfully');
        }, 800);
        
    } catch (error) {
        console.error('Error in viewPackageDetails function:', error);
        alert('An error occurred while opening package details: ' + error.message);
    }
}

// Make function globally available
window.viewPackageDetails = viewPackageDetails;

// Close modal function
function closePackageModal() {
    const modal = document.getElementById('packageDetailsModal');
    const backdrop = document.querySelector('.modal-backdrop');
    
    // Get stored scroll position before modifying modal
    const scrollY = modal ? (modal.getAttribute('data-scroll-y') || '0') : '0';
    
    if (modal) {
        modal.classList.remove('show', 'modal-force-show');
        modal.style.display = 'none';
        modal.setAttribute('aria-hidden', 'true');
        modal.removeAttribute('aria-modal');
        modal.removeAttribute('data-scroll-y');
    }
    
    if (backdrop) {
        backdrop.remove();
    }
    
    // Restore scrolling and position
    document.body.classList.remove('modal-open');
    document.body.style.position = '';
    document.body.style.top = '';
    document.body.style.width = '';
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
    document.documentElement.style.overflow = '';
    
    // Restore scroll position
    window.scrollTo(0, parseInt(scrollY));
    
    console.log('Modal closed and scrolling restored to position:', scrollY);
}

window.closePackageModal = closePackageModal;

// Make test function globally available
window.testModal = testModal;

// Initialize tooltips if using Bootstrap 5
document.addEventListener('DOMContentLoaded', function() {
    // Debug: Check if jQuery and Bootstrap are loaded
    if (typeof $ === 'undefined') {
        console.error('jQuery is not loaded');
    }
    if (typeof bootstrap === 'undefined') {
        console.error('Bootstrap is not loaded');
    }
    
    // Initialize tooltips
    try {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        console.log('Tooltips initialized');
    } catch(error) {
        console.error('Error initializing tooltips:', error);
    }
    
    // Debug: Verify viewPackageDetails function is available
    if (typeof window.viewPackageDetails === 'function') {
        console.log('viewPackageDetails function is available');
    } else {
        console.error('viewPackageDetails function is not available');
    }
});
</script>

@endsection

