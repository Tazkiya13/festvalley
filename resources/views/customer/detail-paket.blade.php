{{-- @extends('admin.home')
@section('title', 'Detail package')
@section('content')
    @include('admin.navbar')
    <div class="app-content white bg box-shadow-z2" role="main">
        <div class="app-body" id="view">
            <div class="pos-rlt">
                <div class="page-bg" 
                     data-stellar-ratio="2"
                     data-bg-image="{{ $package->image }}"
                     style="background-image: url('{{ asset('images/default.jpg') }}'); background-size:cover; background-position:center; opacity:0.18; position:absolute; top:0; left:0; width:100%; height:100%;">
                </div>
            </div>
            <div class="page-content" style="min-height: 750px;">
                <div class="padding b-b">
                    <div class="row no-gutters align-items-center" style="min-height: 520px; margin-bottom: 60px;">
                        <div class="col-md-4 mb-5">
                            <div class="item w r shadow" style="border-radius:18px;overflow:hidden;">
                                <div class="item-media">
                                    <div class="item-media-content"
                                         data-bg-image="{{ $package->image }}"
                                         style="background-image: url('{{ asset('images/default.jpg') }}'); height:320px; background-size:cover; background-position:center;">
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-3">
                                <span class="badge badge-pill badge-primary px-3 py-2" style="font-size:1.2rem;">
                                    {{ euro($package->price) }}
                                </span>
                            </div>
                            <div class="text-center mt-2">
                                <a href="#" class="btn btn-icon rounded btn-favorite mx-1"><i
                                        class="fa fa-heart-o"></i></a>
                                <a href="#" class="btn btn-icon rounded btn-share mx-1" data-toggle="modal"
                                    data-target="#share-modal"><i class="fa fa-share-alt"></i></a>
                            </div>
                            <div class="mt-4 text-center">
                                <span class="badge badge-secondary mr-2">Kategori: Musik</span>
                                <span class="badge badge-secondary mr-2">Populer</span>
                                <span class="badge badge-secondary">Band</span>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="p-l-md no-padding-xs" style="min-height: 520px;">
                                <div class="page-title mb-4">
                                    <h1 class="inline" style="font-size:2.2rem;font-weight:700;">{{ $package->package_name }}
                                    </h1>
                                </div>
                                <div style="height: 20px;"></div>
                                <div class="d-flex align-items-center mb-3">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($package->user->name ?? '-') }}&background=1c202b&color=fff&size=36"
                                        class="rounded-circle mr-2" style="width:36px;height:36px;">
                                    <span class="item-author text-muted" style="font-size:1.1rem;">
                                        <i class="material-icons" style="vertical-align:middle;">person</i>
                                        <span class="ml-1">{{ $package->user->name ?? '-' }}</span>
                                    </span>
                                </div>
                                <div style="height: 32px;"></div>
                                <div class="mb-4">
                                    <span class="badge badge-info badge-pill px-2 py-1" style="font-size:0.95rem;">
                                        <i class="material-icons" style="font-size:1.1em;">event</i>
                                        @if ($package->availableDates->count())
                                            {{ $package->availableDates->count() }} Tanggal Tersedia
                                        @else
                                            Tidak ada tanggal tersedia
                                        @endif
                                    </span>
                                </div>
                                <div style="height: 32px;"></div>
                                <div class="item-desc text-muted mb-5" style="font-size:1.15em; line-height:1.7;">
                                    {!! nl2br(e($package->description)) !!}
                                </div>
                                <div style="height: 32px;"></div>
                                @if ($package->availableDates->count())
                                    <!-- New Booking System Button -->
                                    <div class="mb-3">
                                        <a href="{{ route('booking.form', $package->id) }}" 
                                           class="btn btn-primary btn-lg d-flex align-items-center justify-content-center"
                                           style="font-weight:600; height:50px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 12px;">
                                            <i class="fa fa-calendar-plus" style="margin-right:8px;"></i>Book This Package
                                        </a>
                                        <small class="text-muted d-block mt-2 text-center">
                                            <i class="fa fa-shield-alt"></i> Secure booking for both guests and registered users
                                        </small>
                                    </div>

                                    <!-- Original Checkout Form (for existing users) -->
                                    <form action="{{ route('invoice.index', $package->id) }}" method="POST"
                                        class="form-inline d-inline-block mb-4">
                                        @csrf
                                        <input type="hidden" name="tanggal_id" id="tanggal_id_input">
                                        <div class="form-checkout-row">
                                            <div class="form-checkout-input">
                                                <div class="input-group date-picker-group mb-2">
                                                    <input type="text" class="form-control flatpickr-detail-package modern-date-field"
                                                        style="border-radius: 0 8px 8px 0; background: #f8f9fa;"
                                                        id="flatpickr-package-{{ $package->id }}"
                                                        data-package-id="{{ $package->id }}"
                                                        data-available-dates='@json($package->availableDates->pluck('tanggal')->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d')))'
                                                        data-available-ids='@json($package->availableDates->pluck('id'))'
                                                        placeholder="Lihat tanggal">
                                                </div>
                                            </div>
                                            <div class="form-checkout-btn">
                                                @can('create customer invoice')
                                                    @if (!$hasPendingInvoice)
                                                        <button type="submit"
                                                            class="btn btn-success btn-sm ml-2 px-4 d-flex align-items-center justify-content-center"
                                                            style="font-weight:600; height:40px;">
                                                            <i class="fa fa-shopping-cart" style="margin-right:8px;"></i>Quick Checkout
                                                        </button>
                                                    @else
                                                        <span
                                                            class="badge badge-warning ml-2 d-flex align-items-center justify-content-center"
                                                            style="height:40px;">Sudah ada pesanan berjalan</span>
                                                    @endif
                                                @endcan
                                            </div>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 pt-4">
                        <h5 class="mb-3">package Rekomendasi dari Artis Ini</h5>
                        <div class="row">
                            @php
                                $rekomendasi = \App\Models\package::with('user')
                                    ->where('user_id', $package->user_id)
                                    ->where('id', '!=', $package->id)
                                    ->latest()
                                    ->take(4)
                                    ->get();
                            @endphp
                            @forelse($rekomendasi as $r)
                                <div class="col-6 col-md-4 col-lg-3 mb-3">
                                    <a href="{{ route('card.detail', $r->id) }}" class="text-decoration-none">
                                        <div class="item r shadow-sm"
                                            style="border-radius:10px;overflow:hidden;min-height:150px;">
                                            <div class="item-media" style="height:90px;">
                                                <div class="item-media-content"
                                                    style="background-image: url('{{ $r->image ? asset('storage/' . $r->image) : asset('storage/default.jpg') }}'); height:100%; background-size:cover; background-position:center;">
                                                </div>
                                            </div>
                                            <div class="item-info p-2">
                                                <div class="item-title text-ellipsis" style="font-size:1em;">
                                                    {{ $r->package_name }}
                                                </div>
                                                <div class="item-author text-xs text-muted text-ellipsis">
                                                    {{ $r->user->name ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @empty
                                <div class="col-12 text-muted">Tidak ada package lain dari artis ini.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection --}}
{{-- flatpicker menggunakan flatpicker-detail-packages.js --}}

