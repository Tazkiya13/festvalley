@extends('layouts.customer')

@section('title', 'My Invoices')

@section('content')
<div class="row">
    <div class="col-12">
        <h2>My Invoices</h2>
            
            @if($invoices->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Invoice #</th>
                                <th>Package</th>
                                <th>Artist</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoices as $invoice)
                                <tr>
                                    <td>{{ $invoice->invoice_number }}</td>
                                    <td>{{ $invoice->package->package_name }}</td>
                                    <td>{{ $invoice->package->user->name }}</td>
                                    <td>€ {{ number_format($invoice->total) }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($invoice->status == 'paid') badge-success
                                            @elseif($invoice->status == 'waiting') badge-warning
                                            @elseif($invoice->status == 'rejected') badge-danger
                                            @else badge-secondary
                                            @endif
                                        ">{{ ucfirst($invoice->status) }}</span>
                                    </td>
                                    <td>{{ $invoice->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('invoice.detail', $invoice->id) }}" class="btn btn-sm btn-info">View</a>
                                        @if($invoice->email && $invoice->email->status == 'approved' && $invoice->status == 'unpaid')
                                            <a href="{{ route('booking.payment', $invoice->id) }}" class="btn btn-sm btn-success">Pay</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center">
                    {{ $invoices->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <h4>No invoices found</h4>
                    <p>You don't have any invoices yet.</p>
                    <a href="{{ route('home') }}" class="btn btn-primary">Browse Packages</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
