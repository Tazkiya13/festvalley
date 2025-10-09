@extends('layouts.customer')
@section('title', 'Invoice')

@section('content')
    <div class="container py-4 invoice-ui">
        @if (!$invoice)
            <form action="{{ route('invoice.index', $package->id) }}" method="GET">
                @csrf
                <div class="mb-3">
                    <label for="tanggal_id" class="form-label">Select Date</label>
                    <select name="tanggal_id" id="tanggal_id" class="form-control" required>
                        @foreach ($package->availableDates as $date)
                            <option value="{{ $date->id }}">
                                {{ \Carbon\Carbon::parse($date->tanggal)->translatedFormat('d F Y') }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Create Invoice</button>
            </form>
        @else
            <div class="d-flex justify-content-between align-items-center mb-4 invoice-header-ui">
                {{-- <div>
                    <img src="{{ asset('img/logo.png') }}" alt="Logo" style="height:48px;">
                </div> --}}
                <div>
                    <h4 class="mb-0 fw-bold text-primary">{{ $invoice->invoice_number }}</h4>
                    @if ($invoice->status == 'unpaid')
                        Status: <span class="bg-primary text-light"
                            style="padding: 5px; border-radius: 5px;">{{ $invoice->status }}</span>
                    @elseif($invoice->status == 'paid')
                        Status: <span class="bg-success text-light"
                            style="padding: 5px; border-radius: 5px;">{{ $invoice->status }}</span>
                    @elseif($invoice->status == 'waiting')
                        Status: <span class="bg-warning text-light"
                            style="padding: 5px; border-radius: 5px;">{{ $invoice->status }}</span>
                    @else
                        Status: <span class="bg-danger text-light"
                            style="padding: 5px; border-radius: 5px;">{{ $invoice->status }}</span>
                    @endif
                </div>
            </div>
            <div class="title-invoice">
                <div class="row mb-4 g-3">
                    <div class="col-md-6">
                        <ul class="list-unstyled mb-0 ps-1">
                            <li class="d-flex align-items-center mb-2">
                                <i class="bi bi-person-circle fs-4 text-primary me-2"></i>
                                <span class="fw-bold text-secondary">Invoice Recipient</span>
                            </li>
                            <li class="invoice-recipient ps-4 mb-1">{{ $user->name }}</li>
                            {{-- <li class="invoice-recipient ps-4 mb-1">Test lalal</li>
                            <li class="invoice-recipient ps-4">Solo, Jawa Tengah, 13849</li> --}}
                        </ul>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <ul class="list-unstyled mb-0 pe-md-2">
                            <li class="d-flex align-items-center mb-2 justify-content-md-end">
                                <i class="bi bi-building fs-4 text-primary me-2"></i>
                                <span class="fw-bold text-secondary">Paid To</span>
                            </li>
                            <li class="invoice-company ps-md-4 mb-1">Test Company</li>
                            {{-- <li class="invoice-company ps-md-4 mb-1">Jakarta, DKI Jakarta 12345</li> --}}
                        </ul>
                    </div>
                </div>
                <div class="row mb-2 g-3">
                    <div class="col-md-6">
                        <div class="bg-light rounded-3 p-3 h-100 d-flex align-items-center">
                            <i class="bi bi-calendar-event text-primary fs-5 me-2"></i>
                            <div>
                                <span class="fw-bold text-secondary">Invoice Date</span><br>
                                <span class="invoice-date">
                                    {{ $invoice->created_at ? \Carbon\Carbon::parse($invoice->created_at)->translatedFormat('d F Y, H:i') : '-' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <div class="bg-light rounded-3 p-3 h-100 d-flex align-items-center justify-content-md-end">
                            <i class="bi bi-credit-card-2-front text-primary fs-5 me-2"></i>
                            <div>
                                <span class="fw-bold text-secondary">Payment Method</span><br>
                                <span class="invoice-method">Bank Transfer</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle invoice-table">
                    <thead class="table-light">
                        <tr>
                            <th>Description</th>
                            <th class="text-end">Amount</th>
                            <th class="text-end">Selected Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                {{ $package->package_name }}
                                {{-- <br><small class="text-muted">Include mixing & mastering</small> --}}
                            </td>
                            <td class="text-end">{{ euro($package->price) }}</td>
                            <td class="text-end">
                                @if (isset($selectedDate))
                                    {{ \Carbon\Carbon::parse($selectedDate->tanggal)->translatedFormat('d F Y') }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        {{-- <tr>
                            <td>
                                Sewa Studio
                                <br><small class="text-muted">8 jam</small>
                            </td>
                            <td class="text-end">€ 800.000,00</td>
                        </tr> --}}
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Sub Total</th>
                            <th class="text-end">{{ euro($package->price) }}</th>
                            <th></th>
                        </tr>
                        {{-- <tr>
                            <th>PPN 11%</th>
                            <th class="text-end">€ 253.000,00</th>
                            <th></th>
                        </tr> --}}
                        <tr>
                            <th>Total</th>
                            <th class="text-end text-success fs-5">{{ euro($package->price) }}</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-bordered invoice-table">
                    <thead class="table-light">
                        <tr>
                            <th>Transaction Date</th>
                            <th>Payment Method</th>
                            <th>Transaction ID</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                {{ $invoice->transaction_date ? \Carbon\Carbon::parse($invoice->transaction_date)->translatedFormat('d F Y, H:i') : '-' }}
                            </td>
                            <td>Bank Transfer</td>
                            <td>{{ $invoice->transaction_id ? $invoice->transaction_id : '-' }}</td>
                            <td>{{ euro($package->price) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            {{-- <div class="footer-invoice"> --}}
            <div class="invoice-footer-ui text-end mt-4">
                <span class="text-muted">Thank you for using our service!</span>
                @if ($invoice->status == 'unpaid' || $invoice->status == 'rejected')
                    <button type="button" class="btn btn-success px-4 py-2 fw-bold" data-toggle="modal"
                        data-target="#paymentModal" data-invoice-number="{{ $invoice->invoice_number }}"
                        data-total="{{ euro($package->price) }}">
                        <i class="bi bi-cash-coin me-2"></i>Pay
                    </button>
                @endif
            </div>
            <!-- Modal tunggal di luar loop, di akhir file -->
            <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header border-bottom-0 pb-0" style="border-radius: 0.5rem 0.5rem 0 0;">
                            <h5 class="modal-title fw-bold text-primary" id="paymentModalLabel" style="font-size: 1.3rem;">
                                Konfirmasi Pembayaran</h5>
                            <button type="button" class="close ms-2" data-dismiss="modal" aria-label="Close"
                                style="font-size: 2rem; line-height: 1;">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form
                            @if ($invoice->status == 'rejected') action="{{ route('order.update', $invoice->id) }}" method="POST"
                            @else
                                action="{{ route('order.store') }}" method="POST" @endif
                            enctype="multipart/form-data" class="mb-0">
                            @csrf
                            @if ($invoice->status == 'rejected')
                                @method('POST')
                            @endif
                            @if ($invoice->orders && $invoice->orders->count() > 0)
                                <input type="hidden" name="order_id" id="order_id"
                                    value="{{ $invoice->orders->first()->id }}">
                            @endif
                            <input type="hidden" name="invoice_id" id="invoice_id" value="{{ $invoice->id }}">
                            <input type="hidden" name="user_id" id="user_id" value="{{ auth()->user()->id }}">
                            <div class="modal-body pt-3 pb-0">
                                <div class="mb-3">
                                    <p id="modal-invoice-text" class="mb-1"></p>
                                    <p class="mb-2"><strong>Total:</strong> €<span id="modal-total"></span></p>
                                </div>
                                <div class="mb-3">
                                    <label for="payment_proof" class="form-label fw-semibold">Upload Payment Proof</label>
                                    <input type="file" name="payment_proof" id="payment_proof" class="form-control"
                                        required>
                                </div>
                            </div>
                            <div class="modal-footer border-top-0 pt-0 d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-secondary me-2" data-dismiss="modal">
                                    <i class="bi bi-x-circle me-1"></i>Cancel
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-cash-coin me-1"></i>Proceed to Payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

    @endsection

    @section('scripts')
        <script>
            // Script untuk mengupdate konten modal pembayaran
            var paymentButtons = document.querySelectorAll('button[data-target="#paymentModal"]');
            paymentButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var invoiceNumber = this.getAttribute('data-invoice-number');
                    var total = this.getAttribute('data-total');

                    // Update konten modal
                    document.getElementById('modal-invoice-text').innerText = 'Nomor Invoice: ' + invoiceNumber;
                    document.getElementById('modal-total').innerText = total;
                });
            });
        </script>
    @endsection
