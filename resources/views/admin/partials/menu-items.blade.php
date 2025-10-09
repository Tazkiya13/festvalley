{{-- Navigation Menu Items Partial --}}
{{-- Usage: @include('admin.partials.menu-items', ['menuType' => 'mobile']) or ['menuType' => 'desktop']) --}}

{{-- Simple menu for Artists - only show Profile and Logout --}}
@if(Auth::user()->hasRole('Artist'))
    @can('view profile')
        @if($menuType === 'mobile')
            <a href="{{ route('profile.index') }}" 
               class="{{ request()->routeIs('profile.index') ? 'active' : '' }}"
               onclick="closeMobileMenu()">
                <i class="fa fa-user-circle"></i>
                My Profile
            </a>
        @else
            <a href="{{ route('artists.index') }}" class="dropdown-item">
                <i class="fa fa-table"></i> My Dashboard
            </a>
            <a href="{{ route('profile.index') }}" class="dropdown-item">
                <i class="fa fa-user-circle"></i> My Profile
            </a>
        @endif
    @endcan

    @if($menuType === 'mobile')
        <a href="{{ route('auth.logout') }}" 
           onclick="closeMobileMenu()">
            <i class="fa fa-sign-out-alt"></i>
            Logout
        </a>
    @else
        <a href="{{ route('auth.logout') }}" class="dropdown-item">
            <i class="fa fa-sign-out-alt"></i> Logout
        </a>
    @endif

@else
{{-- Full admin/customer menu for non-artists --}}

{{-- Admin Dashboard Section - Only for Admin role --}}
@if(Auth::user()->hasRole('Admin') && $menuType === 'desktop')
    <div class="dropdown-section">
        <h6 class="dropdown-header">Dashboard</h6>
        <a href="{{ route('admin.home') }}" class="dropdown-item">
            <i class="fa fa-tachometer"></i> Admin Dashboard
        </a>
    </div>
@endif

{{-- Main Package & Chat section - only show if user can view packages OR chat --}}
@canany(['view package', 'view chat'])
    @can('view package')
        @if($menuType === 'mobile')
            <a href="{{ route('packages.index') }}" 
               class="{{ request()->routeIs('packages.*') ? 'active' : '' }}"
               onclick="closeMobileMenu()">
                <i class="fa fa-box"></i>
                Packages
            </a>
        @else
            <div class="dropdown-section">
                <h6 class="dropdown-header">Main</h6>
                <a href="{{ route('packages.index') }}" class="dropdown-item">
                    <i class="fa fa-box"></i> Packages
                </a>
            </div>
        @endif
    @endcan

    @can('view chat')
        @if($menuType === 'mobile')
            <a href="{{ route('view.chat') }}" 
               class="{{ request()->routeIs('view.chat') ? 'active' : '' }}"
               onclick="closeMobileMenu()">
                <i class="fa fa-comments"></i>
                Chat
            </a>
        @else
            <a href="{{ route('view.chat') }}" class="dropdown-item">
                <i class="fa fa-comments"></i> Chat
            </a>
        @endif
    @endcan
@endcanany

{{-- Admin-specific section - only for Admin role --}}
@if(Auth::user()->hasRole('Admin'))
    @if($menuType === 'mobile')
        <a href="{{ route('profile.admin.index') }}" 
           class="{{ request()->routeIs('profile.admin.*') ? 'active' : '' }}"
           onclick="closeMobileMenu()">
            <i class="fa fa-user-cog"></i>
            Artist
        </a>
    @else
        <a href="{{ route('profile.admin.index') }}" class="dropdown-item">
            <i class="fa fa-user-cog"></i> Artist
        </a>
    @endif
@endif

{{-- Order Management section - only show if user can view orders --}}
@can('view customer order')
    @if($menuType === 'mobile')
        <a href="{{ route('order.index') }}" 
           class="{{ request()->routeIs('order.*') ? 'active' : '' }}"
           onclick="closeMobileMenu()">
            <i class="fa fa-shopping-cart"></i>
            Orders
        </a>
    @else
        <a href="{{ route('order.index') }}" class="dropdown-item">
            <i class="fa fa-shopping-cart"></i> Orders
        </a>
    @endif
