<!DOCTYPE html>
<html lang="en">
<head>
  @include('head')
  <link rel="stylesheet" href="{{ asset('css/discover-page.css') }}">
  <link rel="stylesheet" href="{{ asset('css/browse-page.css') }}">
  @if (file_exists(public_path('build/manifest.json')) || is_dir(public_path('hot')))
  @vite(['resources/js/app.js'])
  @endif
</head>

<body>
  <div class="app dk" id="app">
    <div id="content" class="app-content" role="main">
      @include('chat-widget-customer')
      @include('landingpage.header')
      @include('partials.toastr')

      <!-- Page Header -->
      <section class="page-header">
        <div class="container">
          <div class="row">
            <div class="col-12 text-center">
              <h1>Browse All Packages</h1>
              <p class="lead">Discover amazing artists and book your perfect music package</p>
            </div>
          </div>
        </div>
      </section>

      <!-- Enhanced Search Section -->
      <section class="search-section">
        <div class="container">
          <form action="{{ route('packages.search') }}" method="GET" class="search-form">
            <div class="search-card">
              <div class="search-card-body">
                <div class="search-header">
                  <h3 class="search-title">
                    <i class="fas fa-search search-icon"></i>
                    Find Your Perfect Artist
                  </h3>
                  <p class="search-subtitle">Search and filter through our amazing collection of music packages</p>
                </div>
                
                <!-- Search Form Fields -->
                <div class="search-fields">
                  <!-- First Row -->
                  <div class="search-row">
                    <div class="search-field search-field-large">
                      <label class="search-label">Artist & Package Search</label>
                      <input 
                        type="text" 
                        name="search" 
                        class="search-input" 
                        placeholder="Search by artist name, stage name, or package name" 
                        value="{{ request('search') ?: (request('artist') ? request('artist') : (request('package') ? request('package') : '')) }}"
                      >
                    </div>
                    <div class="search-field">
                      <label class="search-label">Available Date</label>
                      <input type="date" name="tanggal" class="search-input search-date" value="{{ request('tanggal') }}">
                    </div>
                  </div>
                  
                  <!-- Second Row -->
                  <div class="search-row">
                    <div class="search-field">
                      <label class="search-label">Country</label>
                      <select name="country" id="countrySelect" class="search-select">
                        <option value="">All Countries</option>
                        @foreach(\App\Helpers\Countries::getCountries() as $code => $name)
                          <option value="{{ $code }}" {{ request('country') == $code ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="search-field">
                      <label class="search-label">Province/State</label>
                      <select name="province" id="provinceSelect" class="search-select">
                        <option value="">All Provinces</option>
                        <!-- Dynamically populated based on country selection -->
                      </select>
                    </div>
                    <div class="search-field">
                      <label class="search-label">Music Genre</label>
                      <select name="category" class="search-select">
                        <option value="">All Genres</option>
                        @if(isset($genres))
                          @foreach($genres as $genre)
                            <option value="{{ $genre }}" {{ request('category') == $genre ? 'selected' : '' }}>{{ $genre }}</option>
                          @endforeach
                        @endif
                      </select>
                    </div>
                  </div>
                  
                  <!-- Action Buttons -->
                  <div class="search-actions">
                    <button type="submit" class="btn-search-primary">
                      <i class="fas fa-search btn-icon"></i>Search Packages
                    </button>
                    <a href="{{ route('packages.browse') }}" class="btn-search-secondary">
                      <i class="fas fa-times btn-icon"></i>Clear Filters
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </section>

      <!-- Packages Grid -->
      <section class="packages-grid py-5">
        <div class="container">
          @if($packages->count() > 0)
            <!-- Active Filters Display -->
            @if(request()->hasAny(['search', 'artist', 'package', 'country', 'province', 'location', 'category', 'tanggal']))
              <div class="active-filters">
                <div class="filters-header">
                  <i class="fas fa-filter filter-icon"></i>
                  <span class="filters-title">Active Filters:</span>
                </div>
                <div class="filters-list">
                  @if(request('search'))
                    <span class="filter-badge">
                      <i class="fas fa-search filter-badge-icon"></i>
                      Search: {{ request('search') }}
                      <button type="button" class="remove-filter" onclick="removeFilter('search')">
                        <i class="fas fa-times"></i>
                      </button>
                    </span>
                  @endif
                  @if(request('artist'))
                    <span class="filter-badge">
                      <i class="fas fa-user filter-badge-icon"></i>
                      Artist: {{ request('artist') }}
                      <button type="button" class="remove-filter" onclick="removeFilter('artist')">
                        <i class="fas fa-times"></i>
                      </button>
                    </span>
                  @endif
                  @if(request('package'))
                    <span class="filter-badge">
                      <i class="fas fa-box filter-badge-icon"></i>
                      Package: {{ request('package') }}
                      <button type="button" class="remove-filter" onclick="removeFilter('package')">
                        <i class="fas fa-times"></i>
                      </button>
                    </span>
                  @endif
                  @if(request('country'))
                    <span class="filter-badge">
                      <i class="fas fa-globe filter-badge-icon"></i>
                      Country: {{ request('country') }}
                      <button type="button" class="remove-filter" onclick="removeFilter('country')">
                        <i class="fas fa-times"></i>
                      </button>
                    </span>
                  @endif
                  @if(request('province'))
                    <span class="filter-badge">
                      <i class="fas fa-map-marker-alt filter-badge-icon"></i>
                      Province: {{ request('province') }}
                      <button type="button" class="remove-filter" onclick="removeFilter('province')">
                        <i class="fas fa-times"></i>
                      </button>
                    </span>
                  @endif
                  @if(request('location'))
                    <span class="filter-badge">
                      <i class="fas fa-map-marker-alt filter-badge-icon"></i>
                      Location: {{ request('location') }}
                      <button type="button" class="remove-filter" onclick="removeFilter('location')">
                        <i class="fas fa-times"></i>
                      </button>
                    </span>
                  @endif
                  @if(request('category'))
                    <span class="filter-badge">
                      <i class="fas fa-music filter-badge-icon"></i>
                      Genre: {{ request('category') }}
                      <button type="button" class="remove-filter" onclick="removeFilter('category')">
                        <i class="fas fa-times"></i>
                      </button>
                    </span>
                  @endif
                  @if(request('tanggal'))
                    <span class="filter-badge">
                      <i class="fas fa-calendar filter-badge-icon"></i>
                      Date: {{ \Carbon\Carbon::parse(request('tanggal'))->format('M d, Y') }}
                      <button type="button" class="remove-filter" onclick="removeFilter('tanggal')">
                        <i class="fas fa-times"></i>
                      </button>
                    </span>
                  @endif
                </div>
                <a href="{{ route('packages.browse') }}" class="clear-all-filters">
                  <i class="fas fa-times-circle"></i>
                  Clear All
                </a>
              </div>
            @endif

            <!-- Results Summary -->
            <div class="row mb-4">
              <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                  <h4>Found {{ $packages->total() }} packages</h4>
                  <div class="d-flex align-items-center">
                    <small class="text-muted">
                      Showing {{ $packages->firstItem() }}-{{ $packages->lastItem() }} of {{ $packages->total() }} results
                    </small>
                  </div>
                </div>
              </div>
            </div>

            <div class="packages-grid-container">
              @foreach ($packages as $package)
                <div class="package-item">
                  <div class="package-card h-100">
                    <div class="package-image">
                      <div class="image-clickable" onclick="viewPackageDetails({{ $package->id }})" style="cursor: pointer;">
                        <img src="{{ asset('images/default.jpg') }}" data-image="{{ $package->image }}"
                             alt="{{ $package->package_name }}" class="card-img-top">
                      </div>
                      
                      <!-- Price Badge - Top Right -->
                      <div class="price-badge">
                        €{{ number_format($package->price ?? 0) }}
                      </div>
                      
                      <!-- Genre Badge - Top Left -->
                      @if($package->user && $package->user->profile && $package->user->profile->genre)
                        <div class="genre-badge">
                          {{ $package->user->profile->genre }}
                        </div>
                      @endif

                      <!-- Media Indicators - Bottom Left -->
                      <div class="media-indicators">
                        @if($package->photos && count($package->photos) > 0)
                          <div class="media-indicator photo-indicator">
                            <i class="fas fa-images"></i>
                            <span>{{ count($package->photos) }}</span>
                          </div>
                        @endif
                        @if($package->video)
                          <div class="media-indicator video-indicator">
                            <i class="fas fa-video"></i>
                          </div>
                        @endif
                      </div>
                    </div>
                    
                    <div class="card-body d-flex flex-column">
                      <!-- Package Title -->
                      <h5 class="package-title mb-2">
                        <button type="button" class="title-clickable" onclick="viewPackageDetails({{ $package->id }})">
                          {{ $package->package_name }}
                        </button>
                      </h5>
                      
                      <!-- Artist Info -->
                      <div class="artist-info mb-3">
                        <p class="artist-name mb-1">
                          <i class="fas fa-user me-1 text-muted"></i>
                          @if($package->user && $package->user->profile && $package->user->profile->stage_name)
                            {{ $package->user->profile->stage_name }}
                          @else
                            {{ $package->user->name ?? 'Unknown Artist' }}
                          @endif
                        </p>
                        
                        <!-- Location -->
                        @if($package->country || ($package->user && $package->user->profile && $package->user->profile->city))
                          <p class="location mb-0">
                            <i class="fas fa-map-marker-alt me-1 text-muted"></i>
                            @if($package->city && $package->region)
                              {{ $package->city }}, {{ $package->region }}
                            @elseif($package->country)
                              {{ $package->country }}
                            @elseif($package->user->profile && $package->user->profile->city)
                              {{ $package->user->profile->city }}
                              @if($package->user->profile->region), {{ $package->user->profile->region }}@endif
                            @endif
                          </p>
                        @endif
                      </div>
                      
                      <!-- Description -->
                      @if($package->description)
                        <div class="package-description mb-3">
                          {{ Str::limit($package->description, 120) }}
                        </div>
                      @endif
                      
                      <!-- Meta Info -->
                      @if($package->availableDates->count() > 0)
                        <div class="availability-info mb-3">
                          <small class="text-success">
                            <i class="fas fa-calendar-check me-1"></i>
                            {{ $package->availableDates->count() }} available date(s)
                          </small>
                        </div>
                      @endif
                      
                      <!-- Action Buttons -->
                      <div class="package-actions mt-auto">
                        <button class="btn btn-outline-primary btn-details" onclick="viewPackageDetails({{ $package->id }})" type="button">
                          <i class="fas fa-eye me-1"></i>Details
                        </button>
                        <a href="{{ route('booking.form', $package->id) }}" class="btn btn-primary btn-book">
                          <i class="fas fa-calendar-plus me-1"></i>Send request                        
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="row mt-4">
              <div class="col-12">
                <div class="d-flex justify-content-center">
                  {{ $packages->links('pagination::bootstrap-4') }}
                </div>
              </div>
            </div>
          @else
            <div class="row">
              <div class="col-12">
                <div class="text-center py-5">
                  <div class="mb-4">
                    <i class="fas fa-search fa-4x text-muted mb-3"></i>
                    <h4>No packages found</h4>
                    
                    @if(request()->hasAny(['artist', 'package', 'location', 'category', 'tanggal']))
                      <p class="text-muted">No packages match your current filters. Try adjusting your search criteria or clearing filters to see more results.</p>
                      <div class="mt-3">
                        <a href="{{ route('packages.browse') }}" class="btn btn-primary me-2">
                          <i class="fas fa-times me-1"></i>Clear All Filters
                        </a>
                        <button type="button" class="btn btn-outline-secondary" onclick="history.back()">
                          <i class="fas fa-arrow-left me-1"></i>Go Back
                        </button>
                      </div>
                    @else
                      <p class="text-muted">There are no packages available at the moment. Please check back later!</p>
                      <a href="{{ route('home') }}" class="btn btn-primary">
                        <i class="fas fa-home me-1"></i>Back to Home
                      </a>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          @endif
        </div>
      </section>
    </div>
  </div>
  
  <!-- Package Details Modal -->
  <div class="modal fade" id="packageDetailsModal" tabindex="-1" role="dialog" aria-labelledby="packageDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content modern-modal">
        <div class="modal-header modern-modal-header">
          <h4 class="modal-title" id="packageDetailsModalLabel">
            <i class="fas fa-music me-2"></i>Package Details
          </h4>
          <button type="button" class="modal-close-btn" data-dismiss="modal" aria-label="Close" onclick="closePackageModal()">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <div class="modal-body p-0">
          <div id="packageDetailsContent">
            <!-- Content will be loaded via JavaScript -->
          </div>
        </div>
      </div>
    </div>
  </div>
  
  @include('landingpage.footer')
  @stack('js')
  <script src="{{ asset('pulse-template/assets/scripts/app.min.js') }}"></script>
  <script src="{{ asset('assets/js/maincontent-scripts.js') }}"></script>
  
  <!-- Custom Modal Styling -->
  <style>
    .modal-xl {
      max-width: 95%;
    }
    
    @media (min-width: 1200px) {
      .modal-xl {
        max-width: 1100px;
      }
    }
    
    .modal-content {
      border-radius: 20px;
      overflow: hidden;
      border: none;
      box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    }
    
    .modal-header {
      background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #a855f7 100%);
      padding: 2rem 2.5rem;
      border: none;
      position: relative;
      overflow: hidden;
    }
    
    .modal-header::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.05"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
      opacity: 0.1;
    }
    
    .modal-title {
      position: relative;
      z-index: 2;
      font-size: 1.75rem;
      font-weight: 700;
      text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .modal-header .close {
      position: relative;
      z-index: 2;
      font-size: 2rem;
      font-weight: 300;
      opacity: 0.9;
      transition: all 0.3s ease;
      background: rgba(255,255,255,0.1);
      border-radius: 50%;
      width: 45px;
      height: 45px;
      display: flex;
      align-items: center;
      justify-content: center;
      backdrop-filter: blur(10px);
    }
    
    .modal-header .close:hover {
      opacity: 1;
      background: rgba(255,255,255,0.2);
      transform: rotate(90deg);
    }
    
    .package-hero {
      position: relative;
      background: linear-gradient(145deg, #f8fafc 0%, #e2e8f0 100%);
    }
    
    .package-image-container {
      position: relative;
      overflow: hidden;
      border-radius: 20px;
      box-shadow: 0 15px 40px rgba(0,0,0,0.2);
      background: linear-gradient(145deg, #f8fafc, #e2e8f0);
    }
    
    .package-image-container::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(45deg, 
        rgba(99, 102, 241, 0.1) 0%, 
        rgba(139, 92, 246, 0.1) 50%, 
        rgba(168, 85, 247, 0.1) 100%);
      z-index: 1;
      opacity: 0;
      transition: opacity 0.3s ease;
    }
    
    .package-image-container:hover::before {
      opacity: 1;
    }
    
    .package-image-container img {
      transition: transform 0.4s ease;
      position: relative;
      z-index: 0;
    }
    
    .package-image-container:hover img {
      transform: scale(1.08);
    }
    
    .package-badge {
      backdrop-filter: blur(10px);
      background: rgba(255,255,255,0.95);
      border: 2px solid white;
      box-shadow: 0 8px 25px rgba(0,0,0,0.15);
      font-weight: 700;
      letter-spacing: 0.5px;
      border-radius: 25px;
    }
    
    .package-info-card {
      background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
      border-radius: 25px;
      box-shadow: 0 15px 50px rgba(0,0,0,0.08);
      border: 1px solid #e2e8f0;
      position: relative;
      overflow: hidden;
    }
    
    .package-info-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 5px;
      background: linear-gradient(90deg, #6366f1, #8b5cf6, #a855f7, #d946ef);
    }
    
    .artist-avatar {
      background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
      box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
    }
    
    .section-icon {
      background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
      box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
    }
    
    .content-card {
      background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
      border: 1px solid #e2e8f0;
      border-radius: 20px;
      transition: all 0.4s ease;
      box-shadow: 0 4px 20px rgba(0,0,0,0.06);
      position: relative;
      overflow: hidden;
    }
    
    .content-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(99, 102, 241, 0.05), transparent);
      transition: left 0.5s ease;
    }
    
    .content-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 12px 35px rgba(99, 102, 241, 0.15);
      border-color: #8b5cf6;
    }
    
    .content-card:hover::before {
      left: 100%;
    }
    
    .date-badge {
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      border: none;
      font-weight: 600;
      padding: 8px 16px;
      border-radius: 20px;
      box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
      transition: transform 0.2s ease;
    }
    
    .date-badge:hover {
      transform: translateY(-1px);
    }
    
    .modal-footer-custom {
      background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
      border-top: 1px solid #e2e8f0;
      padding: 2rem 2.5rem;
    }
    
    .price-display {
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      font-weight: 800;
      font-size: 2.5rem;
      text-shadow: none;
    }
    
    .btn-custom-primary {
      background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #a855f7 100%);
      border: none;
      padding: 16px 40px;
      font-weight: 700;
      border-radius: 25px;
      box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
      transition: all 0.3s ease;
      text-transform: uppercase;
      letter-spacing: 1px;
      font-size: 0.9rem;
      position: relative;
      overflow: hidden;
    }
    
    .btn-custom-primary::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: left 0.5s ease;
    }
    
    .btn-custom-primary:hover {
      transform: translateY(-3px);
      box-shadow: 0 15px 40px rgba(99, 102, 241, 0.5);
      background: linear-gradient(135deg, #5855f0 0%, #7c3aed 50%, #9333ea 100%);
    }
    
    .btn-custom-primary:hover::before {
      left: 100%;
    }
    
    .btn-custom-secondary {
      background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
      border: 2px solid #e2e8f0;
      color: #64748b;
      padding: 16px 40px;
      font-weight: 600;
      border-radius: 25px;
      transition: all 0.3s ease;
      text-transform: uppercase;
      letter-spacing: 1px;
      font-size: 0.9rem;
      box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    
    .btn-custom-secondary:hover {
      border-color: #8b5cf6;
      color: #6366f1;
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(99, 102, 241, 0.2);
      background: linear-gradient(145deg, #ffffff 0%, #faf5ff 100%);
    }
    
    .loading-container {
      background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
      border-radius: 20px;
      padding: 4rem 2rem;
    }
    
    .spinner-custom {
      width: 4rem;
      height: 4rem;
      border: 4px solid #e2e8f0;
      border-top: 4px solid #6366f1;
      border-radius: 50%;
      animation: spin-custom 1s linear infinite;
    }
    
    @keyframes spin-custom {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    
    .error-container {
      background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
      border-radius: 20px;
      padding: 3rem 2rem;
    }
    
    .error-icon {
      background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
      box-shadow: 0 6px 20px rgba(239, 68, 68, 0.3);
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
      .modal-xl {
        max-width: 95%;
        margin: 1rem;
      }
      
      .modal-header,
      .modal-footer-custom {
        padding: 1.5rem;
      }
      
      .package-hero .row > div {
        margin-bottom: 1rem;
      }
      
      .btn-custom-primary,
      .btn-custom-secondary {
        width: 100%;
        margin-bottom: 0.5rem;
      }
    }

    /* Media Indicators Styles */
    .media-indicators {
      position: absolute;
      bottom: 10px;
      left: 10px;
      display: flex;
      gap: 5px;
      z-index: 3;
    }

    .media-indicator {
      background: rgba(0, 0, 0, 0.8);
      color: white;
      padding: 4px 8px;
      border-radius: 12px;
      font-size: 0.7rem;
      display: flex;
      align-items: center;
      gap: 3px;
      backdrop-filter: blur(10px);
    }

    .photo-indicator {
      background: rgba(34, 197, 94, 0.9);
    }

    .video-indicator {
      background: rgba(239, 68, 68, 0.9);
    }

    /* Gallery Styles */
    .media-gallery {
      padding: 1rem 0;
    }

    .media-subtitle {
      color: #4f46e5;
      font-weight: 600;
      margin-bottom: 1rem;
      font-size: 1rem;
    }

    .photos-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
      gap: 0.75rem;
      margin-bottom: 1rem;
    }

    .photo-item {
      position: relative;
      aspect-ratio: 1;
      overflow: hidden;
      border-radius: 8px;
      cursor: pointer;
      transition: transform 0.2s ease;
    }

    .photo-item:hover {
      transform: scale(1.05);
    }

    .gallery-photo {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.3s ease;
    }

    .gallery-photo:hover {
      transform: scale(1.1);
    }

    .video-container {
      max-width: 100%;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .gallery-video {
      width: 100%;
      max-height: 300px;
      object-fit: cover;
    }

    /* Photo Modal Styles */
    .photo-modal {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.9);
      z-index: 10000;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
    }

    .photo-modal-content {
      position: relative;
      max-width: 90%;
      max-height: 90%;
      cursor: default;
    }

    .photo-modal-image {
      width: 100%;
      height: auto;
      max-height: 90vh;
      object-fit: contain;
      border-radius: 8px;
    }

    .photo-modal-close {
      position: absolute;
      top: -40px;
      right: 0;
      color: white;
      font-size: 30px;
      font-weight: bold;
      cursor: pointer;
      background: rgba(0, 0, 0, 0.5);
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background 0.3s ease;
    }

    .photo-modal-close:hover {
      background: rgba(0, 0, 0, 0.8);
    }

    @media (max-width: 768px) {
      .photos-grid {
        grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
        gap: 0.5rem;
      }
      
      .media-indicators {
        bottom: 5px;
        left: 5px;
      }
      
      .media-indicator {
        padding: 2px 6px;
        font-size: 0.6rem;
      }
    }

    /* Professional Information Section Styles */
    .professional-info-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 1.5rem;
      margin-top: 1rem;
    }

    .professional-info-item {
      background: linear-gradient(145deg, #f8fafc 0%, #ffffff 100%);
      border-radius: 15px;
      padding: 1.5rem;
      border: 1px solid #e2e8f0;
      transition: all 0.3s ease;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .professional-info-item:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(99, 102, 241, 0.15);
      border-color: #c7d2fe;
    }

    .info-header {
      display: flex;
      align-items: center;
      margin-bottom: 1rem;
    }

    .info-icon {
      color: #6366f1;
      font-size: 1.2rem;
      margin-right: 0.75rem;
      width: 24px;
      height: 24px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .info-title {
      font-size: 1rem;
      font-weight: 600;
      color: #374151;
      margin: 0;
    }

    .info-value {
      font-size: 1.1rem;
      font-weight: 500;
      color: #1f2937;
      margin: 0;
    }

    .info-badges {
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem;
    }

    .info-badge {
      display: inline-block;
      padding: 0.375rem 0.75rem;
      border-radius: 20px;
      font-size: 0.8rem;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .language-badge {
      background: linear-gradient(135deg, #ddd6fe 0%, #c4b5fd 100%);
      color: #5b21b6;
      border: 1px solid #c4b5fd;
    }

    .language-badge:hover {
      background: linear-gradient(135deg, #c4b5fd 0%, #a78bfa 100%);
      transform: translateY(-1px);
    }

    .equipment-badge {
      background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
      color: #92400e;
      border: 1px solid #fde68a;
    }

    .equipment-badge:hover {
      background: linear-gradient(135deg, #fde68a 0%, #fcd34d 100%);
      transform: translateY(-1px);
    }

    @media (max-width: 768px) {
      .professional-info-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
      }
      
      .professional-info-item {
        padding: 1rem;
      }
      
      .info-header {
        margin-bottom: 0.75rem;
      }
    }
  </style>
  
  <script>
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
  
  <script>
    // Package Details Modal Functions
    function viewPackageDetails(packageId) {
      console.log('Opening package details for ID:', packageId);
      
      // Show loading state
      document.getElementById('packageDetailsContent').innerHTML = `
        <div class="modal-loading-state">
          <div class="loading-content">
            <div class="loading-spinner"></div>
            <h4 class="loading-title">Loading Package Details</h4>
            <p class="loading-text">Fetching the latest information for you...</p>
          </div>
        </div>
      `;
      
      // Show the modal using jQuery (compatible with Bootstrap 4/5)
      $('#packageDetailsModal').modal('show');
      
      // Fetch package details
      fetch(`/api/package/${packageId}`)
        .then(response => response.json())
        .then(data => {
          // Format available dates
          let availableDatesHtml = '';
          if (data.available_dates && data.available_dates.length > 0) {
            availableDatesHtml = data.available_dates.map(date => {
              const formattedDate = new Date(date).toLocaleDateString('en-US', {
                weekday: 'short',
                year: 'numeric',
                month: 'short',
                day: 'numeric'
              });
              return `<span class="modal-date-badge">${formattedDate}</span>`;
            }).join('');
          } else {
            availableDatesHtml = `
              <div class="no-dates-state">
                <div class="no-dates-content">
                  <i class="fas fa-calendar-times no-dates-icon"></i>
                  <p class="no-dates-text">No dates currently available</p>
                  <small class="no-dates-subtext">Contact the artist for custom scheduling</small>
                </div>
              </div>
            `;
          }
          
          // Get image URL
          const imageUrl = getImageUrl(data.image);
          
          // Build the modal content
          const content = `
            <!-- Modal Hero Section -->
            <div class="modal-hero-section">
              <div class="row g-0">
                <div class="col-lg-5">
                  <div class="modal-image-container">
                    <img src="${imageUrl}" alt="${data.package_name}" class="modal-image">
                    ${data.genre ? `
                      <div class="modal-genre-badge">
                        <span class="badge-content">${data.genre}</span>
                      </div>
                    ` : ''}
                    <div class="modal-price-badge">
                      <span class="badge-content">€${data.price ? Number(data.price).toLocaleString() : '0'}</span>
                    </div>
                  </div>
                </div>
                <div class="col-lg-7">
                  <div class="modal-info-section">
                    <div class="modal-header-content">
                      <h1 class="modal-package-title">${data.package_name}</h1>
                      <div class="modal-artist-info">
                        <h4 class="artist-name">${data.stage_name || data.artist_name}</h4>
                        <p class="artist-role">Professional Artist</p>
                      </div>
                    </div>
                    
                    ${(data.city || data.region || data.country) ? `
                      <div class="modal-location-card">
                        <div class="location-content">
                          <i class="fas fa-map-marker-alt location-icon"></i>
                          <div class="location-details">
                            <h6 class="location-city">${data.city || data.country || 'Location'}</h6>
                            ${data.region ? `<small class="location-province">${data.region}</small>` : ''}
                          </div>
                        </div>
                      </div>
                    ` : ''}
                  </div>
                </div>
              </div>
            </div>
            
            <div class="modal-content-sections">
              <!-- Description Section -->
              <div class="modal-section">
                <div class="section-header">
                  <h3 class="section-title">
                    <i class="fas fa-align-left section-icon"></i>
                    Package Description
                  </h3>
                </div>
                <div class="section-content">
                  <div class="content-card">
                    <p class="description-text">${data.description || 'This package currently has no detailed description available. Please contact the artist directly for more information about what this package includes.'}</p>
                  </div>
                </div>
              </div>

              <!-- Photos & Video Gallery Section -->
              ${(data.photos && data.photos.length > 0) || data.video ? `
                <div class="modal-section">
                  <div class="section-header">
                    <h3 class="section-title">
                      <i class="fas fa-photo-video section-icon"></i>
                      Gallery
                    </h3>
                  </div>
                  <div class="section-content">
                    <div class="content-card">
                      <div class="media-gallery">
                        ${data.photos && data.photos.length > 0 ? `
                          <div class="photos-section">
                            <h6 class="media-subtitle"><i class="fas fa-images me-2"></i>Photos (${data.photos.length})</h6>
                            <div class="photos-grid">
                              ${data.photos.map(photo => `
                                <div class="photo-item">
                                  <img src="/storage/${photo}" alt="Package photo" class="gallery-photo" onclick="openPhotoModal('/storage/${photo}')">
                                </div>
                              `).join('')}
                            </div>
                          </div>
                        ` : ''}
                        ${data.video ? `
                          <div class="video-section ${data.photos && data.photos.length > 0 ? 'mt-4' : ''}">
                            <h6 class="media-subtitle"><i class="fas fa-video me-2"></i>Video</h6>
                            <div class="video-container">
                              <video controls class="gallery-video">
                                <source src="/storage/${data.video}" type="video/mp4">
                                Your browser does not support the video tag.
                              </video>
                            </div>
                          </div>
                        ` : ''}
                      </div>
                    </div>
                  </div>
                </div>
              ` : ''}
              
              <!-- Professional Information Section -->
              ${data.years_experience || (data.languages && data.languages.length > 0) || (data.equipment_owned && data.equipment_owned.length > 0) ? `
                <div class="modal-section">
                  <div class="section-header">
                    <h3 class="section-title">
                      <i class="fas fa-briefcase section-icon"></i>
                      Professional Information
                    </h3>
                  </div>
                  <div class="section-content">
                    <div class="content-card">
                      <div class="professional-info-grid">
                        ${data.years_experience ? `
                          <div class="professional-info-item">
                            <div class="info-header">
                              <i class="fas fa-calendar-check info-icon"></i>
                              <h6 class="info-title">Years of Experience</h6>
                            </div>
                            <p class="info-value">${data.years_experience} years</p>
                          </div>
                        ` : ''}
                        
                        ${data.languages && data.languages.length > 0 ? `
                          <div class="professional-info-item">
                            <div class="info-header">
                              <i class="fas fa-language info-icon"></i>
                              <h6 class="info-title">Languages</h6>
                            </div>
                            <div class="info-badges">
                              ${data.languages.map(language => `
                                <span class="info-badge language-badge">${language}</span>
                              `).join('')}
                            </div>
                          </div>
                        ` : ''}
                        
                        ${data.equipment_owned && data.equipment_owned.length > 0 ? `
                          <div class="professional-info-item">
                            <div class="info-header">
                              <i class="fas fa-tools info-icon"></i>
                              <h6 class="info-title">Equipment Owned</h6>
                            </div>
                            <div class="info-badges">
                              ${data.equipment_owned.map(equipment => `
                                <span class="info-badge equipment-badge">${equipment}</span>
                              `).join('')}
                            </div>
                          </div>
                        ` : ''}
                      </div>
                    </div>
                  </div>
                </div>
              ` : ''}
              
              <!-- Available Dates Section -->
              <div class="modal-section">
                <div class="section-header">
                  <h3 class="section-title">
                    <i class="fas fa-calendar-alt section-icon"></i>
                    Available Dates
                  </h3>
                </div>
                <div class="section-content">
                  <div class="content-card">
                    <div class="dates-container">
                      ${availableDatesHtml}
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Modal Footer Actions -->
            <div class="modal-footer-actions">
              <div class="footer-content">
                <div class="price-section">
                  <div class="total-price">€${data.price ? Number(data.price).toLocaleString() : '0'}</div>
                  <p class="price-label">Total Package Price</p>
                </div>
                <div class="action-buttons">
                  <button type="button" class="btn btn-modal-secondary" onclick="closePackageModal()">
                    <i class="fas fa-times me-2"></i>Close
                  </button>
                  <a href="/book/${data.id}" class="btn btn-modal-primary">
                    <i class="fas fa-calendar-plus me-2"></i> Send Booking Request
                  </a>
                </div>
              </div>
            </div>
          `;
          
          document.getElementById('packageDetailsContent').innerHTML = content;
        })
        .catch(error => {
          console.error('Error fetching package details:', error);
          document.getElementById('packageDetailsContent').innerHTML = `
            <div class="modal-error-state">
              <div class="error-content">
                <div class="error-icon">
                  <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h3 class="error-title">Unable to Load Package Details</h3>
                <p class="error-text">We encountered an issue while fetching the package information. This might be a temporary network problem.</p>
                <div class="error-actions">
                  <button type="button" class="btn btn-modal-secondary" onclick="closePackageModal()">
                    <i class="fas fa-times me-2"></i>Close
                  </button>
                  <button type="button" class="btn btn-modal-primary" onclick="viewPackageDetails(${packageId})">
                    <i class="fas fa-redo me-2"></i>Try Again
                  </button>
                </div>
              </div>
            </div>
          `;
        });
    }
    
    // Function to close modal
    function closePackageModal() {
      $('#packageDetailsModal').modal('hide');
    }

    // Function to open photo in fullscreen modal
    function openPhotoModal(imageSrc) {
      const photoModal = `
        <div id="photoModal" class="photo-modal" onclick="closePhotoModal()">
          <div class="photo-modal-content">
            <span class="photo-modal-close" onclick="closePhotoModal()">&times;</span>
            <img src="${imageSrc}" alt="Package photo" class="photo-modal-image">
          </div>
        </div>
      `;
      document.body.insertAdjacentHTML('beforeend', photoModal);
      document.body.style.overflow = 'hidden';
    }

    // Function to close photo modal
    function closePhotoModal() {
      const modal = document.getElementById('photoModal');
      if (modal) {
        modal.remove();
        document.body.style.overflow = 'auto';
      }
    }
    
    // Make functions globally available
    window.viewPackageDetails = viewPackageDetails;
    window.closePackageModal = closePackageModal;
    window.openPhotoModal = openPhotoModal;
    window.closePhotoModal = closePhotoModal;
  </script>

  <script>
    // Search Form Enhancement Scripts
    document.addEventListener('DOMContentLoaded', function() {
      // Country/Province dynamic selection
      const countrySelect = document.getElementById('countrySelect');
      const provinceSelect = document.getElementById('provinceSelect');
      
      // Countries and their regions data
      const regionsData = @json(\App\Helpers\Countries::getAllRegions());
      
      // Initialize province dropdown based on current selection
      const currentCountry = countrySelect.value;
      const currentProvince = "{{ request('province') }}";
      
      if (currentCountry && regionsData[currentCountry]) {
        populateProvinces(currentCountry, currentProvince);
      }
      
      // Handle country change
      countrySelect.addEventListener('change', function() {
        const selectedCountry = this.value;
        
        // Clear province selection
        provinceSelect.innerHTML = '<option value="">All Provinces</option>';
        
        if (selectedCountry && regionsData[selectedCountry]) {
          populateProvinces(selectedCountry, '');
        }
      });
      
      function populateProvinces(country, selectedProvince = '') {
        if (regionsData[country]) {
          regionsData[country].forEach(function(province) {
            const option = document.createElement('option');
            option.value = province;
            option.textContent = province;
            if (selectedProvince === province) {
              option.selected = true;
            }
            provinceSelect.appendChild(option);
          });
        }
      }
    });
    
    // Filter removal functionality
    function removeFilter(filterName) {
      const url = new URL(window.location.href);
      const params = new URLSearchParams(url.search);
      
      params.delete(filterName);
      
      // Rebuild URL and redirect
      const newUrl = url.pathname + (params.toString() ? '?' + params.toString() : '');
      window.location.href = newUrl;
    }
    
    // Make remove filter function globally available
    window.removeFilter = removeFilter;
  </script>
</body>
</html>
