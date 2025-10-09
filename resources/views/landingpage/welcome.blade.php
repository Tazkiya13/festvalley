<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <!-- themes/pulse/ Sat, 25 Jan 2025 16:25:47 GMT -->
  <head>
      @include('head')
      @if (file_exists(public_path('build/manifest.json')) || is_dir(public_path('hot')))
      @vite([ 'resources/js/app.js'])
      @endif
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  </head>
  <body>
    <div class="app dk" id="app">
        <div id="content" class="app-content" role="main">
            @include('chat-widget-customer')
            @include('landingpage.header')
            @include('landingpage.mainconten')
            @include('partials.toastr')
        </div>
    </div>
    @include('landingpage.footer')
    @stack('js')
    <script src="{{ asset('pulse-template/assets/scripts/app.min.js') }}"></script>
    <script src="{{ asset('assets/js/maincontent-scripts.js') }}"></script>
    
    <script>
        // Toast notifications for authentication feedback
        var successMessage = "{{ session('success') }}";
        var statusMessage = "{{ session('status') }}";
        var errorMessage = "{{ session('error') }}";
        var firstError = "{{ $errors->first() }}";
        
        if (successMessage) {
            toastr.success(successMessage);
        }
        
        if (statusMessage) {
            toastr.success(statusMessage);
        }
        
        if (errorMessage) {
            toastr.error(errorMessage);
        }
        
        if (firstError) {
            toastr.error(firstError);
        }
        
        // Configure toastr
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
    </script>
  </body>
  <!-- themes/pulse/ Sat, 25 Jan 2025 16:26:04 GMT -->
</html>
