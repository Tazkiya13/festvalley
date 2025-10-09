@extends('admin.home')
@section('title', 'Orders List')

@section('content')
      <div>
        <div class="main-content" style="padding: 20px;">
            <!-- Desktop Table View -->
            <div class="responsive-table-container">
                <table class="table table-striped table-hover table-bordered responsive-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Invoice Number</th>
                            <th>Image</th>
                            <th>Transaction Date</th>
                            <th>Status</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>{{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->index + 1 }}</td>
                                <td>
                                    @if ($order->invoice)
                                        {{ $order->invoice->invoice_number }}
                                    @else
                                        No invoice available
                                    @endif
                                </td>
                                <td>
                                    <img src="{{ asset('images/default.jpg') }}" 
                                         data-image="{{ $order->payment_proof }}" 
                                         alt="Payment Proof"
                                         width="100" 
                                         class="table-img">
                                </td>
                                <td>{{ $order->invoice->transaction_date ? \Carbon\Carbon::parse($order->invoice->transaction_date)->translatedFormat('d F Y, H:i') : '-' }}
                                </td>
                                <td>
                                   @if($order->status == 'waiting approval')
                                        <span class="bg-warning text-dark" style="padding: 5px; border-radius: 5px;">{{ $order->status }}</span>
                                    @elseif($order->status == 'approved')
                                        <span class="bg-success text-dark" style="padding: 5px; border-radius: 5px;">{{ $order->status }}</span>
                                    @elseif($order->status == 'rejected')
                                        <span class="bg-danger text-light" style="padding: 5px; border-radius: 5px;">{{ $order->status }}</span>
                                    @endif
                                </td>
                                <td>{{ euro($order->invoice->total) }}</td>
                                <td>
                                    @php
                                        // Cek jika baris ini adalah baris terakhir
                                        $isLastRow = $loop->last;
                                    @endphp
                                    <div class="dropdown{{ $isLastRow ? ' dropup' : '' }}">
                                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Action
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right"
                                            style="z-index:1051; min-width:120px;">
                                            <a class="dropdown-item"
                                                href="{{ route('order.approve', $order->id) }}">Approve</a>
                                            <a class="dropdown-item"
                                                href="{{ route('order.reject', $order->id) }}">Reject</a>
                                            <a class="dropdown-item btn-detail-modal" href="#" data-toggle="modal"
                                                data-target="#detailModal"
                                                data-img="{{ $order->payment_proof ? asset('storage/' . $order->payment_proof) : asset('storage/default.png') }}"
                                                data-price="{{ euro($order->invoice->total) }}"
                                                data-tanggal="{{ $order->invoice->transaction_date ? \Carbon\Carbon::parse($order->invoice->transaction_date)->translatedFormat('d F Y, H:i') : '-' }}">Detail</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center empty-state">
                                    <i class="material-icons">shopping_cart</i>
                                    <h5>No orders found</h5>
                                    <p>Orders will appear here when customers make purchases</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="mobile-cards">
                @forelse($orders as $order)
                    <div class="mobile-card">
                        <div class="mobile-card-header">
                            <span>Order
                                #{{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->index + 1 }}</span>
                            <span class="mobile-price-badge">{{ euro($order->invoice->total) }}</span>
                        </div>

                        <div class="mobile-card-body">
                            <!-- Image -->
                            <div class="mobile-card-row">
                                <div class="mobile-card-label">Payment Proof:</div>
                                <div class="mobile-card-value">
                                    <img src="{{ asset('images/default.jpg') }}" 
                                         data-image="{{ $order->payment_proof }}" 
                                         alt="Payment Proof"
                                         class="mobile-card-image">
                                </div>
                            </div>

                            <!-- Invoice Number -->
                            <div class="mobile-card-row">
                                <div class="mobile-card-label">Invoice:</div>
                                <div class="mobile-card-value">
                                    @if ($order->invoice)
                                        <strong>{{ $order->invoice->invoice_number }}</strong>
                                    @else
                                        <em>No invoice available</em>
                                    @endif
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="mobile-card-row">
                                <div class="mobile-card-label">Status:</div>
                                <div class="mobile-card-value">
                                   @if($order->status == 'waiting approval')
                                        <span class="bg-warning text-dark" style="padding: 5px; border-radius: 5px;">{{ $order->status }}</span>
                                    @elseif($order->status == 'approved')
                                        <span class="bg-success text-dark" style="padding: 5px; border-radius: 5px;">{{ $order->status }}</span>
                                    @elseif($order->status == 'rejected')
                                        <span class="bg-danger text-light" style="padding: 5px; border-radius: 5px;">{{ $order->status }}</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Tanggal Transaksi -->
                            <div class="mobile-card-row">
                                <div class="mobile-card-label">Date:</div>
                                <div class="mobile-card-value">
                                    {{ $order->invoice->transaction_date ? \Carbon\Carbon::parse($order->invoice->transaction_date)->translatedFormat('d F Y, H:i') : '-' }}
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="mobile-card-actions">
                                <a href="{{ route('order.approve', $order->id) }}" class="btn btn-success btn-sm">
                                    <i class="material-icons">check</i> Approve
                                </a>
                                <a href="{{ route('order.reject', $order->id) }}" class="btn btn-danger btn-sm">
                                    <i class="material-icons">close</i> Reject
                                </a>
                                <button class="btn btn-info btn-sm btn-detail-modal" data-toggle="modal"
                                    data-target="#detailModal"
                                    data-img="{{ $order->payment_proof ? asset('storage/' . $order->payment_proof) : asset('storage/default.png') }}"
                                    data-price="{{ euro($order->invoice->total) }}"
                                    data-tanggal="{{ $order->invoice->transaction_date ? \Carbon\Carbon::parse($order->invoice->transaction_date)->translatedFormat('d F Y, H:i') : '-' }}">
                                    <i class="material-icons">visibility</i> Detail
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="material-icons">shopping_cart</i>
                        <h5>No orders found</h5>
                        <p>Orders will appear here when customers make purchases</p>
                    </div>
                @endforelse
            </div>

            <!-- Modal Detail Bootstrap 4 alpha -->
            <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content" style="border-radius:10px;">
                        <div class="modal-header">
                            <h5 class="modal-title" id="detailModalLabel">Order Details</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                style="font-size:2rem;">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-center">
                            <img id="detailModalImg" src="" alt="Image"
                                style="max-width:90vw; max-height:50vh; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.2); margin-bottom:20px;">
                            <div class="mt-3">
                                <p><strong>Price:</strong> <span id="detailModalHarga"></span></p>
                                <p><strong>Transaction Date:</strong> <span id="detailModalTanggal"></span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="paginate">
        {{ $orders->links('pagination::bootstrap-4') }}
    </div>

@include('partials.image-url-handler')
@endsection
