{{-- Combined Navigation: Sidebar + Top Navbar --}}

{{-- Sidebar Navigation --}}
<div id="aside" class="app-aside modal fade nav-dropdown" style="bottom : 0px;">
    <div class="left navside grey dk" data-layout="column">
        {{-- <div class="navbar no-radius">
            <a href="#" class="navbar-brand md">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="32" height="32">
                    <circle cx="24" cy="24" r="24" fill="rgba(255,255,255,0.2)" />
                    <circle cx="24" cy="24" r="22" fill="#1c202b" class="brand-color" />
                    <circle cx="24" cy="24" r="10" fill="#ffffff" />
                    <circle cx="13" cy="13" r="2" fill="#ffffff" class="brand-animate" />
                    <path d="M 14 24 L 24 24 L 14 44 Z" fill="#FFFFFF" />
                    <circle cx="24" cy="24" r="3" fill="#000000" />
                </svg>
                <img src="{{ asset('pulse-template/assets/images/logo.png') }}" alt="." class="hide" />
        </div> --}}
        <h3 style="margin-left:20px; padding: 30px 10px 0 0;">
            <a href="{{ route('admin.home') }}" style="color: inherit; text-decoration: none;">FestValley</a>
        </h3>
        <div data-flex class="hide-scroll">
            <nav class="scroll nav-stacked nav-active-primary">
                <ul class="nav">
                    @can('view package')
                        <li class="nav-header hidden-folded">
                            <span class="text-xs text-muted">Main</span>
                        </li>
                        <li class="{{ request()->routeIs('packages.index') ? 'active' : '' }}">
                            <a href="{{ route('packages.index') }}">
                                <span class="nav-icon">
                                    <i class="material-icons">sort</i>
                                </span>
                                <span class="nav-text">package</span></a>
                        </li>
                    @endcan

                    @can('view chat')
                        <li class="{{ request()->routeIs('view.chat') ? 'active' : '' }}">
                            <a href="{{ route('view.chat') }}">
                                <span class="nav-icon">
                                    <i class="material-icons">chat</i>
                                </span>
                                <span class="nav-text">Chat</span></a>
                        </li>
                    @endcan

                    @can('view customer order')
                    <li class="{{ request()->routeIs('order.index') ? 'active' : '' }}">
                        <a href="{{ route('order.index') }}">
                            <span class="nav-icon">
                                <i class="material-icons">shopping_cart</i>
                            </span>
                            <span class="nav-text">Order</span></a>
                    </li>
                    @endcan

                    @can('view package artists')
                        <li class="nav-header hidden-folded m-t">
                            <span class="text-xs text-muted">Package Management</span>
                        </li>
                        <li class="{{ request()->routeIs('artists.index') ? 'active' : '' }}">
                            <a href="{{ route('artists.index') }}">
                                <span class="nav-icon">
                                    <i class="material-icons">inventory</i></span>
                                <span class="nav-text">My Packages</span></a>
                        </li>
                        @can('create package artists')
                        <li class="{{ request()->routeIs('packages.create') ? 'active' : '' }}">
                            <a href="{{ route('packages.create') }}">
                                <span class="nav-icon">
                                    <i class="material-icons">add_box</i></span>
                                <span class="nav-text">Add New Package</span></a>
                        </li>
                        @endcan
                        @endcan

                        @can('view profile')
                        <li class="{{ request()->routeIs('user.profile') ? 'active' : '' }}">
                            <a href="{{ route('user.profile') }}">
                                <span class="nav-icon">
                                    <i class="material-icons">account_circle</i></span>
                                <span class="nav-text">My Profile</span></a>
                        </li>
                        @endcan

                        @if(Auth::user()->hasRole('Admin'))
                         <li class="{{ request()->routeIs('profile.admin.index') ? 'active' : '' }}">
                            <a href="{{ route('profile.admin.index') }}">
                                <span class="nav-icon">
                                    <i class="material-icons">account_circle</i></span>
                                <span class="nav-text">Admin Profile</span></a>
                        </li>
                        @endif

                    @can('view package customer')
                        <li class="nav-header hidden-folded m-t">
                            <span class="text-xs text-muted">Customer</span>
                        </li>
                        <li class="{{ request()->routeIs('packages.browse') ? 'active' : '' }}">
                            <a href="{{ route('packages.browse') }}" onclick="window.location=this.href; return false;">
                                <span class="nav-icon">
                                    <i class="material-icons">person</i></span>
                                <span class="nav-text">Browse Packages</span></a>
                        </li>
                    @endcan

                    @can('view customer invoice')
                    <li class="{{ request()->routeIs('admin.invoice.show') ? 'active' : '' }}">
                        <a href="{{ route('admin.invoice.show') }}">
                            <span class="nav-icon">
                                <i class="material-icons">receipt</i></span>
                            <span class="nav-text">Invoice</span></a>
                    </li>
                    @endcan

                    @can('view customer booking')
                    <li class="{{ request()->routeIs('admin.booking.index') ? 'active' : '' }}">
                        <a href="{{ route('admin.booking.index') }}">
                            <span class="nav-icon">
                                <i class="material-icons">book_online</i></span>
                            <span class="nav-text">Booking</span></a>
                    </li>
                    @endcan
                </ul>
            </nav>
        </div>

        <div style="padding-left: 20px;">
            <p>Hai, {{ Auth::user()->name }}</p>
        </div>

        <div data-flex-no-shrink>
            <div class="nav-fold">
                <a href="#" id="profile-menu-trigger" class="profile-menu-btn">
                    <span class="pull-left">
                        <svg class="w-32 h-32 text-gray-500" fill="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M12 2a5 5 0 1 1 0 10 5 5 0 0 1 0-10zm0 11c-4.42 0-8 2.24-8 5v2h16v-2c0-2.76-3.58-5-8-5z" />
                        </svg>
                    </span>
                    <span class="clear hidden-folded p-x p-y-xs">
                        <span class="block _500 text-ellipsis">Account</span>
                    </span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Profile Menu Modal -->