@extends('admin.home')
@section('title', 'Detail package')
@section('content')
    <div class="app dk" id="app">
        <div id="content" class="app-content white bg box-shadow-z2" style="min-height:100vh;" role="main">
            <div class="app-body" id="view">
                <div class="pos-rlt">
                    @include('admin.navbar')
                    <div class="page-bg" data-stellar-ratio="2"
                        style="background-image: url('{{ $package->image ? asset('storage/' . $package->image) : asset('pulse-template/assets/images/b0.jpg') }}')"></div>
                </div>
                <div class="page-content" style="min-height:100vh;">
                    <div class="padding b-b">
                        <div class="row-col">
                            <div class="col-sm w w-auto-xs m-b">
                                <div class="item w r">
                                    <div class="item-media">
                                        <div class="item-media-content"
                                            style="background-image: url('{{ $package->image ? asset('storage/' . $package->image) : asset('storage/default.png') }}')">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="p-l-md no-padding-xs">
                                    <div class="page-title">
                                        <h1 class="inline">{{ $package->package_name }}</h1>
                                    </div>
                                    <p class="item-desc text-ellipsis text-muted" data-ui-toggle-class="text-ellipsis">
                                        {{ $package->description }}</p>
                                    <div class="item-action m-b"
                                        style="display: flex; flex-direction:column; align-items: start;">
                                        <span class="text-muted"
                                            style="font-size:1.2rem;">{{ $package->user->name ?? '-' }}</span>
                                        <span class="text-muted">
                                            <span class="badge badge-pill badge-primary px-3 py-2"
                                                style="font-size:1.2rem;">
                                                {{ euro($package->price) }}
                                            </span>
                                        </span>
                                    </div>
                                    <div class="mb-4">
                                        <span class="badge badge-info badge-pill px-2 py-1" style="font-size:0.95rem;">
                                            <i class="material-icons" style="font-size:1.1em;">event</i>
                                            @if ($package->availableDates->count())
                                                {{ $package->availableDates->count() }} Tanggal Tersedia
                                            @else
                                                Tidak ada tanggal tersedia
                                            @endif
                                        </span>
                                    </div>
                                    @if ($package->availableDates->count())
                                        <form action="{{ route('invoice.index', $package->id) }}" method="POST"
                                            class="form-inline d-inline-block mb-4">
                                            @csrf
                                            <input type="hidden" name="tanggal_id" id="tanggal_id_input">
                                            <div class="form-checkout-row">
                                                <div class="form-checkout-input">
                                                    <div class="input-group date-picker-group mb-2">
                                                        <input type="text"
                                                            class="form-control flatpickr-detail-package modern-date-field"
                                                            style="border-radius: 0 8px 8px 0; background: #f8f9fa;"
                                                            id="flatpickr-package-{{ $package->id }}"
                                                            data-package-id="{{ $package->id }}"
                                                            data-available-dates='@json($package->availableDates->pluck('tanggal')->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d')))'
                                                            data-available-ids='@json($package->availableDates->pluck('id'))'
                                                            placeholder="Lihat tanggal">
                                                    </div>
                                                </div>
                                                <div class="form-checkout-btn">
                                                    @can('create customer invoice')
                                                        @if (!$hasPendingInvoice)
                                                            <button type="submit"
                                                                class="btn btn-success btn-sm ml-2 px-4 d-flex align-items-center justify-content-center"
                                                                style="font-weight:600; height:40px;">
                                                                <i class="fa fa-shopping-cart"
                                                                    style="margin-right:8px;"></i>Checkout
                                                            </button>
                                                        @else
                                                            <span
                                                                class="badge badge-warning ml-2 d-flex align-items-center justify-content-center"
                                                                style="height:40px;">Sudah ada pesanan berjalan</span>
                                                        @endif
                                                    @endcan
                                                </div>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row-col">
                        <div class="col-lg-9 b-r no-border-md">
                            <div class="padding">
                                <h5 class="m-b">From {{ $package->user->name ?? '-' }}</h5>
                                <div class="row m-b">
                                    @php
                                        $rekomendasi = \App\Models\package::with('user')
                                            ->where('user_id', $package->user_id)
                                            ->where('id', '!=', $package->id)
                                            ->latest()
                                            ->take(4)
                                            ->get();
                                    @endphp
                                    @forelse ($rekomendasi as $r)
                                        <div class="col-xs-6 col-sm-6 col-md-3">
                                            <div class="item r" data-id="item-2" data-src="#">
                                                <div class="item-media"><a href="{{ route('card.detail', $r->id) }}"
                                                        onclick="window.location=this.href; return false;"
                                                        class="item-media-content"
                                                        style="background-image: url('{{ $r->image ? asset('storage/' . $r->image) : asset('storage/default.jpg') }}')"></a>
                                                    <div class="item-overlay center">
                                                        {{-- <button class="btn-playpause">Play</button> --}}
                                                    </div>
                                                </div>
                                                <div class="item-info">
                                                    <div class="item-overlay bottom text-right">
                                                        {{-- <a href="#" class="btn-favorite"><i class="fa fa-heart-o"></i></a>
                                                        <a href="#" class="btn-more" data-toggle="dropdown"><i
                                                                class="fa fa-ellipsis-h"></i></a>
                                                        <div class="dropdown-menu pull-right black lt"></div> --}}
                                                    </div>
                                                    <div class="item-title text-ellipsis"><a
                                                            href="track.detail.html">{{ $r->package_name }}</a></div>
                                                    {{-- <div class="item-author text-sm text-ellipsis hide"><a
                                                            href="artist.detail.html" class="text-muted">Kygo</a></div>
                                                    <div class="item-meta text-sm text-muted">
                                                        <span
                                                            class="item-meta-stats text-xs"> --}}
                                                            {{-- <i class="fa fa-play text-muted"></i> 30 <i
                                                    class="fa fa-heart m-l-sm text-muted"></i> 10 --}}
                                                        {{-- </span> --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12 text-muted">Tidak ada package lain dari artis ini.</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 w-xxl w-auto-md">
                            <div class="padding" style="bottom: 60px" data-ui-jp="stick_in_parent">
                                <h6 class="text text-muted">Recommand for you</h6>
                                <div class="row item-list item-list-sm m-b">
                                    @php
                                        $packages = $packages->shuffle();
                                    @endphp
                                    @foreach ($packages->take(5) as $package)
                                        <div class="col-xs-12">
                                            <div class="item r" data-id="item-3" data-src="#">
                                                <div class="item-media"><a href="{{ route('card.detail', $package->id) }}"
                                                        onclick="window.location=this.href; return false;"
                                                        class="item-media-content"
                                                        style="background-image: url('{{ $package->image ? asset('storage/' . ltrim($package->image, '/')) : asset('images/default.jpg') }}')"></a>
                                                </div>
                                                <div class="item-info">
                                                    <div class="item-title text-ellipsis"><a
                                                            href="{{ route('card.detail', $package->id) }}"
                                                            onclick="window.location=this.href; return false;">{{ $package->package_name ?? 'Nama package' }}"
                                                        </a></div>
                                                    <div class="item-author text-sm text-ellipsis"><a
                                                            href="{{ route('card.detail', $package->id) }}"
                                                            onclick="window.location=this.href; return false;"
                                                            class="text-muted">{{ $package->package_name }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <script src="scripts/app.min.js"></script>

    @include('partials.image-url-handler')
    @endsection
