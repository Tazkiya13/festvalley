/**
 * FestValley - Main JavaScript
 * Artist Booking Platform
 */

// Global Configuration
const FestValley = {
    config: {
        apiUrl: '/api',
        wsUrl: 'ws://localhost:8080',
        csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
        currentUser: null,
        userRole: null
    },
    
    // Initialize the application
    init() {
        this.setupEventListeners();
        this.initializeComponents();
        this.loadUserState();
        this.setupAjaxDefaults();
    },
    
    // Setup global event listeners
    setupEventListeners() {
        document.addEventListener('DOMContentLoaded', () => {
            this.handlePageLoad();
        });
        
        // Handle form submissions globally
        document.addEventListener('submit', (e) => {
            if (e.target.hasAttribute('data-ajax')) {
                e.preventDefault();
                this.handleAjaxForm(e.target);
            }
        });
        
        // Handle navigation
        document.addEventListener('click', (e) => {
            if (e.target.hasAttribute('data-navigate')) {
                e.preventDefault();
                this.navigate(e.target.getAttribute('data-navigate'));
            }
        });
    },
    
    // Initialize UI components
    initializeComponents() {
        this.initializeSelect2();
        this.initializeDatePickers();
        this.initializeTooltips();
        this.initializeModals();
        this.setupLoadingStates();
    },
    
    // Setup AJAX defaults
    setupAjaxDefaults() {
        if (window.jQuery) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': this.config.csrfToken,
                    'Accept': 'application/json'
                },
                beforeSend: () => {
                    this.showLoading();
                },
                complete: () => {
                    this.hideLoading();
                }
            });
        }
    },
    
    // Handle page load
    handlePageLoad() {
        this.animateElements();
        this.loadInitialData();
        console.log('FestValley initialized successfully');
    },
    
    // Load user authentication state
    loadUserState() {
        // Check if user is authenticated
        const userMenu = document.getElementById('user-menu');
        const guestMenu = document.getElementById('guest-menu');
        
        // This would typically come from a server-side rendered variable
        // For demo purposes, we'll simulate it
        const isAuthenticated = localStorage.getItem('festvalley_auth') === 'true';
        const userRole = localStorage.getItem('festvalley_role') || 'customer';
        
        if (isAuthenticated) {
            userMenu?.classList.remove('d-none');
            guestMenu?.classList.add('d-none');
            this.showRoleSpecificMenus(userRole);
        } else {
            userMenu?.classList.add('d-none');
            guestMenu?.classList.remove('d-none');
        }
        
        this.config.userRole = userRole;
    },
    
    // Show role-specific menu items
    showRoleSpecificMenus(role) {
        const roleMenus = {
            'artist': '.artist-menu',
            'customer': '.customer-menu',
            'admin': '.admin-menu'
        };
        
        // Hide all role menus first
        Object.values(roleMenus).forEach(selector => {
            document.querySelectorAll(selector).forEach(el => {
                el.classList.add('d-none');
            });
        });
        
        // Show current role menu
        if (roleMenus[role]) {
            document.querySelectorAll(roleMenus[role]).forEach(el => {
                el.classList.remove('d-none');
            });
        }
    },
    
    // Navigation handling
    navigate(url) {
        this.showLoading();
        
        // Update URL
        history.pushState(null, '', url);
        
        // Load content based on URL
        this.loadPageContent(url);
    },
    
    // Load page content dynamically
    loadPageContent(url) {
        const contentArea = document.getElementById('main-content');
        
        // Simulate different page loads based on URL
        switch (url) {
            case '#browse':
                this.loadBrowsePage();
                break;
            case '#booking':
                this.loadBookingForm();
                break;
            case '#dashboard':
                this.loadDashboard();
                break;
            case '#admin':
                this.loadAdminPanel();
                break;
            default:
                this.loadHomePage();
        }
    },
    
    // Initialize Select2 dropdowns
    initializeSelect2() {
        if (window.jQuery && $.fn.select2) {
            $('.select2').select2({
                theme: 'default',
                width: '100%',
                placeholder: 'Select an option...'
            });
        }
    },
    
    // Initialize date pickers
    initializeDatePickers() {
        if (window.flatpickr) {
            const dateInputs = document.querySelectorAll('.date-picker');
            dateInputs.forEach(input => {
                flatpickr(input, {
                    dateFormat: 'Y-m-d',
                    minDate: 'today',
                    locale: {
                        firstDayOfWeek: 1
                    }
                });
            });
        }
    },
    
    // Initialize tooltips
    initializeTooltips() {
        if (window.bootstrap) {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
    },
    
    // Initialize modals
    initializeModals() {
        if (window.bootstrap) {
            const modalTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="modal"]'));
            modalTriggerList.map(function (modalTriggerEl) {
                return new bootstrap.Modal(modalTriggerEl);
            });
        }
    },
    
    // Setup loading states
    setupLoadingStates() {
        const loadingOverlay = document.getElementById('loading-overlay');
        if (!loadingOverlay) {
            // Create loading overlay if it doesn't exist
            const overlay = document.createElement('div');
            overlay.id = 'loading-overlay';
            overlay.className = 'loading-overlay d-none';
            overlay.innerHTML = `
                <div class="loading-spinner">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `;
            document.body.appendChild(overlay);
        }
    },
    
    // Show loading overlay
    showLoading() {
        const overlay = document.getElementById('loading-overlay');
        if (overlay) {
            overlay.classList.remove('d-none');
        }
    },
    
    // Hide loading overlay
    hideLoading() {
        const overlay = document.getElementById('loading-overlay');
        if (overlay) {
            overlay.classList.add('d-none');
        }
    },
    
    // Animate elements on page load
    animateElements() {
        const animatedElements = document.querySelectorAll('[data-animate]');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const animationType = entry.target.getAttribute('data-animate');
                    entry.target.classList.add(`animate-${animationType}`);
                    observer.unobserve(entry.target);
                }
            });
        });
        
        animatedElements.forEach(el => observer.observe(el));
    },
    
    // Handle AJAX form submissions
    handleAjaxForm(form) {
        const formData = new FormData(form);
        const url = form.action || window.location.href;
        const method = form.method || 'POST';
        
        fetch(url, {
            method: method,
            body: formData,
            headers: {
                'X-CSRF-TOKEN': this.config.csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.showNotification('Success', data.message, 'success');
                if (data.redirect) {
                    this.navigate(data.redirect);
                }
            } else {
                this.showNotification('Error', data.message || 'An error occurred', 'error');
            }
        })
        .catch(error => {
            console.error('Form submission error:', error);
            this.showNotification('Error', 'Network error occurred', 'error');
        });
    },
    
    // Show notification
    showNotification(title, message, type = 'info') {
        if (window.toastr) {
            toastr.options = {
                closeButton: true,
                debug: false,
                newestOnTop: true,
                progressBar: true,
                positionClass: 'toast-top-right',
                preventDuplicates: false,
                onclick: null,
                showDuration: '300',
                hideDuration: '1000',
                timeOut: '5000',
                extendedTimeOut: '1000',
                showEasing: 'swing',
                hideEasing: 'linear',
                showMethod: 'fadeIn',
                hideMethod: 'fadeOut'
            };
            
            toastr[type](message, title);
        } else {
            // Fallback notification
            this.showCustomNotification(title, message, type);
        }
    },
    
    // Custom notification system
    showCustomNotification(title, message, type) {
        const notification = document.createElement('div');
        notification.className = `notification-toast ${type} show`;
        notification.innerHTML = `
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <strong>${title}</strong>
                    <div>${message}</div>
                </div>
                <button type="button" class="btn-close" onclick="this.parentElement.parentElement.remove()"></button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 5000);
    },
    
    // Load initial data
    loadInitialData() {
        // Load featured artists
        this.loadFeaturedArtists();
        
        // Load user dashboard data if authenticated
        if (this.config.userRole) {
            this.loadUserDashboardData();
        }
    },
    
    // Load featured artists
    loadFeaturedArtists() {
        const artistsGrid = document.getElementById('artist-cards');
        if (!artistsGrid) return;
        
        // Simulate API call
        setTimeout(() => {
            const mockArtists = this.getMockArtistData();
            this.renderArtistCards(mockArtists, artistsGrid);
        }, 500);
    },
    
    // Get mock artist data
    getMockArtistData() {
        return [
            {
                id: 1,
                name: 'The Acoustic Duo',
                genre: 'Acoustic',
                location: 'Amsterdam, Netherlands',
                price: 500,
                rating: 4.8,
                image: 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f',
                description: 'Professional acoustic duo perfect for intimate events and weddings.'
            },
            {
                id: 2,
                name: 'Jazz Masters',
                genre: 'Jazz',
                location: 'Berlin, Germany',
                price: 750,
                rating: 4.9,
                image: 'https://images.unsplash.com/photo-1415201364774-f6f0bb35f28f',
                description: 'Experienced jazz ensemble bringing classic and modern jazz to your events.'
            },
            {
                id: 3,
                name: 'Electronic Vibes',
                genre: 'Electronic',
                location: 'London, UK',
                price: 600,
                rating: 4.7,
                image: 'https://images.unsplash.com/photo-1571330735066-03aaa9429d89',
                description: 'Modern electronic music for parties, festivals, and corporate events.'
            }
        ];
    },
    
    // Render artist cards
    renderArtistCards(artists, container) {
        container.innerHTML = '';
        
        artists.forEach(artist => {
            const cardHtml = `
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card artist-card hover-lift">
                        <img src="${artist.image}?w=400&h=250&fit=crop" class="card-img-top" alt="${artist.name}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title">${artist.name}</h5>
                                <span class="badge bg-primary">${artist.genre}</span>
                            </div>
                            <p class="card-text text-muted mb-2">
                                <i class="material-icons align-middle me-1" style="font-size: 16px;">location_on</i>
                                ${artist.location}
                            </p>
                            <p class="card-text">${artist.description}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="price-tag">€${artist.price}/event</div>
                                <div class="text-muted">
                                    <i class="material-icons align-middle text-warning" style="font-size: 16px;">star</i>
                                    ${artist.rating}
                                </div>
                            </div>
                            <button class="btn btn-primary w-100 mt-3" onclick="FestValley.bookArtist(${artist.id})">
                                Send Request
                            </button>
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', cardHtml);
        });
    },
    
    // Book artist
    bookArtist(artistId) {
        if (!this.config.userRole) {
            this.showNotification('Authentication Required', 'Please log in to book an artist', 'warning');
            this.showLoginModal();
            return;
        }
        
        this.navigate('#booking?artist=' + artistId);
    },
    
    // Show login modal
    showLoginModal() {
        // Create and show login modal
        const modalHtml = `
            <div class="modal fade" id="loginModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Login Required</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>Please log in to continue with booking.</p>
                            <div class="d-grid gap-2">
                                <button class="btn btn-primary" onclick="FestValley.navigate('#login')">Login</button>
                                <button class="btn btn-outline-secondary" onclick="FestValley.navigate('#register')">Create Account</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.getElementById('modals-container').innerHTML = modalHtml;
        const modal = new bootstrap.Modal(document.getElementById('loginModal'));
        modal.show();
    },
    
    // Load user dashboard data
    loadUserDashboardData() {
        // This would typically fetch from API
        console.log('Loading dashboard data for role:', this.config.userRole);
    },
    
    // Search functionality
    handleSearch(query, filters = {}) {
        this.showLoading();
        
        // Simulate API search
        setTimeout(() => {
            const results = this.getMockArtistData().filter(artist => 
                artist.name.toLowerCase().includes(query.toLowerCase()) ||
                artist.genre.toLowerCase().includes(query.toLowerCase()) ||
                artist.location.toLowerCase().includes(query.toLowerCase())
            );
            
            this.displaySearchResults(results);
            this.hideLoading();
        }, 1000);
    },
    
    // Display search results
    displaySearchResults(results) {
        const artistsGrid = document.getElementById('artist-cards');
        if (!artistsGrid) return;
        
        if (results.length === 0) {
            artistsGrid.innerHTML = `
                <div class="col-12 text-center py-5">
                    <i class="material-icons" style="font-size: 48px; color: #ccc;">search_off</i>
                    <h4 class="text-muted mt-3">No artists found</h4>
                    <p class="text-muted">Try adjusting your search criteria</p>
                </div>
            `;
        } else {
            this.renderArtistCards(results, artistsGrid);
        }
    },
    
    // Utility functions
    utils: {
        // Format currency
        formatCurrency(amount, currency = 'EUR') {
            return new Intl.NumberFormat('en-EU', {
                style: 'currency',
                currency: currency
            }).format(amount);
        },
        
        // Format date
        formatDate(date, options = {}) {
            const defaultOptions = {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            return new Date(date).toLocaleDateString('en-US', { ...defaultOptions, ...options });
        },
        
        // Debounce function
        debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        },
        
        // Generate unique ID
        generateId(prefix = 'id') {
            return `${prefix}_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`;
        }
    }
};

// Search form handling
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('searchForm');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const query = formData.get('genre') || formData.get('location') || '';
            const filters = {
                genre: formData.get('genre'),
                location: formData.get('location'),
                date: formData.get('date')
            };
            
            FestValley.handleSearch(query, filters);
        });
    }
});

// File upload handling
function handleFileUpload(input, previewContainer, maxFiles = 5) {
    const files = Array.from(input.files);
    
    if (files.length > maxFiles) {
        FestValley.showNotification('Error', `Maximum ${maxFiles} files allowed`, 'error');
        return;
    }
    
    previewContainer.innerHTML = '';
    
    files.forEach((file, index) => {
        if (file.type.startsWith('image/') || file.type.startsWith('video/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewHtml = `
                    <div class="file-preview-item">
                        ${file.type.startsWith('image/') 
                            ? `<img src="${e.target.result}" alt="Preview">`
                            : `<video src="${e.target.result}" controls></video>`
                        }
                        <button type="button" class="file-preview-remove" onclick="removeFilePreview(this, ${index})">
                            <i class="material-icons">close</i>
                        </button>
                    </div>
                `;
                previewContainer.insertAdjacentHTML('beforeend', previewHtml);
            };
            reader.readAsDataURL(file);
        }
    });
}

function removeFilePreview(button, index) {
    button.parentElement.remove();
}

// Initialize FestValley when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    FestValley.init();
});

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = FestValley;
} else {
    window.FestValley = FestValley;
}