<div id="profile-menu-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Account Menu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="list-group list-group-flush">
                    {{-- <a href="{{ route('profile.index') }}" class="list-group-item list-group-item-action">
                        <i class="material-icons mr-2">person</i>
                        Profile
                    </a> --}}
                    {{-- <a href="#" class="list-group-item list-group-item-action">
                        <i class="material-icons mr-2">library_music</i>
                        Tracks
                    </a> --}}
                    {{-- <a href="#" class="list-group-item list-group-item-action">
                        <i class="material-icons mr-2">playlist_play</i>
                        Playlists
                    </a> --}}
                    {{-- <a href="#" class="list-group-item list-group-item-action">
                        <i class="material-icons mr-2">favorite</i>
                        Likes
                    </a> --}}
                    {{-- <div class="dropdown-divider"></div>
                    <a href="#" class="list-group-item list-group-item-action">
                        <i class="material-icons mr-2">help</i>
                        Need help?
                    </a> --}}
                    <a href="{{ route('auth.logout') }}" class="list-group-item list-group-item-action text-danger">
                        <i class="material-icons mr-2">exit_to_app</i>
                        Sign out
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Main Content Area with Top Navbar --}}
<div class="app-content white bg box-shadow-z2" role="main">
    {{-- Mobile Header --}}
    <div class="app-header hidden-lg-up white lt box-shadow-z1">
        <div class="navbar">
            <a href="{{ route('admin.home') }}" class="navbar-brand md">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="32" height="32">
                    <circle cx="24" cy="24" r="24" fill="rgba(255,255,255,0.2)" />
                    <circle cx="24" cy="24" r="22" fill="#1c202b" class="brand-color" />
                    <circle cx="24" cy="24" r="10" fill="#ffffff" />
                    <circle cx="13" cy="13" r="2" fill="#ffffff" class="brand-animate" />
                    <path d="M 14 24 L 24 24 L 14 44 Z" fill="#FFFFFF" />
                    <circle cx="24" cy="24" r="3" fill="#000000" />
                </svg>
                <img src="{{ asset('pulse-template/assets/images/logo.png') }}" alt="." class="hide" />
                <span class="hidden-folded inline">FestValley</span>
            </a>
            <ul class="nav navbar-nav pull-right">
                <li class="nav-item">
                    <a data-toggle="modal" data-target="#aside" class="nav-link">
                        <i class="material-icons">menu</i>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    {{-- Desktop Top Navbar --}}
    <nav class="navbar navbar-expand-lg" style="background-color: #363c43; border-radius: 0;">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1" style="color: #b9bbbd; font-weight: 500;">
                @hasSection('title')
                    <a href="{{ route('admin.home') }}" style="color: inherit; text-decoration: none; margin-right: 10px;">FestValley</a>
                    <span style="color: #7a7d80;"> | </span>
                    @yield('title')
                @else
                    <a href="{{ route('admin.home') }}" style="color: inherit; text-decoration: none;">FestValley</a>
                @endif
            </span>
        </div>
    </nav>
</div>
