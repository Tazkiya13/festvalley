@extends('admin.home')
@section('title', 'Artist Profile')

@section('content')
<div class="content">
    <div class="padding">
        <!-- Profile Header -->
        <div class="card mb-4">
            <div class="card-body">
                <!-- Cover Photo Background -->
                <div class="position-relative mb-4" style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px; overflow: hidden; margin-bottom: 30px;">
                    @if($profile?->cover_photo)
                        <img src="{{ str_starts_with($profile->cover_photo, 'http') ? $profile->cover_photo : asset('storage/' . $profile->cover_photo) }}"
                             alt="Cover Photo"
                             class="w-100 h-100"
                             style="object-fit: cover;"
                             onerror="this.style.display='none';">
                    @endif
                    <div class="position-absolute" style="bottom: -50px; left: 30px;">
                        @if($profile?->profile_photo)
                            <img src="{{ str_starts_with($profile->profile_photo, 'http') ? $profile->profile_photo : asset('storage/' . $profile->profile_photo) }}"
                                 alt="{{ $profile->stage_name ?? 'Artist' }}"
                                 class="rounded-circle border border-white"
                                 style="width: 120px; height: 120px; object-fit: cover; border-width: 4px !important;"
                                 onerror="this.src=''; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="rounded-circle bg-secondary align-items-center justify-content-center border border-white"
                                 style="width: 120px; height: 120px; border-width: 4px !important; display: none; position: absolute; top: 0; left: 0;">
                                <i class="material-icons text-white" style="font-size: 48px;">person</i>
                            </div>
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
                        @if($profile)
                            <h2 class="mb-1">{{ $profile->stage_name }}</h2>
                            @if($profile->real_name)
                                <p class="text-muted mb-2">{{ $profile->real_name }}</p>
                            @endif

                            <div class="mb-3">
                                @if($profile->genre)
                                    <span class="badge badge-primary mr-2 text-black">{{ $profile->genre }}</span>
                                @endif
                            </div>

                            @if($profile->bio)
                                <p class="text-muted">{{ $profile->bio }}</p>
                            @endif
                        @else
                            <h2 class="mb-1">{{ Auth::user()->name }}</h2>
                            <p class="text-muted mb-2">Welcome to your Artist Dashboard!</p>
                            <div class="mb-3">
                                <span class="badge bg-warning text-dark" style="padding: 5px; border-radius: 5px;">
                                    Profile Not Created
                                </span>
                            </div>
                            <p class="text-muted">Create your artist profile to start getting booked by customers and showcase your musical talents.</p>
                        @endif
                    </div>

                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">Quick Actions</h6>
                                <div class="btn-group-vertical w-100" role="group">
                                    @if(Auth::user()->hasRole('Admin'))
                                        <a href="{{ route('profile.admin.index') }}" class="btn btn-secondary btn-sm mb-2">
                                            <i class="material-icons mr-1">arrow_back</i> Back to List
                                        </a>
                                    @endif
                                    
                                    <a href="{{ route('profile.artist.edit') }}" class="btn btn-warning btn-sm mb-2">
                                        <i class="material-icons mr-1">edit</i> Edit Profile
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($profile)
            <!-- Profile Details -->
            <div class="row">
                <!-- Contact & Location Info -->
                <div class="col-lg-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="material-icons mr-2">location_on</i>Location & Contact</h5>
                        </div>
                        <div class="card-body">
                            @if($profile->address || $profile->city || $profile->province)
                                <div class="mb-3">
                                    <strong>Address:</strong>
                                    <p class="mb-1">
                                        {{ $profile->address }}
                                        @if($profile->city || $profile->province)
                                            <br>{{ $profile->city }}@if($profile->city && $profile->province), @endif{{ $profile->province }} {{ $profile->postal_code }}
                                        @endif
                                    </p>
                                </div>
                            @endif

                            @if($profile->phone)
                                <div class="mb-3">
                                    <strong>Phone:</strong>
                                    <p class="mb-1">{{ $profile->phone }}</p>
                                </div>
                            @endif

                            @if($profile->maps_link)
                                <div class="mb-3">
                                    <strong>Location:</strong>
                                    <a href="{{ $profile->maps_link }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                        <i class="material-icons mr-1">map</i> View on Maps
                                    </a>
                                </div>
                            @endif

                            @if($profile->social_media)
                                <div class="mb-3">
                                    <strong>Social Media:</strong>
                                    <div class="mt-2">
                                        @foreach($profile->social_media as $platform => $url)
                                            @if($url)
                                                <a href="{{ $url }}" target="_blank" class="btn btn-outline-secondary btn-sm mr-1 mb-1">
                                                    <i class="fab fa-{{ $platform }}"></i> {{ ucfirst($platform) }}
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
                            @if($profile->years_experience)
                                <div class="mb-3">
                                    <strong>Years of Experience:</strong>
                                    <p class="mb-1">{{ $profile->years_experience }} years</p>
                                </div>
                            @endif

                            <div class="mb-3">
                                <strong>Languages:</strong>
                                @if($profile->languages)
                                    <div class="mt-2">
                                        @foreach($profile->languages as $language)
                                            <span class="badge bg-secondary me-1 mb-1">{{ $language }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted mt-1">Not specified</p>
                                @endif
                            </div>

                            <div class="mb-3">
                                <strong>Equipment Owned:</strong>
                                @if($profile->equipment_owned)
                                    <div class="mt-2">
                                        @foreach($profile->equipment_owned as $equipment)
                                            <span class="badge bg-info me-1 mb-1">{{ $equipment }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted mt-1">Not specified</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Getting Started Guide -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="material-icons mr-2">info</i>Get Started with Your Artist Profile</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <p class="lead">Create your artist profile to unlock all the features of the platform:</p>
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="material-icons text-primary mr-2">check_circle</i> Showcase your musical talents and experience</li>
                                        <li class="mb-2"><i class="material-icons text-primary mr-2">check_circle</i> Let customers discover and book your services</li>
                                        <li class="mb-2"><i class="material-icons text-primary mr-2">check_circle</i> Upload photos and portfolio</li>
                                        <li class="mb-2"><i class="material-icons text-primary mr-2">check_circle</i> Manage your availability and pricing</li>
                                        <li class="mb-2"><i class="material-icons text-primary mr-2">check_circle</i> Connect with potential clients</li>
                                    </ul>
                                    <div>
                                        <a href="{{ route('profile.artist.edit') }}" class="btn btn-primary btn-lg">
                                            <i class="material-icons mr-2">add</i> Edit My Profile
                                        </a>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
.badge {
    font-size: 0.75rem;
}

.card {
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
}
</style>

<script>
// Image error handling for better user experience
document.addEventListener('DOMContentLoaded', function() {
    // Handle image loading errors
    const images = document.querySelectorAll('img[onerror]');
    images.forEach(img => {
        img.addEventListener('error', function() {
            console.log('Image failed to load:', this.src);
        });
    });
});
</script>
@endsection
