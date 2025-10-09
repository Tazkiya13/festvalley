@extends('admin.home')
@section('title', 'All Artist Profiles - Admin')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/responsif-tabel.css') }}">
@endpush

@section('content')
<div class="content">
    <div class="padding">
        <!-- Header Section -->
        <div class="card mb-4 header-card shadow-sm">
            <div class="card-body">
                <!-- Title -->
                <div class="row align-items-center mb-4 hero-section" style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px; overflow: hidden; padding: 20px; margin-bottom: 20px; position: relative;">
                    <div class="col-lg-6 col-12 mb-3 mb-lg-0" style="position: relative; z-index: 2;">
                        <h2 class="text-white mb-2 d-flex align-items-center">
                            <i class="material-icons mr-2" style="font-size: 40px;">people</i>
                            All Artist Profiles
                        </h2>
                        <p class="text-white mb-0">Manage and view all artist profiles</p>
                    </div>
                    <div class="col-lg-6 col-12 d-flex justify-content-end" style="position: relative; z-index: 2;">
                        <a href="{{ route('profile.admin.create') }}" class="btn btn-light btn-lg">
                            <i class="material-icons mr-2">add</i>
                            Create New Artist
                        </a>
                    </div>
                </div>

                <!-- Search Form -->
                <form class="search-form" method="GET" action="{{ route('profile.admin.index') }}">
                    <div class="row">
                        <div class="col-lg-8 col-md-7 col-12 mb-3 mb-md-0">
                            <div class="input-group input-group-lg">
                                {{-- <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0">
                                        <i class="material-icons text-muted">search</i>
                                    </span>
                                </div> --}}
                                <input class="form-control border-left-0" type="search" name="search"
                                       placeholder="Search by artist name, real name..."
                                       value="{{ request('search') }}" aria-label="Search">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-5 col-12">
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary btn-lg flex-fill" type="submit">
                                    <i class="material-icons mr-1">search</i>
                                    Search
                                </button>
                                <a href="{{ route('profile.admin.index') }}" class="btn btn-outline-secondary btn-lg text-center" style="min-width: 100px; display: flex; align-items: center; justify-content: center;">
                                    <i class="material-icons mr-1">clear</i>
                                    Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Artists Grid/Table -->
                @if($profiles->count() > 0)
                    <!-- Desktop Table -->
                    <div class="desktop-table-container">
                        <table class="table table-striped table-hover table-bordered desktop-table">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Artist</th>
                                    <th>Genre</th>
                                    <th>Location</th>
                                    <th>Experience</th>
                                    <th>Joined</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($profiles as $profile)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($profile->profile_photo)
                                                    <img src="{{ asset('storage/' . $profile->profile_photo) }}"
                                                         alt="{{ $profile->stage_name }}"
                                                         width="70" height="70"
                                                         style="object-fit: cover; border-radius: 20%; margin-right: 10px;">
                                                @else
                                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mr-3"
                                                         style="width: 40px; height: 40px;">
                                                        <i class="material-icons text-white">person</i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <strong>{{ $profile->stage_name }}</strong>
                                                    @if($profile->real_name)
                                                        <br><small class="text-muted">{{ $profile->real_name }}</small>
                                                    @endif
                                                    <br><small class="text-info">{{ $profile->user->email ?? 'N/A' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($profile->genre)
                                                <span class="badge badge-primary" style="background-color: #007bff; color: white;">{{ $profile->genre }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($profile->city || $profile->province)
                                                {{ collect([$profile->city, $profile->province])->filter()->implode(', ') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $profile->years_experience ? $profile->years_experience . ' years' : '-' }}
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $profile->created_at->format('M d, Y') }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('profile.admin.show', $profile->id) }}"
                                                   class="btn btn-sm btn-outline-primary" title="View Profile">
                                                    <i class="material-icons">visibility</i>
                                                </a>
                                                <a href="{{ route('profile.admin.edit', $profile->id) }}"
                                                   class="btn btn-sm btn-outline-warning" title="Edit Profile">
                                                    <i class="material-icons">edit</i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Cards -->
                    <div class="mobile-cards">
                        @foreach($profiles as $profile)
                            <div class="mobile-card">
                                <div class="mobile-card-header">
                                    <h6>{{ $profile->stage_name }}</h6>
                                </div>
                                <div class="mobile-card-body">
                                    <div class="mobile-card-row">
                                        <span class="mobile-card-label">Photo</span>
                                        <div class="mobile-card-value text-left">
                                            @if($profile->profile_photo)
                                                <img src="{{ asset('storage/' . $profile->profile_photo) }}"
                                                     alt="{{ $profile->stage_name }}"
                                                     class="mobile-card-image">
                                            @else
                                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center"
                                                     style="width: 60px; height: 60px;">
                                                    <i class="material-icons text-white">person</i>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    @if($profile->real_name)
                                        <div class="mobile-card-row">
                                            <span class="mobile-card-label">Real Name</span>
                                            <span class="mobile-card-value">{{ $profile->real_name }}</span>
                                        </div>
                                    @endif

                                    <div class="mobile-card-row">
                                        <span class="mobile-card-label">Email</span>
                                        <span class="mobile-card-value">{{ $profile->user->email ?? 'N/A' }}</span>
                                    </div>

                                    @if($profile->genre)
                                        <div class="mobile-card-row">
                                            <span class="mobile-card-label">Genre</span>
                                            <span class="mobile-card-value">
                                                <span class="mobile-status-badge" style="background: #007bff; color: white; padding: 4px 8px; border-radius: 4px; font-weight: 500;">{{ $profile->genre }}</span>
                                            </span>
                                        </div>
                                    @endif

                                    @if($profile->city || $profile->province)
                                        <div class="mobile-card-row">
                                            <span class="mobile-card-label">Location</span>
                                            <span class="mobile-card-value">{{ collect([$profile->city, $profile->province])->filter()->implode(', ') }}</span>
                                        </div>
                                    @endif

                                    @if($profile->years_experience)
                                        <div class="mobile-card-row">
                                            <span class="mobile-card-label">Experience</span>
                                            <span class="mobile-card-value">{{ $profile->years_experience }} years</span>
                                        </div>
                                    @endif

                                    <div class="mobile-card-row">
                                        <span class="mobile-card-label">Joined</span>
                                        <span class="mobile-card-value">{{ $profile->created_at->format('M d, Y') }}</span>
                                    </div>

                                    <div class="mobile-card-actions">
                                        <a href="{{ route('profile.admin.show', $profile->id) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="material-icons small mr-1">visibility</i>
                                            View
                                        </a>
                                        <a href="{{ route('artists.edit', $profile->id) }}" class="btn btn-outline-warning btn-sm">
                                            <i class="material-icons small mr-1">edit</i>
                                            Edit
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $profiles->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="empty-state">
                        <i class="material-icons">search_off</i>
                        <h5>No Artist Profiles Found</h5>
                        @if(request('search'))
                            <p>Try adjusting your search criteria</p>
                            <a href="{{ route('profile.admin.index') }}" class="btn btn-primary">Clear Search</a>
                        @else
                            <p>No artists have created profiles yet</p>
                        @endif
                    </div>
                @endif

        <!-- Summary Stats -->
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-primary">{{ $totalProfiles }}</h3>
                        <p class="text-muted mb-0">Total Artists</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-info">{{ $topGenre->genre ?? 'N/A' }}</h3>
                        <p class="text-muted mb-0">Top Genre</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-warning">{{ $avgExperience }}y</h3>
                        <p class="text-muted mb-0">Avg Experience</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border: none;
    border-radius: 10px;
}

