@extends('admin.home')
@section('title', 'Artist Profile Detail - Admin')

@section('content')
    <div class="content">
        <div class="padding">

            <!-- Profile Header -->
            <div class="card mb-4">
                <div class="card-body">
                    <!-- Cover Photo Background -->
                    <div class="position-relative mb-4"
                        style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px; overflow: hidden; margin-bottom: 30px;">
                        {{-- Cover Photo --}}
                        @if ($profile->cover_photo)
                            <img src="{{ asset('storage/' . $profile->cover_photo) }}" alt="Cover Photo" class="w-100 h-100"
                                style="object-fit: cover;">
                        @endif
                        <div class="position-absolute" style="bottom: -50px; left: 30px;">
                            @if ($profile->profile_photo)
                                <img src="{{ asset('storage/' . $profile->profile_photo) }}"
                                    alt="{{ $profile->stage_name }}" class="rounded-circle border border-white"
                                    style="width: 120px; height: 120px; object-fit: cover; border-width: 4px !important;">
                            @else
                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center border border-white"
                                    style="width: 120px; height: 120px; border-width: 4px !important;">
                                    <i class="material-icons text-white" style="font-size: 48px;">person</i>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Profile Info -->
                    <div class="row mt-5 pt-3">
                        <div class="col-md-8">
                            <h2 class="mb-1">{{ $profile->stage_name }}</h2>
                            @if ($profile->real_name)
                                <p class="text-muted mb-2">{{ $profile->real_name }}</p>
                            @endif

                            <!-- User Info -->
                            <div class="mb-3">
                                <strong>Email:</strong>
                                <a href="mailto:{{ $profile->user->email }}"
                                    class="text-info">{{ $profile->user->email }}</a>
                                <br>
                                <strong>User ID:</strong> {{ $profile->user_id }}
                                <br>
                                <strong>Account Created:</strong> {{ $profile->user->created_at->format('M d, Y H:i') }}
                            </div>

                            <div class="mb-3">
                                @if ($profile->genre)
                                    <span class="badge badge-primary mr-2">{{ $profile->genre }}</span>
                                @endif
                            </div>

                            @if ($profile->bio)
                                <p class="text-muted">{{ $profile->bio }}</p>
                            @endif
                        </div>

                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Quick Actions</h6>
                                    <div class="btn-group-vertical w-100" role="group">
                                        <a href="{{ route('profile.admin.index') }}" class="btn btn-secondary btn-sm mb-2">
                                            <i class="material-icons mr-1">arrow_back</i> Back to List
                                        </a>
                                        <a href="{{ route('profile.admin.edit', $profile->id) }}" class="btn btn-warning btn-sm mb-2">
                                            <i class="material-icons mr-1">edit</i> Edit Profile
                                        </a>
                                        {{-- <a href="mailto:{{ $profile->user->email }}" class="btn btn-outline-primary btn-sm mb-2">
                                            <i class="material-icons mr-1">email</i> Send Email
                                        </a> --}}
                                        {{-- @if($profile->phone)
                                            <a href="tel:{{ $profile->phone }}" class="btn btn-outline-info btn-sm">
                                                <i class="material-icons mr-1">phone</i> Call Artist
                                            </a>
                                        @endif --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Details -->
            <div class="row">
                <!-- Contact & Location Info -->
                <div class="col-lg-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="material-icons mr-2">location_on</i>Location & Contact</h5>
                        </div>
                        <div class="card-body">
                            @if ($profile->address || $profile->city || $profile->province)
                                <div class="mb-3">
                                    <strong>Address:</strong>
                                    <p class="mb-1">
                                        @if ($profile->address)
                                            {{ $profile->address }}
                                        @endif
                                        @if ($profile->city || $profile->province)
                                            <br>{{ collect([$profile->city, $profile->province])->filter()->implode(', ') }}
                                        @endif
                                        @if ($profile->postal_code)
                                            {{ $profile->postal_code }}
                                        @endif
                                    </p>
                                </div>
                            @endif

                            @if ($profile->phone)
                                <div class="mb-3">
                                    <strong>Phone:</strong>
                                    <p class="mb-1">
                                        <a href="tel:{{ $profile->phone }}"
                                            class="text-decoration-none">{{ $profile->phone }}</a>
                                    </p>
                                </div>
                            @endif

                            @if ($profile->maps_link)
                                <div class="mb-3">
                                    <a href="{{ $profile->maps_link }}" target="_blank"
                                        class="btn btn-outline-primary btn-sm">
                                        <i class="material-icons">map</i> View on Maps
                                    </a>
                                </div>
                            @endif

                            @if ($profile->social_media)
                                <div>
                                    <strong>Social Media:</strong>
                                    <div class="mt-2">
                                        @foreach ($profile->social_media as $platform => $url)
                                            @if ($url)
                                                <a href="{{ $url }}" target="_blank"
                                                    class="btn btn-outline-info btn-sm mr-2 mb-2">
                                                    <i
                                                        class="material-icons">{{ $platform == 'instagram' ? 'camera_alt' : ($platform == 'facebook' ? 'facebook' : ($platform == 'youtube' ? 'video_library' : 'link')) }}</i>
                                                    {{ ucfirst($platform) }}
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Professional Info -->
                <div class="col-lg-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="material-icons mr-2">work</i>Professional Information</h5>
                        </div>
                        <div class="card-body">
                            @if ($profile->years_experience)
                                <div class="mb-3">
                                    <strong>Years of Experience:</strong>
                                    <p class="mb-1">{{ $profile->years_experience }} years</p>
                                </div>
                            @endif

                            @if ($profile->languages)
                                <div class="mb-3">
                                    <strong>Languages:</strong>
                                    <div class="mt-2">
                                        @foreach ($profile->languages as $language)
                                            <span class="badge badge-secondary mr-1 mb-1">{{ $language }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if ($profile->equipment_owned)
                                <div class="mb-3">
                                    <strong>Equipment Owned:</strong>
                                    <div class="mt-2">
                                        @foreach ($profile->equipment_owned as $equipment)
                                            <span class="badge badge-info mr-1 mb-1">{{ $equipment }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Admin Actions -->
            {{-- <div class="col-lg-6">
            <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="material-icons mr-2">admin_panel_settings</i>Admin Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Quick Actions</h6>
                        <div class="btn-group-vertical w-100" role="group">
                            <a href="{{ route('artists.edit', $profile->id) }}" class="btn btn-warning mb-2">
                                <i class="material-icons">edit</i> Edit Profile
                            </a>
                            <a href="mailto:{{ $profile->user->email }}" class="btn btn-primary mb-2">
                                <i class="material-icons">email</i> Send Email
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        </div>
    </div>

    <style>
        .badge {
            font-size: 0.75rem;
        }

        /* Custom badge colors for Bootstrap 3-4 compatibility */
        .badge.bg-success {
            background-color: #28a745 !important;
            color: #fff !important;
        }

        .badge.bg-danger {
            background-color: #dc3545 !important;
            color: #fff !important;
        }

        .badge.badge-success {
            background-color: #28a745 !important;
            color: #fff !important;
        }

        .badge.badge-danger {
            background-color: #dc3545 !important;
            color: #fff !important;
        }

        .card {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border: none;
            border-radius: 10px;
        }

        .card-header {
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            border-radius: 10px 10px 0 0 !important;
        }

        .btn {
            border-radius: 6px;
        }

        @media (max-width: 768px) {
            .position-absolute {
                position: static !important;
                text-align: center;
                margin-top: -60px;
            }

            .mt-5.pt-3 {
                margin-top: 2rem !important;
                padding-top: 1rem !important;
            }

            .navbar.row {
                flex-direction: column;
            }

            .navbar-nav {
                margin-top: 1rem;
            }
        }
    </style>
@endsection
