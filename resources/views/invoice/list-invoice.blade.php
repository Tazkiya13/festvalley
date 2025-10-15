@extends('admin.home')
@section('title', 'List Invoice')
@section('content')
    <div class="main-content" style="padding: 20px;">

        <!-- Desktop Table View -->
        <div class="responsive-table-container">
            <table class="table table-striped table-hover table-bordered responsive-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor Invoice</th>
                        <th>Image</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Transaction Date</th>
                        <th>Package Name</th>
                        <th>Artist Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $invoice)
                        @php
                            $package = $packages->where('id', $invoice->package_id)->first();
                        @endphp
                        <tr>
                            <td>{{ isset($invoices) && method_exists($invoices, 'currentPage') ? ($invoices->currentPage() - 1) * $invoices->perPage() + $loop->index + 1 : $loop->iteration }}
                            </td>
                            <td>{{ $invoice->invoice_number }}</td>
                            <td>
                                <img src="{{ asset('images/default.jpg') }}" 
                                     data-image="{{ $package->image ?? '' }}" 
                                     alt="Package image" 
                                     width="100" 
                                     class="table-img">
                            </td>
                            <td>
                                @if ($invoice->status == 'unpaid')
                                    <span class="bg-primary text-light" style="padding: 5px; border-radius: 5px;">{{ $invoice->status }}</span>
                                @elseif($invoice->status == 'paid')
                                    <span class="bg-success text-dark" style="padding: 5px; border-radius: 5px;">{{ $invoice->status }}</span>
                                @elseif($invoice->status == 'waiting')
                                    <span class="bg-warning text-dark" style="padding: 5px; border-radius: 5px;">{{ $invoice->status }}</span>
                                @else
                                    <span class="bg-danger text-light" style="padding: 5px; border-radius: 5px;">{{ $invoice->status }}</span>
                                @endif
                            </td>
                            <td>{{ euro($invoice->total) }}</td>
                            <td>
                                {{ $invoice->transaction_date ? \Carbon\Carbon::parse($invoice->transaction_date)->translatedFormat('d F Y, H:i') : '-' }}
                            </td>
                            <td>{{ $package ? $package->package_name : '-' }}</td>
                            <td>{{ $package && $package->user ? $package->user->name : '-' }}</td>
                            <td>
                                <a href="{{ route('invoice.detail', $invoice->id) }}" type="button" class="btn btn-primary btn-sm">
                                    Detail
                                </a>
                                @if ($invoice->status == 'paid')
                                    @if (!($invoice->email && in_array($invoice->email->status, ['waiting', 'approved', 'rejected'])))
                                        <!-- Automated email booking system removed -->
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center empty-state">
                                <i class="material-icons">receipt</i>
                                <h5>No invoices found</h5>
                                <p>Invoices will appear here when customers make payments</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="mobile-cards">
            @forelse($invoices as $invoice)
                @php
                    $package = $packages->where('id', $invoice->package_id)->first();
                @endphp
                <div class="mobile-card">
                    <div class="mobile-card-header">
                        <span>Invoice #{{ isset($invoices) && method_exists($invoices, 'currentPage') ? ($invoices->currentPage() - 1) * $invoices->perPage() + $loop->index + 1 : $loop->iteration }}</span>
                        @if ($invoice->status == 'unpaid')
                                    <span class="bg-primary text-light" style="padding: 5px; border-radius: 5px;">{{ $invoice->status }}</span>
                                @elseif($invoice->status == 'paid')
                                    <span class="bg-success text-dark" style="padding: 5px; border-radius: 5px;">{{ $invoice->status }}</span>
                                @elseif($invoice->status == 'waiting')
                                    <span class="bg-warning text-dark" style="padding: 5px; border-radius: 5px;">{{ $invoice->status }}</span>
                                @else
                                    <span class="bg-danger text-light" style="padding: 5px; border-radius: 5px;">{{ $invoice->status }}</span>
                                @endif
                    </div>

                    <div class="mobile-card-body">
                        <!-- Image -->
                        <div class="mobile-card-row">
                            <div class="mobile-card-label">Image:</div>
                            <div class="mobile-card-value">
                                <img src="{{ asset('images/default.jpg') }}" 
                                     data-image="{{ $package->image ?? '' }}" 
                                     alt="Package image" 
                                     class="mobile-img">
                            </div>
                        </div>

                        <!-- Invoice Number -->
                        <div class="mobile-card-row">
                            <div class="mobile-card-label">Invoice Number:</div>
                            <div class="mobile-card-value">
                                <strong>{{ $invoice->invoice_number }}</strong>
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="mobile-card-row">
                            <div class="mobile-card-label">Total:</div>
                            <div class="mobile-card-value">
                                <strong>{{ euro($invoice->total) }}</strong>
                            </div>
                        </div>

                        <!-- Tanggal Transaksi -->
                        <div class="mobile-card-row">
                            <div class="mobile-card-label">Tanggal:</div>
                            <div class="mobile-card-value">
                                {{ $invoice->transaction_date ? \Carbon\Carbon::parse($invoice->transaction_date)->translatedFormat('d F Y, H:i') : '-' }}
                            </div>
                        </div>

                        <!-- Nama package -->
                        <div class="mobile-card-row">
                            <div class="mobile-card-label">package:</div>
                            <div class="mobile-card-value">
                                {{ $package ? $package->package_name : '-' }}
                            </div>
                        </div>

                        <!-- Nama Artis -->
                        <div class="mobile-card-row">
                            <div class="mobile-card-label">Artis:</div>
                            <div class="mobile-card-value">
                                {{ $package && $package->user ? $package->user->name : '-' }}
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="mobile-card-actions">
                            <a href="{{ route('invoice.detail', $invoice->id) }}" class="btn btn-primary btn-sm">
                                <i class="material-icons">visibility</i> Detail
                            </a>
                            @if ($invoice->status == 'paid')
                                @if (!($invoice->email && in_array($invoice->email->status, ['waiting', 'approved', 'rejected'])))
                                    <!-- Automated email booking system removed -->
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="material-icons">receipt</i>
                    <h5>No invoices found</h5>
                    <p>Invoices will appear here when customers make payments</p>
                </div>
            @endforelse
        </div>
        <div class="text-center" class="btn btn-sm white rounded jscroll-next">
               {{ $invoices->links('pagination::bootstrap-4') }}
        </div>
    </div>

@include('partials.image-url-handler')
@endsection