.btn {
    border-radius: 6px;
}

.badge {
    font-size: 0.75rem;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #fff !important; /* Force white text for table headers */
}

.table thead.thead-dark th {
    color: #fff !important; /* Ensure thead-dark has white text */
}

.header-card {
    border: none;
    background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image:
        radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 2px, transparent 2px),
        radial-gradient(circle at 75% 75%, rgba(255,255,255,0.1) 2px, transparent 2px),
        radial-gradient(circle at 25% 75%, rgba(255,255,255,0.05) 1px, transparent 1px),
        radial-gradient(circle at 75% 25%, rgba(255,255,255,0.05) 1px, transparent 1px);
    background-size: 50px 50px, 60px 60px, 30px 30px, 40px 40px;
    animation: heroPatternMove 20s linear infinite;
    z-index: 1;
    pointer-events: none;
    border-radius: 10px;
}

@keyframes heroPatternMove {
    0% { transform: translateX(0) translateY(0); }
    100% { transform: translateX(50px) translateY(50px); }
}

.stat-item-header {
    background: rgba(255, 255, 255, 0.1);
    padding: 0.75rem 0.5rem;
    border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
    backdrop-filter: blur(5px);
}

.stat-item-header:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.text-white-50 {
    color: rgba(255, 255, 255, 0.7) !important;
}

.stat-item {
    background: rgba(255, 255, 255, 0.8);
    padding: 0.75rem;
    border-radius: 8px;
    border: 1px solid rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.stat-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.input-group-lg .input-group-text {
    padding: 0.75rem 1rem;
}

.input-group-lg .form-control {
    padding: 0.75rem 1rem;
}

.btn-lg {
    padding: 0.75rem 1.5rem;
}

.gap-2 {
    gap: 0.5rem;
}

.material-icons.small {
    font-size: 16px;
    vertical-align: middle;
}

@media (max-width: 768px) {
    .navbar.row {
        flex-direction: column;
    }

    .navbar-nav {
        margin-top: 1rem;
    }

    .stat-item {
        margin-bottom: 0.5rem;
    }

    .stat-item h4 {
        font-size: 1.1rem;
    }

    .stat-item small {
        font-size: 0.75rem;
    }

    .gap-2 {
        gap: 0.25rem;
    }
}
</style>
@endsection
