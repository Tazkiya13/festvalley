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

      <!-- Customer Content Section -->
      <div class="container mt-4">
        @yield('content')
      </div>

      @include('landingpage.footer')
    </div>
  </div>

  @stack('js')
  <script src="{{ asset('pulse-template/assets/scripts/app.min.js') }}"></script>
  <script src="{{ asset('assets/js/maincontent-scripts.js') }}"></script>
</body>
</html>
