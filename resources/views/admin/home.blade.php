<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Home') - FestValley</title>
    <meta name="description" content="@yield('meta_description', 'Admin panel for FestValley')">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    {{-- Existing CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/order-modal.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/home-admin.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/invoice.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/responsif-tabel.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    
    @include('head')
    
    @if (file_exists(public_path('build/manifest.json')) || is_dir(public_path('hot')))
        @vite(['resources/js/app.js'])
    @endif
    
    <!-- Bootstrap CSS and Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .main-content {
            padding: 20px;
            background-color: #f8f9fa;
            min-height: 100vh;
        }

        /* Admin navigation styles */
        .admin-nav-item {
            position: relative;
        }

        .admin-nav-item .dropdown-menu {
            min-width: 300px;
            max-height: 400px;
            overflow-y: auto;
        }

        .admin-nav-section {
            padding: 8px 16px;
            margin: 0;
            font-size: 0.875rem;
            font-weight: 600;
            color: #6c757d;
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }

        .admin-nav-link {
            padding: 8px 16px;
            display: flex;
            align-items: center;
            color: #212529;
            text-decoration: none;
            transition: background-color 0.15s ease-in-out;
        }

        .admin-nav-link:hover {
            background-color: #f8f9fa;
            color: #0d6efd;
            text-decoration: none;
        }

        .admin-nav-link i {
            width: 20px;
            margin-right: 8px;
            text-align: center;
        }

        @stack('styles')
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        // Configure toastr options with proper text colors
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
    
    <!-- Fix toastr text colors -->
    <style>
        /* Force toastr text to be visible */
        #toast-container > div {
            color: #ffffff !important;
            opacity: 1 !important;
        }
        
        .toast-success {
            background-color: #51a351 !important;
            color: #ffffff !important;
        }
        
        .toast-error {
            background-color: #bd362f !important;
            color: #ffffff !important;
        }
        
        .toast-info {
            background-color: #2f96b4 !important;
            color: #ffffff !important;
        }
        
        .toast-warning {
            background-color: #f89406 !important;
            color: #ffffff !important;
        }
        
        .toast-title {
            color: #ffffff !important;
            font-weight: bold !important;
        }
        
        .toast-message {
            color: #ffffff !important;
        }
        
        /* Ensure text is always visible */
        .toast-success .toast-message,
        .toast-error .toast-message,
        .toast-info .toast-message,
        .toast-warning .toast-message {
            color: #ffffff !important;
        }
        
        .toast-success .toast-title,
        .toast-error .toast-title,
        .toast-info .toast-title,
        .toast-warning .toast-title {
            color: #ffffff !important;
        }
    </style>

    @stack('styles')
</head>

<body>
    {{-- Include Admin Header Navigation --}}
    @include('admin.header')

    {{-- Main Content --}}
    <div class="main-content">
        @yield('content')
        @include('partials.toastr')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    {{-- Existing Scripts --}}
    @stack('js')
    @yield('scripts')
    <script src="{{ asset('pulse-template/assets/scripts/app.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="{{ asset('assets/js/flatpicker/flatpicker-init.js') }}"></script>
    <script src="{{ asset('assets/js/flatpicker/flatpicker-libur.js') }}"></script>
    <script src="{{ asset('assets/js/flatpicker/flatpicker-packages.js') }}"></script>
    <script src="{{ asset('assets/js/flatpicker/flatpicker-packages-create.js')}}"></script>
    <script src="{{ asset('assets/js/flatpicker/flatpicker-packages-edit.js') }}"></script>
    <script src="{{ asset('assets/js/flatpicker/flatpicker-detail-paket.js') }}"></script>
    <script src="{{ asset('assets/js/flatpicker/flatpicker-booking.js') }}"></script>
    <script src="{{ asset('assets/js/flatpicker/flatpicker-artist.js') }}"></script>
    <script src="{{ asset('assets/js/order-modal.js') }}"></script>
    <script src="{{ asset('assets/js/home-admin.js') }}"></script>

    @stack('scripts')
</body>

</html>