@endcan

{{-- Artist Package Management section - only show if user can view/manage artist packages --}}
@can('view package artists')
    @if($menuType === 'mobile')
        <a href="{{ route('artists.index') }}" 
           class="{{ request()->routeIs('artists.*') ? 'active' : '' }}"
           onclick="closeMobileMenu()">
            <i class="fa fa-box"></i>
            Packages
        </a>
        @can('create package artists')
        <a href="{{ route('packages.create') }}" 
           class="{{ request()->routeIs('packages.create') ? 'active' : '' }}"
           onclick="closeMobileMenu()">
            <i class="fa fa-plus"></i>
            Add Package
        </a>
        @endcan
    @else
        <div class="dropdown-section">
            <h6 class="dropdown-header">Packages</h6>
            <a href="{{ route('artists.index') }}" class="dropdown-item">
                <i class="fa fa-box"></i> My Packages
            </a>
            @can('create package artists')
            <a href="{{ route('packages.create') }}" class="dropdown-item">
                <i class="fa fa-plus"></i> Add New Package
            </a>
            @endcan
        </div>
    @endif
@endcan

{{-- Booking section - show artist booking for artists, admin booking for admins --}}
@can('view package artist')
    @if($menuType === 'mobile')
        <a href="{{ route('artists.booking-requests') }}" 
           class="{{ request()->routeIs('artists.booking-requests') ? 'active' : '' }}"
           onclick="closeMobileMenu()">
            <i class="fa fa-calendar-check"></i>
            Booking Requests
        </a>
    @else
        <a href="{{ route('artists.booking-requests') }}" class="dropdown-item">
            <i class="fa fa-calendar-check"></i> Booking Requests
        </a>
    @endif
@elsecan('view customer booking')
    @if($menuType === 'mobile')
        <a href="{{ route('admin.booking.index') }}" 
           class="{{ request()->routeIs('admin.booking.*') ? 'active' : '' }}"
           onclick="closeMobileMenu()">
            <i class="fa fa-calendar-check"></i>
            Admin Bookings
        </a>
    @else
        <a href="{{ route('admin.booking.index') }}" class="dropdown-item">
            <i class="fa fa-calendar-check"></i> Admin Bookings
        </a>
    @endif
@endcan

    @can('view customer invoice')
        @if($menuType === 'mobile')
            <a href="{{ route('admin.invoice.show') }}" 
               class="{{ request()->routeIs('admin.invoice.*') ? 'active' : '' }}"
               onclick="closeMobileMenu()">
                <i class="fa fa-file-invoice"></i>
                Invoices
            </a>
        @else
            <a href="{{ route('admin.invoice.show') }}" class="dropdown-item">
                <i class="fa fa-receipt"></i> Admin Invoices
            </a>
        @endif
    @endcan

    <div class="dropdown-divider"></div>

    @can('view profile')
        @if($menuType === 'mobile')
            <a href="{{ route('user.profile') }}" 
               class="{{ request()->routeIs('user.profile') ? 'active' : '' }}"
               onclick="closeMobileMenu()">
                <i class="fa fa-user-circle"></i>
                My Profile
            </a>
        @else
            <a href="{{ route('user.profile') }}" class="dropdown-item">
                <i class="fa fa-user-circle"></i> My Profile
            </a>
        @endif
    @endcan

    @if($menuType === 'mobile')
        <a href="{{ route('auth.logout') }}" 
           onclick="closeMobileMenu()">
            <i class="fa fa-sign-out-alt"></i>
            Logout
        </a>
    @else
        <a href="{{ route('auth.logout') }}" class="dropdown-item">
            <i class="fa fa-sign-out-alt"></i> Logout
        </a>
    @endif

@endif
