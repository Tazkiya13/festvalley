<!DOCTYPE html>
<html lang="en">
<head>
  @include('head')
  <link rel="stylesheet" href="{{ asset('css/discover-page.css') }}">
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

      <!-- Enhanced Hero Header Section -->
  <section class="hero-header-section">
    <div class="hero-background">
      <div class="hero-gradient"></div>
      <div class="hero-pattern"></div>
    </div>

    <div class="hero-content">
      <h1 class="hero-title">
        Discover Amazing
        <span class="hero-title-highlight">Artists</span>
      </h1>

      <p class="hero-subtitle">
        Discover talented musicians and find your next favorite artist.
        From jazz to rock, explore the perfect music package for your event.
      </p>

      {{-- <div class="hero-stats">
        <div class="hero-stat">
          <div class="hero-stat-number">{{ $packages->count() }}+</div>
          <div class="hero-stat-label">package Musik</div>
        </div>
        <div class="hero-stat">
          <div class="hero-stat-number">50+</div>
          <div class="hero-stat-label">Artis Aktif</div>
        </div>
        <div class="hero-stat">
          <div class="hero-stat-number">6</div>
          <div class="hero-stat-label">Kategori</div>
        </div>
      </div> --}}

      <div class="hero-actions">
        <a href="{{ route('packages.browse') }}" class="hero-btn-primary">
          <span>Explore Artists</span>
          <i class="material-icons">arrow_forward</i>
        </a>
        <a href="{{ route('packages.browse') }}#search" class="hero-btn-secondary">
          <i class="material-icons">search</i>
          <span>Search Artists</span>
        </a>
      </div>
    </div>
  </section>

  <!-- Main Content -->
  <main class="main-content">
    <!-- Featured Artists -->
    <div class="owl-carousel owl-theme" data-ui-jp="owlCarousel" data-ui-options="{items: 1, loop: {{ count($packages) > 1 ? 'true' : 'false' }}, autoplay: {{ count($packages) > 1 ? 'true' : 'false' }}, autoplayTimeout: 5000, nav: true, dots: true, smartSpeed: 800}">
        @forelse($packages as $package)
        <div class="row-col" data-bg-image="{{ $package->image }}" style="background: url('{{ asset('images/default.jpg') }}') center center / cover no-repeat; min-height: 350px; border-radius: 16px; padding: 40px 0;">
            <div class="col-lg-2"></div>
            <div class="col-lg-8 text-center">
                <div class="p-a-lg" style="background: rgba(255,255,255,0.7); border-radius: 16px;">
                    <h2 class="display-4 m-y-lg">
                        {{ $package->package_name ?? 'Discover Artists' }}
                    </h2>
                    <h6 class="text-muted m-b-lg">
                        {{ $package->user->name ?? 'Explore talented musicians and find your next favorite artist' }}
                    </h6>
                    <a href="{{ route('booking.form', $package->id) }}" class="package-cta">Send Booking Request</a>
                </div>
            </div>
            <div class="col-lg-2"></div>
        </div>
        @empty
        <div class="row-col" style="background: url('{{ asset('images/default.jpg') }}') center center / cover no-repeat; min-height: 350px; border-radius: 16px; padding: 40px 0;">
            <div class="col-lg-2"></div>
            <div class="col-lg-8 text-center">
                <div class="p-a-lg" style="background: rgba(255,255,255,0.7); border-radius: 16px;">
                    <h2 class="display-4 m-y-lg">Discover Amazing Artists</h2>
                    <h6 class="text-muted m-b-lg">Explore talented musicians and find your next favorite artist</h6>
                    <a href="{{ route('packages.browse') }}" class="package-cta">Browse All Packages</a>
                </div>
            </div>
            <div class="col-lg-2"></div>
        </div>
        @endforelse
    </div>

    <!-- Trending Now -->
    <section>
      <div class="section-header">
        <h2 class="section-title">Trending Now</h2>
        {{-- <a href="#" class="view-all-btn">View All</a> --}}
      </div>

      <div class="trending-carousel">
        <div class="trending-grid">
          @foreach($packages->skip(3)->take(8) as $package)
            <div class="trending-card">
              <a href="{{ route('booking.form', $package->id) }}">
                <div class="trending-image-container">
                  <img src="{{ asset('images/default.jpg') }}" data-image="{{ $package->image }}"
                       alt="{{ $package->package_name }}" class="trending-image">

                  <!-- Overlay untuk teks -->
                  <div class="trending-overlay"></div>

                  <!-- Konten di dalam card dengan background transparan -->
                  <div class="trending-content">
                    <div class="trending-text-wrapper">
                      <h3 class="trending-title">{{ $package->package_name ?? 'Trending Artist' }}</h3>
                      <p class="trending-artist">{{ $package->user->name ?? 'Artist' }}</p>
                    </div>
                  </div>
                </div>
              </a>
            </div>
          @endforeach
        </div>
      </div>
    </section>

    <!-- New package -->
    <section>
      <div class="section-header">
        <h2 class="section-title">New package</h2>
        {{-- <a href="#" class="view-all-btn">View All</a> --}}
      </div>

      <div class="new-package-grid">
        @foreach($packages->skip(3)->take(6) as $package)
          <div class="package-card">
            <a href="{{ route('booking.form', $package->id) }}">
              <div class="package-image-container">
                <img src="{{ asset('images/default.jpg') }}" data-image="{{ $package->image }}"
                     alt="{{ $package->package_name }}" class="package-image">

                <!-- Overlay untuk gradient -->
                <div class="package-overlay"></div>

                <!-- Konten di dalam card -->
                <div class="package-content">
                  <div class="package-text-wrapper">
                    <h3 class="package-name">{{ $package->package_name ?? 'Package Name' }}</h3>
                    <p class="artist-name">{{ $package->user->name ?? 'Artist Name' }}</p>
                  </div>
                </div>
              </div>
            </a>
          </div>
        @endforeach
      </div>
    </section>
</main>
@include('landingpage.footer')

  <script src="{{ asset('pulse-template/assets/scripts/app.min.js') }}"></script>
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

    // Smooth scroll for trending carousel
    const trendingGrid = document.querySelector('.trending-grid');
    if (trendingGrid) {
      // Optional: Add keyboard navigation
      document.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowLeft') {
          trendingGrid.scrollBy({ left: -300, behavior: 'smooth' });
        } else if (e.key === 'ArrowRight') {
          trendingGrid.scrollBy({ left: 300, behavior: 'smooth' });
        }
      });
    }

    // Enhanced card interactions
    document.querySelectorAll('.card, .trending-card, .featured-main').forEach(card => {
      card.addEventListener('click', function() {
        // Add click handling for navigation to artist details
        console.log('Navigate to artist:', this.querySelector('.card-title, .trending-title, .featured-title')?.textContent);
      });
    });
  </script>

  <!-- Include Pinterest Masonry JS like browser page -->
  <script src="{{ asset('assets/js/discover.js') }}"></script>

</body>
</html>
