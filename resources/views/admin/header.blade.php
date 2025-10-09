<!-- Admin Header -->
<header class="minimal-header">
    <div class="header-content">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="logo">Festvalley</a>
        
        <!-- Hamburger Menu Button for Mobile -->
        <button class="hamburger-btn" onclick="toggleMobileMenu()" aria-label="Toggle navigation">
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
        </button>
        
        <!-- Admin Navigation -->
        <div class="header-nav" id="headerNav">
            <!-- Mobile Navigation Menu -->
            <div class="mobile-nav-menu">
                @include('admin.partials.menu-items', ['menuType' => 'mobile'])
            </div>
            
            <!-- User Menu (Desktop) -->
            <div class="auth-section">
                <div class="user-menu">
                    <button class="dashboard-btn" onclick="toggleUserMenu();" id="userMenuBtn">
                        <i class="fa fa-user"></i>
                        {{ Auth::user()->name }}
                        <i class="fa fa-chevron-down" style="font-size: 0.8rem; margin-left: 0.5rem;"></i>
                    </button>
                    <div class="user-dropdown" id="userDropdown">
                        @include('admin.partials.menu-items', ['menuType' => 'desktop'])
                    </div>
                </div>
            </div>
            
            <!-- Mobile Menu Overlay -->
            <div class="mobile-menu-overlay" onclick="closeMobileMenu()"></div>
        </div>
    </div>
</header>

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
</script>
