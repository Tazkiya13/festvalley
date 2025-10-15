@extends('admin.home')
@section('title', 'Admin Dashboard')
@section('content')
<div class="container-fluid">
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Admin Dashboard</h1>
            <p class="text-muted">Welcome to the Festvalley administration panel</p>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon bg-primary">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ \App\Models\User::count() }}</h3>
                        <p>Total Users</p>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon bg-info">
                        <i class="fas fa-music"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ \App\Models\Package::count() }}</h3>
                        <p>Total Packages</p>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon bg-warning">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ \App\Models\Invoice::count() }}</h3>
                        <p>Total Bookings</p>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon bg-success">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="stat-info">
                        <h3>€ {{ number_format(\App\Models\Invoice::where('status', 'paid')->sum('total'), 0, ',', '.') }}</h3>
                        <p>Total Revenue</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Approval Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-clock text-warning me-2"></i>
                            Payment needs approval
                        </h4>
                        <a href="{{ route('order.index') }}" class="btn btn-sm btn-outline-warning">View All Orders</a>
                    </div>
                    <div class="card-body">
                        @php
                            $pendingOrders = \App\Models\Order::with(['invoice.user', 'invoice.package'])
                                ->whereIn('status', ['waiting approval'])
                                ->whereNotNull('payment_proof')
                                ->orderBy('id', 'asc')
                                ->limit(10)
                                ->get();
                        @endphp
                        
                        @if($pendingOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 10%;">ID</th>
                                        <th style="width: 15%;">Invoice</th>
                                        <th style="width: 20%;">Customer</th>
                                        <th style="width: 20%;">Package</th>
                                        <th style="width: 15%;">Amount</th>
                                        <th style="width: 10%;">Payment Proof</th>
                                        <th style="width: 10%;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingOrders as $order)
                                    <tr>
                                        <td class="fw-bold">#{{ $order->id }}</td>
                                        <td>{{ $order->invoice->invoice_number }}</td>
                                        <td>{{ Str::limit($order->invoice->user->name, 15) }}</td>
                                        <td>{{ Str::limit($order->invoice->package->package_name, 20) }}</td>
                                        <td class="fw-bold">€ {{ number_format($order->invoice->total, 0, ',', '.') }}</td>
                                        <td>
                                            @if($order->payment_proof)
                                                <button class="btn btn-sm btn-outline-info" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#paymentProofModal"
                                                        data-image="{{ asset('storage/' . $order->payment_proof) }}"
                                                        data-order="{{ $order->id }}">
                                                    <i class="fas fa-eye"></i> View
                                                </button>
                                            @else
                                                <span class="text-muted">No proof</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('order.approve', $order->id) }}" 
                                                   class="btn btn-success"
                                                   onclick="return confirm('Are you sure you want to approve this payment?')">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                                <a href="{{ route('order.reject', $order->id) }}" 
                                                   class="btn btn-danger"
                                                   onclick="return confirm('Are you sure you want to reject this payment?')">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <h5 class="text-muted">No payments awaiting approval</h5>
                            <p class="text-muted">All payment proofs have been processed!</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Recent Bookings</h4>
                        <a href="{{ route('admin.booking.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 15%;">Invoice</th>
                                        <th style="width: 20%;">Customer</th>
                                        <th style="width: 25%;">Package</th>
                                        <th style="width: 15%;">Status</th>
                                        <th style="width: 15%;">Total</th>
                                        <th style="width: 10%;">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse(\App\Models\Invoice::with(['user', 'package', 'email'])->latest()->take(5)->get() as $invoice)
                                    <tr>
                                        <td class="fw-bold">#{{ $invoice->invoice_number }}</td>
                                        <td>{{ Str::limit($invoice->user->name, 15) }}</td>
                                        <td>{{ Str::limit($invoice->package->package_name, 20) }}</td>
                                        <td>
                                            <span class="badge rounded-pill {{ $invoice->status === 'paid' ? 'bg-success' : ($invoice->status === 'waiting' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                                {{ ucfirst($invoice->status) }}
                                            </span>
                                        </td>
                                        <td class="fw-bold">€ {{ number_format($invoice->total, 0, ',', '.') }}</td>
                                        <td class="text-muted">{{ $invoice->created_at->format('M j') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            <i class="fas fa-inbox fa-2x mb-2"></i>
                                            <div>No bookings found</div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h4 class="mb-0">Quick Actions</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-3">
                            <a href="{{ route('packages.index') }}" class="btn btn-primary d-flex align-items-center">
                                <i class="fas fa-music me-2"></i>
                                <span>Manage Packages</span>
                                <i class="fas fa-arrow-right ms-auto"></i>
                            </a>
                            <a href="{{ route('artists.index') }}" class="btn btn-info d-flex align-items-center">
                                <i class="fas fa-box me-2"></i>
                                <span>Manage Packages</span>
                                <i class="fas fa-arrow-right ms-auto"></i>
                            </a>
                            <a href="{{ route('admin.booking.index') }}" class="btn btn-warning d-flex align-items-center">
                                <i class="fas fa-calendar me-2"></i>
                                <span>View Bookings</span>
                                <i class="fas fa-arrow-right ms-auto"></i>
                            </a>
                            <a href="{{ route('customer.invoices') }}" class="btn btn-success d-flex align-items-center">
                                <i class="fas fa-file-invoice-dollar me-2"></i>
                                <span>Customer Invoices</span>
                                <i class="fas fa-arrow-right ms-auto"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.dashboard-container {
    padding: 20px;
}

.dashboard-header {
    margin-bottom: 30px;
    text-align: center;
}

.dashboard-header h1 {
    color: #2c3e50;
    margin-bottom: 10px;
    font-size: 2.5rem;
    font-weight: 600;
}

.dashboard-header .text-muted {
    font-size: 1.1rem;
}

/* Stat Cards */
.stat-card {
    background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    display: flex;
    align-items: center;
    border: none;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 20px;
    font-size: 1.4rem;
    color: white;
}

.stat-icon.bg-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.stat-icon.bg-info {
    background: linear-gradient(135deg, #36d1dc 0%, #5b86e5 100%);
}

.stat-icon.bg-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.stat-icon.bg-success {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.stat-info h3 {
    margin: 0;
    color: #2c3e50;
    font-size: 1.8rem;
    font-weight: 700;
    line-height: 1.2;
}

.stat-info p {
    margin: 5px 0 0 0;
    color: #6c757d;
    font-size: 0.95rem;
    font-weight: 500;
}

/* Cards */
.card {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    border: none;
    overflow: hidden;
}

.card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid #dee2e6;
    padding: 20px 25px;
    border-radius: 15px 15px 0 0 !important;
}

.card-header h4 {
    margin: 0;
    color: #2c3e50;
    font-weight: 600;
    font-size: 1.2rem;
}

.card-body {
    padding: 25px;
}

/* Table */
.table {
    margin-bottom: 0;
}

.table th {
    border-top: none;
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    color: #495057;
    font-size: 0.9rem;
    padding: 15px 10px;
}

.table td {
    padding: 15px 10px;
    vertical-align: middle;
    border-bottom: 1px solid #f1f3f4;
    font-size: 0.9rem;
}

.table-hover tbody tr:hover {
    background-color: #f8f9fa;
}

/* Badges */
.badge {
    font-size: 0.75rem;
    font-weight: 500;
    padding: 5px 10px;
}

/* Buttons */
.btn {
    border-radius: 10px;
    font-weight: 500;
    padding: 12px 20px;
    border: none;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

/* Payment Approval Table Styling */
.table th {
    background-color: #f8f9fa;
    border-top: none;
    color: #495057;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
}

.table td {
    vertical-align: middle;
    border-top: 1px solid #dee2e6;
}

.btn-group-sm > .btn {
    padding: 4px 8px;
    font-size: 0.875rem;
    border-radius: 6px;
}

/* Modal styling */
.modal-content {
    border: none;
    border-radius: 15px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
}

.modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px 15px 0 0;
    border-bottom: none;
}

.modal-title {
    font-weight: 600;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.btn-info {
    background: linear-gradient(135deg, #36d1dc 0%, #5b86e5 100%);
}

.btn-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.btn-success {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.btn-outline-primary {
    border-color: #667eea;
    color: #667eea;
}

.btn-outline-primary:hover {
    background: #667eea;
    border-color: #667eea;
}

/* Responsive Design */
@media (max-width: 768px) {
    .dashboard-container {
        padding: 15px;
    }
    
    .dashboard-header h1 {
        font-size: 2rem;
    }
    
    .stat-card {
        padding: 20px;
        flex-direction: column;
        text-align: center;
    }
    
    .stat-icon {
        margin-right: 0;
        margin-bottom: 15px;
    }
    
    .stat-info h3 {
        font-size: 1.5rem;
    }
    
    .card-body {
        padding: 20px 15px;
    }
    
    .table th,
    .table td {
        padding: 10px 5px;
        font-size: 0.8rem;
    }
    
    .btn {
        padding: 10px 15px;
        font-size: 0.9rem;
    }
}

@media (max-width: 576px) {
    .dashboard-header h1 {
        font-size: 1.8rem;
    }
    
    .stat-info h3 {
        font-size: 1.3rem;
    }
    
    .table th,
    .table td {
        padding: 8px 4px;
        font-size: 0.75rem;
    }
}
</style>

<!-- Payment Proof Modal -->
<div class="modal fade" id="paymentProofModal" tabindex="-1" aria-labelledby="paymentProofModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentProofModalLabel">Payment Proof - Order #<span id="orderNumber"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="paymentProofImage" src="" alt="Payment Proof" class="img-fluid rounded shadow" style="max-height: 500px;">
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a id="approveOrderBtn" href="#" class="btn btn-success">
                    <i class="fas fa-check"></i> Approve Payment
                </a>
                <a id="rejectOrderBtn" href="#" class="btn btn-danger">
                    <i class="fas fa-times"></i> Reject Payment
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle payment proof modal
    document.querySelectorAll('[data-bs-target="#paymentProofModal"]').forEach(function(button) {
        button.addEventListener('click', function() {
            const imageUrl = this.getAttribute('data-image');
            const orderId = this.getAttribute('data-order');
            
            document.getElementById('paymentProofImage').src = imageUrl;
            document.getElementById('orderNumber').textContent = orderId;
            document.getElementById('approveOrderBtn').href = '{{ route("order.approve", ":id") }}'.replace(':id', orderId);
            document.getElementById('rejectOrderBtn').href = '{{ route("order.reject", ":id") }}'.replace(':id', orderId);
        });
    });
});
</script>
@endsection
