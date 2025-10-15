@extends('layouts.customer')

@section('title', 'My Bookings')

@section('content')
<div class="row">
    <div class="col-12">
        <h2>My Bookings</h2>
            
            @if($invoices->count() > 0)
                <div class="row">
                    @foreach($invoices as $invoice)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card">
                                <img src="{{ asset('images/default.jpg') }}" data-image="{{ $invoice->package->image }}"
                                    alt="{{ $invoice->package->package_name }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $invoice->package->package_name }}</h5>
                                    <p class="card-text">
                                        <strong>Artist:</strong> {{ $invoice->package->user->name }}<br>
                                        <strong>Invoice #:</strong> {{ $invoice->invoice_number }}<br>
                                        <strong>Status:</strong> 
                                        <span class="badge 
                                            @if($invoice->status == 'paid') badge-success
                                            @elseif($invoice->status == 'waiting') badge-warning
                                            @elseif($invoice->status == 'rejected') badge-danger
                                            @else badge-secondary
                                            @endif
                                        ">{{ ucfirst($invoice->status) }}</span><br>
                                        <strong>Total:</strong> € {{ number_format($invoice->total) }}<br>
                                        @if($invoice->email)
                                            <strong>Booking Status:</strong> 
                                            <span class="badge
                                                @if($invoice->email->status == 'approved') badge-success
                                                @elseif($invoice->email->status == 'waiting') badge-warning
                                                @elseif($invoice->email->status == 'rejected') badge-danger
                                                @else badge-secondary
                                                @endif
                                            ">{{ ucfirst($invoice->email->status) }}</span>
                                        @endif
                                    </p>
                                    <a href="{{ route('booking.confirmation', $invoice->id) }}" class="btn btn-primary">View Details</a>
                                    @if($invoice->email && $invoice->email->status == 'approved' && $invoice->status == 'unpaid')
                                        <a href="{{ route('booking.payment', $invoice->id) }}" class="btn btn-success">Pay Now</a>
                                    @endif
                                </div>
                                <div class="card-footer text-muted">
                                    Booked on {{ $invoice->created_at->format('M d, Y') }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="d-flex justify-content-center">
                    {{ $invoices->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <h4>No bookings found</h4>
                    <p>You haven't made any bookings yet.</p>
                    <a href="{{ route('home') }}" class="btn btn-primary">Browse Packages</a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
// Helper function to get proper image URL
function getImageUrl(image, defaultImage = '{{ asset("images/default.jpg") }}') {
    if (!image) return defaultImage;

    // If image already contains http/https, use as-is
    if (image.includes('http://') || image.includes('https://')) {
        return image;
    }

    // Otherwise, prepend storage path
    return '{{ asset("storage/") }}' + '/' + image;
}

// Apply proper image URLs after page load
document.addEventListener('DOMContentLoaded', function() {
    // Update all images with data-image attribute
    document.querySelectorAll('img[data-image]').forEach(img => {
        const image = img.getAttribute('data-image');
        img.src = getImageUrl(image);
    });

    // Update background images
    document.querySelectorAll('[data-bg-image]').forEach(element => {
        const image = element.getAttribute('data-bg-image');
        if (image) {
            const imageUrl = getImageUrl(image);
            element.style.backgroundImage = `url('${imageUrl}')`;
        }
    });
});
</script>
@endsection
