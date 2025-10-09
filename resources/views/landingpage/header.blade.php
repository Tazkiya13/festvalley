<!-- Clean Minimal Header -->
<header class="minimal-header">
    <div class="header-content">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="logo">Festvalley</a>
        
        <!-- Hamburger Menu Button -->
        <button class="hamburger-btn" onclick="toggleMobileMenu()" aria-label="Toggle navigation">
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
        </button>
        
        <!-- Navigation -->
        <div class="header-nav" id="headerNav">
            <nav class="nav-minimal">
                <a href="{{ route('packages.browse') }}" 
                   class="{{ request()->routeIs('packages.browse') ? 'active' : '' }}"
                   onclick="closeMobileMenu()">
                    <i class="fa fa-compass"></i>
                    Browse Packages
                </a>
                @auth
                    <a href="{{ route('customer.bookings') }}" 
                       class="{{ request()->routeIs('customer.bookings') ? 'active' : '' }}"
                       onclick="closeMobileMenu()">
                        <i class="fa fa-calendar"></i>
                        My Bookings
                    </a>
                    <a href="{{ route('customer.invoices') }}" 
                       class="{{ request()->routeIs('customer.invoices') ? 'active' : '' }}"
                       onclick="closeMobileMenu()">
                        <i class="fa fa-file-text"></i>
                        My Invoices
                    </a>
                @endauth
            </nav>
            
            <!-- Auth Section -->
            <div class="auth-section">
                @guest
                    <button class="login-btn" onclick="openLoginModal(); closeMobileMenu();">
                        <i class="fa fa-user"></i>
                        Login
                    </button>
                @else
                    @if (Auth::user()->hasRole('Admin') && !request()->routeIs('customer.*'))
                        <a href="{{ route('admin.home') }}" class="dashboard-btn" onclick="closeMobileMenu()">
                            <i class="fa fa-dashboard"></i>
                            Admin Dashboard
                        </a>
                    @else
                        <div class="user-menu">
                            <button class="dashboard-btn" onclick="toggleUserMenu(); closeMobileMenu();" id="userMenuBtn">
                                <i class="fa fa-user"></i>
                                {{ Auth::user()->name }}
                                <i class="fa fa-chevron-down" style="font-size: 0.8rem; margin-left: 0.5rem;"></i>
                            </button>
                            <div class="user-dropdown" id="userDropdown">
                                @if(Auth::user()->hasRole('Artist'))
                                    <!-- Artist Dashboard Section -->
                                    <div class="dropdown-section">
                                        <h6 class="dropdown-header">Dashboard</h6>
                                        <a href="{{ route('artists.index') }}" class="dropdown-item">
                                            <i class="fa fa-user-tie"></i> Artist Dashboard
                                        </a>
                                    </div>
                                    <a href="{{ route('user.profile') }}" class="dropdown-item">
                                        <i class="fa fa-user-circle"></i> My Profile
                                    </a>
                                @else
                                    <!-- Customer Section -->
                                    <div class="dropdown-section">
                                        <h6 class="dropdown-header">My Account</h6>
                                        <a href="{{ route('customer.bookings') }}" class="dropdown-item">
                                            <i class="fa fa-calendar"></i> My Bookings
                                        </a>
                                        <a href="{{ route('customer.invoices') }}" class="dropdown-item">
                                            <i class="fa fa-file-text"></i> My Invoices
                                        </a>
                                        <a href="{{ route('user.profile') }}" class="dropdown-item">
                                            <i class="fa fa-user-circle"></i> My Profile
                                        </a>
                                    </div>
                                @endif
                                
                                <div class="dropdown-divider"></div>
                                <a href="{{ route('auth.logout') }}" class="dropdown-item logout-item">
                                    <i class="fa fa-sign-out"></i> Logout
                                </a>
                            </div>
                        </div>
                    @endif
                @endguest
            </div>
            
            <!-- Mobile Menu Overlay -->
            <div class="mobile-menu-overlay" onclick="closeMobileMenu()"></div>
        </div>
    </div>
