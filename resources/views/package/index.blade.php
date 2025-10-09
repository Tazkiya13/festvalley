@extends('admin.home')
@section('title', 'Daftar package Artis')
@section('content')
        <div class="main-content" style="padding: 20px;">
            <div style="margin-bottom: 20px;">
                <!-- Button trigger modal -->
                <a href="{{ route('packages.create') }}" type="button" class="btn btn-primary">
                    Create package
                </a>
            </div>

            <!-- Desktop Table View -->
            <div class="desktop-table-container">
                <table class="table table-striped table-hover table-bordered desktop-table">
                    <thead class="thead-dark">
                        <tr>
                            <th>No</th>
                            <th>Main Image</th>
                            <th>Media</th>
                            <th>Artist Name</th>
                            <th>Available Dates</th>
                            <th>Package Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($packages as $package)
                            <tr>
                                <td>{{(($packages->currentPage() - 1) * $packages->perPage() + $loop->index + 1) }}</td>
                                <td>
                                    @if($package->image)
                                        <img src="{{ asset('storage/' . $package->image) }}" 
                                             alt="Package image" 
                                             width="100" height="75"
                                             style="object-fit: cover; border-radius: 8px;">
                                    @else
                                        <img src="{{ asset('images/default.jpg') }}" 
                                             alt="Default image" 
                                             width="100" height="75"
                                             style="object-fit: cover; border-radius: 8px;">
                                    @endif
                                </td>
                                <td>
                                    <div class="media-preview">
                                        @if($package->photos && count($package->photos) > 0)
                                            <div class="mb-2">
                                                <small class="text-muted d-block">Photos ({{ count($package->photos) }})</small>
                                                <div class="d-flex gap-1">
                                                    @foreach(array_slice($package->photos, 0, 3) as $photo)
                                                        <img src="{{ asset('storage/' . $photo) }}" 
                                                             alt="Photo" 
                                                             width="30" height="30"
                                                             style="object-fit: cover; border-radius: 4px;">
                                                    @endforeach
                                                    @if(count($package->photos) > 3)
                                                        <span class="badge badge-secondary">+{{ count($package->photos) - 3 }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                        @if($package->video)
                                            <div>
                                                <small class="text-muted d-block">Video</small>
                                                <i class="fas fa-play-circle text-primary" style="font-size: 20px;"></i>
                                            </div>
                                        @endif
                                        @if(!$package->photos && !$package->video)
                                            <small class="text-muted">No additional media</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if ($package->user)
                                        {{ $package->user->name }}
                                    @else
                                        No artist
                                    @endif
                                </td>
                                <td>
                                    <div style="padding: 8px;">
                                        <div class="input-group date-picker-group mb-2">
                                            <input type="text" class="form-control flatpickr-package modern-date-field"
                                                style="border-radius: 0 8px 8px 0; background: #f8f9fa;"
                                                id="flatpickr-package-{{ $package->id }}"
                                                data-package-id="{{ $package->id }}"
                                                data-available-dates='@json($package->availableDates->pluck("tanggal")->map(fn($d) => \Carbon\Carbon::parse($d)->format("Y-m-d")))'
                                                placeholder="See available dates">
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $package->package_name }}</td>
                                <td style="max-width: 500px;">{{ $package->description }}</td>
                                <td>{{ euro($package->price) }}</td>
                                <td>
                                    <a href="{{ route('packages.edit', $package->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('packages.destroy', $package->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin hapus package ini?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada package tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="mobile-cards">
                @forelse($packages as $package)
                    <div class="mobile-card">
                        <div class="mobile-card-header">
                            <span>package #{{(($packages->currentPage() - 1) * $packages->perPage() + $loop->index + 1) }}</span>
                            <span class="mobile-price-badge">{{ euro($package->price) }}</span>
                        </div>

                        <div class="mobile-card-body">
                            <!-- Image -->
                            <div class="mobile-card-row">
                                <div class="mobile-card-label">Image:</div>
                                <div class="mobile-card-value">
                                    <img src="{{ asset('images/default.jpg') }}" 
                                         data-image="{{ $package->image }}" 
                                         alt="Package image" 
                                         class="mobile-card-image">
                                </div>
                            </div>

                            <!-- Nama Artis -->
                            <div class="mobile-card-row">
                                <div class="mobile-card-label">Nama Artis:</div>
                                <div class="mobile-card-value">
                                    @if ($package->user)
                                        {{ $package->user->name }}
                                    @else
                                        <span class="text-muted">Tidak ada artis</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Nama package -->
                            <div class="mobile-card-row">
                                <div class="mobile-card-label">Nama package:</div>
                                <div class="mobile-card-value">{{ $package->package_name }}</div>
                            </div>

                            <!-- Deskripsi -->
                            <div class="mobile-card-row">
                                <div class="mobile-card-label">Deskripsi:</div>
                                <div class="mobile-card-value" style="text-align: left; margin-left: 10px;">
                                    <small>{{ $package->description }}</small>
                                </div>
                            </div>

                            <!-- Tanggal Tersedia -->
                            <div class="mobile-card-row">
                                <div class="mobile-card-label">Tanggal:</div>
                                <div class="mobile-card-value">
                                    <input type="text" class="mobile-date-input flatpickr-package"
                                        id="flatpickr-package-mobile-{{ $package->id }}"
                                        data-package-id="{{ $package->id }}"
                                        data-available-dates='@json($package->availableDates->pluck("tanggal")->map(fn($d) => \Carbon\Carbon::parse($d)->format("Y-m-d")))'
                                        placeholder="See available dates">
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="mobile-card-actions">
                                <a href="{{ route('packages.edit', $package->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('packages.destroy', $package->id) }}" method="POST" style="flex: 1;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm w-100"
                                        onclick="return confirm('Yakin hapus package ini?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <h5>Tidak ada package tersedia</h5>
                        <p>Silakan tambah package baru dengan klik tombol "Create package" di atas.</p>
                    </div>
                @endforelse
            </div>
        </div>

    <div class="paginate">
        {{ $packages->links('pagination::bootstrap-4') }}
    </div>

@include('partials.image-url-handler')
@endsection
{{-- flatpickr menggunakan flatpicker-packages.js --}}
