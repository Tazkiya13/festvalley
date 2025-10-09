@extends('layouts.app')
@section('title', 'My Profile')

@section('content')
<div class="container mt-4">
    <div class="content">
        <div class="padding">
            <!-- Profile Header -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fa fa-user-circle mr-2"></i>My Profile
                    </h4>
                    <div>
                        @if(Auth::user()->hasRole('Artist'))
                            <a href="{{ route('profile.index') }}" class="btn btn-primary btn-sm mr-2">
                                <i class="fa fa-user-tie mr-1"></i>Artist Profile
                            </a>
                        @endif
                    </div>
                </div>
            <div class="card-body">
                <!-- User Avatar and Basic Info -->
                <div class="row">
                    <div class="col-md-3 text-center">
                        <div class="profile-avatar mb-3">
                            @if(Auth::user()->hasRole('Artist') && Auth::user()->profile?->profile_photo)
                                <img src="{{ str_starts_with(Auth::user()->profile->profile_photo, 'http') ? Auth::user()->profile->profile_photo : asset('storage/' . Auth::user()->profile->profile_photo) }}"
                                     alt="{{ Auth::user()->name }}"
                                     class="rounded-circle img-fluid border"
                                     style="width: 120px; height: 120px; object-fit: cover;"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            @endif
                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mx-auto" 
                                 style="width: 120px; height: 120px;@if(Auth::user()->hasRole('Artist') && Auth::user()->profile?->profile_photo) display: none !important;@endif">
                                <i class="fa fa-user text-white" style="font-size: 48px;"></i>
                            </div>
                        </div>
                        <h5 class="mb-1">{{ Auth::user()->name }}</h5>
                        <p class="text-muted">
                            @foreach(Auth::user()->roles as $role)
                                <span class="badge badge-primary">{{ $role->name }}</span>
                            @endforeach
                        </p>
                    </div>
                    
                    <div class="col-md-9">
                        <!-- User Information -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-group mb-3">
                                    <label class="font-weight-bold text-muted">Email Address</label>
                                    <div class="d-flex align-items-center">
                                        <span>{{ Auth::user()->email }}</span>
                                        @if(Auth::user()->email_verified_at)
                                            <span class="badge badge-success ml-2">Verified</span>
                                        @else
                                            <span class="badge badge-warning ml-2">Unverified</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="info-group mb-3">
                                    <label class="font-weight-bold text-muted">Member Since</label>
                                    <p>{{ Auth::user()->created_at->format('F j, Y') }}</p>
                                </div>
                                
                                <div class="info-group mb-3">
                                    <label class="font-weight-bold text-muted">Last Login</label>
                                    <p>{{ Auth::user()->updated_at->format('F j, Y g:i A') }}</p>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                @if(Auth::user()->hasRole('Artist') && Auth::user()->profile)
                                    @if(Auth::user()->profile->phone)
                                        <div class="info-group mb-3">
                                            <label class="font-weight-bold text-muted">Phone</label>
                                            <p>{{ Auth::user()->profile->phone }}</p>
                                        </div>
                                    @endif
                                    
                                    @if(Auth::user()->profile->city || Auth::user()->profile->region)
                                        <div class="info-group mb-3">
                                            <label class="font-weight-bold text-muted">Location</label>
                                            <p>
                                                @if(Auth::user()->profile->city){{ Auth::user()->profile->city }}@endif
                                                @if(Auth::user()->profile->city && Auth::user()->profile->region), @endif
                                                @if(Auth::user()->profile->region){{ Auth::user()->profile->region }}@endif
                                            </p>
                                        </div>
                                    @endif
                                    
                                    @if(Auth::user()->profile->bio)
                                        <div class="info-group mb-3">
                                            <label class="font-weight-bold text-muted">Bio</label>
                                            <p>{{ Auth::user()->profile->bio }}</p>
                                        </div>
                                    @endif
                                @elseif(Auth::user()->hasRole('Admin'))
                                    <div class="info-group mb-3">
                                        <label class="font-weight-bold text-muted">Role</label>
                                        <p>System Administrator</p>
                                    </div>
                                    
                                    <div class="info-group mb-3">
                                        <label class="font-weight-bold text-muted">Permissions</label>
                                        <p>Full system access and management</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


<style>
.info-group label {
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
}

.info-group p {
    margin-bottom: 0;
    color: #495057;
}

.action-item {
    border-bottom: 1px solid #e9ecef;
    padding-bottom: 1rem;
}

.action-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.profile-avatar {
    position: relative;
}

.badge {
    font-size: 0.75rem;
}
</style>
    </div>
</div>
@endsection