</header>



<!-- Login Modal -->
<div id="loginModal" class="auth-modal">
    <div class="auth-modal-content">
        <button class="auth-close" onclick="closeLoginModal()">&times;</button>
        <div class="auth-modal-header">
            <h2 class="auth-modal-title">Welcome Back!</h2>
            <p class="auth-modal-subtitle">Sign in to access your dashboard</p>
        </div>
        
        <!-- Error Messages -->
        @if ($errors->any())
        <div class="error-block">
            <strong>Please fix these errors:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        
        <form method="POST" action="{{ route('auth.login') }}" id="loginForm">
            @csrf
            <div class="auth-form-group">
                <input type="email" class="auth-form-control" placeholder="Email Address" name="email" required>
            </div>
            <div class="auth-form-group">
                <input type="password" class="auth-form-control" placeholder="Password" name="password" required>
            </div>
            <button type="submit" class="auth-btn-submit">Sign In</button>
        </form>
        <div class="auth-modal-links">
            Don't have an account? <a href="#" onclick="switchToRegister()">Sign up here</a>
        </div>
    </div>
</div>

<!-- Register Modal -->
<div id="registerModal" class="auth-modal">
    <div class="auth-modal-content">
        <button class="auth-close" onclick="closeRegisterModal()">&times;</button>
        <div class="auth-modal-header">
            <h2 class="auth-modal-title">Join Festvalley!</h2>
            <p class="auth-modal-subtitle">Create your account to start booking artists</p>
        </div>
        
        <!-- Error Messages -->
        @if ($errors->any())
        <div class="error-block">
            <strong>Please fix these errors:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        
        <form action="{{ route('auth.register') }}" method="POST" id="registerForm">
            @csrf
            <div class="auth-form-group">
                <input type="text" class="auth-form-control" name="name" placeholder="Full Name" required>
            </div>
            <div class="auth-form-group">
                <input type="email" class="auth-form-control" name="email" placeholder="Email Address" required>
            </div>
            <div class="auth-form-group">
                <input type="password" class="auth-form-control" name="password" placeholder="Password" required>
            </div>
            <div class="auth-form-group">
                <select class="auth-form-control" name="role" required>
                    <option value="" disabled selected>Select Your Role</option>
                    <option value="Customer">Customer (Book Artists)</option>
                    <option value="Artist">Artist (Get Booked)</option>
                </select>
            </div>
            <button type="submit" class="auth-btn-submit">Create Account</button>
        </form>
        <div class="auth-modal-links">
            Already have an account? <a href="#" onclick="switchToLogin()">Sign in here</a>
        </div>
    </div>
</div>

<script>
// Mobile menu functions
function toggleMobileMenu() {
    const headerNav = document.getElementById('headerNav');
    const hamburgerBtn = document.querySelector('.hamburger-btn');
    
    headerNav.classList.toggle('mobile-open');
    hamburgerBtn.classList.toggle('active');
    
    // Prevent body scroll when menu is open
    if (headerNav.classList.contains('mobile-open')) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = 'auto';
    }
}

function closeMobileMenu() {
    const headerNav = document.getElementById('headerNav');
    const hamburgerBtn = document.querySelector('.hamburger-btn');
    
    headerNav.classList.remove('mobile-open');
    hamburgerBtn.classList.remove('active');
    document.body.style.overflow = 'auto';
}

// Close mobile menu on window resize
window.addEventListener('resize', function() {
    if (window.innerWidth > 768) {
        closeMobileMenu();
    }
});

function openLoginModal() {
    document.getElementById('loginModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeLoginModal() {
    document.getElementById('loginModal').classList.remove('show');
    document.body.style.overflow = 'auto';
}

function openRegisterModal() {
    document.getElementById('registerModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeRegisterModal() {
    document.getElementById('registerModal').classList.remove('show');
    document.body.style.overflow = 'auto';
}

function switchToRegister() {
    closeLoginModal();
    setTimeout(() => openRegisterModal(), 200);
}

function switchToLogin() {
    closeRegisterModal();
    setTimeout(() => openLoginModal(), 200);
}

function toggleUserMenu() {
    const userDropdown = document.getElementById('userDropdown');
    userDropdown.classList.toggle('show');
}

// Close user menu when clicking outside
document.addEventListener('click', function(event) {
    const userMenu = document.querySelector('.user-menu');
    if (userMenu && !userMenu.contains(event.target)) {
        const userDropdown = document.getElementById('userDropdown');
        if (userDropdown) {
            userDropdown.classList.remove('show');
        }
    }
});

// Close user menu when clicking outside
document.addEventListener('click', function(event) {
    const userMenu = document.querySelector('.user-menu');
    if (userMenu && !userMenu.contains(event.target)) {
        const userDropdown = document.getElementById('userDropdown');
        if (userDropdown) {
            userDropdown.classList.remove('show');
        }
    }
});

// Close modal when clicking outside
window.onclick = function(event) {
    const loginModal = document.getElementById('loginModal');
    const registerModal = document.getElementById('registerModal');
    
    if (event.target == loginModal) {
        closeLoginModal();
    }
    if (event.target == registerModal) {
        closeRegisterModal();
    }
}

// Handle form submissions with better UX
document.addEventListener('DOMContentLoaded', function() {
    // Check for errors and show appropriate modal
    @if ($errors->any())
        // Check which form had errors by examining the session
        var hasLoginErrors = @json($errors->has('email') || $errors->has('password'));
        var hasRegisterErrors = @json($errors->has('name') || $errors->has('role'));
        
        if (hasLoginErrors) {
            openLoginModal();
        } else if (hasRegisterErrors) {
            openRegisterModal();
        }
        
        // Add error styling to inputs
        var errorFields = @json($errors->keys());
        errorFields.forEach(function(field) {
            var input = document.querySelector('input[name="' + field + '"]');
            if (input) {
                input.classList.add('error');
                
                // Remove error class when user starts typing
                input.addEventListener('input', function() {
                    this.classList.remove('error');
                });
            }
        });
    @endif
    
    // Add form submission loading states
    var loginForm = document.getElementById('loginForm');
    var registerForm = document.getElementById('registerForm');
    
    if (loginForm) {
        loginForm.addEventListener('submit', function() {
            var submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="loading-spinner"></span> Logging in...';
            }
        });
    }
    
    if (registerForm) {
        registerForm.addEventListener('submit', function() {
            var submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="loading-spinner"></span> Creating Account...';
            }
        });
    }
});
</script>

<style>
/* Error message styling for modals */
.error-block {
    background-color: #fee2e2;
    border: 1px solid #fecaca;
    color: #dc2626;
    padding: 12px;
    border-radius: 6px;
    margin-bottom: 16px;
    font-size: 14px;
    animation: fadeIn 0.3s ease-in;
}

.error-block ul {
    margin: 0;
    padding-left: 20px;
}

.error-block li {
    margin-bottom: 4px;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Form input error styling */
.form-input.error {
    border-color: #dc2626;
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
}

.form-input:focus.error {
    border-color: #dc2626;
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.2);
}

/* Success message styling */
.success-block {
    background-color: #dcfce7;
    border: 1px solid #bbf7d0;
    color: #166534;
    padding: 12px;
    border-radius: 6px;
    margin-bottom: 16px;
    font-size: 14px;
    animation: fadeIn 0.3s ease-in;
}

/* Loading spinner */
.loading-spinner {
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 2px solid #f3f3f3;
    border-top: 2px solid #3498db;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-right: 8px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Button disabled state */
button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
</style>
