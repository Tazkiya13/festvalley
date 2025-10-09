// Modern MainContent JavaScript for Festvalley
document.addEventListener('DOMContentLoaded', function() {

    // Initialize animations on scroll
    initScrollAnimations();

    // Initialize floating action button interactions
    initFloatingActionButton();

    // Initialize parallax effects
    initParallaxEffects();

    // Initialize smooth scrolling
    initSmoothScrolling();

    // Initialize hero carousel enhancements
    initHeroCarouselEnhancements();

    // Initialize mobile optimizations
    initMobileOptimizations();
});

// Scroll Animations
function initScrollAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animated');

                // Add stagger animation for multiple elements
                if (entry.target.classList.contains('stagger-animation')) {
                    const children = entry.target.children;
                    Array.from(children).forEach((child, index) => {
                        setTimeout(() => {
                            child.classList.add('animate__animated', 'animate__fadeInUp');
                        }, index * 100);
                    });
                }
            }
        });
    }, observerOptions);

    // Observe elements
    document.querySelectorAll('.animate-on-scroll').forEach(el => {
        observer.observe(el);
    });

    // Auto-add animation classes to sections
    document.querySelectorAll('.benefit-card, .feature-card, .trust-badge').forEach(el => {
        el.classList.add('animate-on-scroll');
        observer.observe(el);
    });
}

// Floating Action Button
function initFloatingActionButton() {
    const fab = document.querySelector('.fab-button');
    if (!fab) return;

    let lastScrollY = window.scrollY;

    window.addEventListener('scroll', () => {
        const currentScrollY = window.scrollY;

        // Hide/show FAB based on scroll direction
        if (currentScrollY > lastScrollY && currentScrollY > 100) {
            fab.style.transform = 'translateY(100px)';
            fab.style.opacity = '0';
        } else {
            fab.style.transform = 'translateY(0)';
            fab.style.opacity = '1';
        }

        lastScrollY = currentScrollY;
    });

    // Add click analytics or tracking here if needed
    fab.addEventListener('click', function(e) {
        // Add smooth transition effect
        this.style.transform = 'scale(0.95)';
        setTimeout(() => {
            this.style.transform = '';
        }, 150);

        // Track FAB clicks (add your analytics code here)
        // console.log('FAB clicked - Register action');
    });
}

// Parallax Effects
function initParallaxEffects() {
    const parallaxElements = document.querySelectorAll('.hero-slide');

    if (window.innerWidth > 768) { // Only on desktop
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.5;

            parallaxElements.forEach(el => {
                if (el.getBoundingClientRect().top < window.innerHeight && el.getBoundingClientRect().bottom > 0) {
                    el.style.transform = `translate3d(0, ${rate}px, 0)`;
                }
            });
        });
    }
}

// Smooth Scrolling
function initSmoothScrolling() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// Hero Carousel Enhancements
function initHeroCarouselEnhancements() {
    const carousel = document.querySelector('.hero-carousel');
    if (!carousel) return;

    // Auto-height adjustment
    const adjustCarouselHeight = () => {
        const activeSlide = carousel.querySelector('.owl-item.active .hero-slide');
        if (activeSlide) {
            const minHeight = window.innerWidth < 768 ? '60vh' : '70vh';
            activeSlide.style.minHeight = minHeight;
        }
    };

    // Listen for Owl Carousel events
    if (typeof $ !== 'undefined' && $.fn.owlCarousel) {
        $(carousel).on('changed.owl.carousel', adjustCarouselHeight);
        $(carousel).on('initialized.owl.carousel', adjustCarouselHeight);
    }

    // Adjust on window resize
    window.addEventListener('resize', adjustCarouselHeight);

    // Initial adjustment
    setTimeout(adjustCarouselHeight, 100);
}

// Mobile Optimizations
function initMobileOptimizations() {
    if (window.innerWidth <= 768) {
        // Disable parallax on mobile for better performance
        document.querySelectorAll('.hero-slide').forEach(el => {
            el.style.backgroundAttachment = 'scroll';
        });

        // Optimize animations for mobile
        const style = document.createElement('style');
        style.textContent = `
            .floating-cards { display: none !important; }
            .hero-visual { height: 200px !important; }
            .animate__animated { animation-duration: 0.6s !important; }
        `;
        document.head.appendChild(style);

        // Touch interactions for cards
        document.querySelectorAll('.benefit-card, .feature-card').forEach(card => {
            card.addEventListener('touchstart', function() {
                this.style.transform = 'scale(0.98)';
            });

            card.addEventListener('touchend', function() {
                this.style.transform = '';
            });
        });
    }
}

// Utility Functions
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Performance optimized scroll handler
const optimizedScroll = debounce(() => {
    // Any expensive scroll operations go here
}, 16); // ~60fps

window.addEventListener('scroll', optimizedScroll);

// Loading Animation
window.addEventListener('load', function() {
    // Remove any loading states
    document.body.classList.add('loaded');

    // Initialize any components that need DOM to be fully loaded
    setTimeout(() => {
        // Trigger initial animations
        document.querySelectorAll('.hero-content').forEach(el => {
            el.classList.add('animate__animated', 'animate__fadeInUp');
        });
    }, 300);
});

// Error Handling
window.addEventListener('error', function(e) {
    console.warn('MainContent JS Error:', e.error);
    // Graceful degradation - ensure basic functionality still works
});

// Preload Critical Images
function preloadCriticalImages() {
    const criticalImages = [
        '/pulse-template/assets/images/b6.jpg',
        '/pulse-template/assets/images/c1.jpg',
        '/pulse-template/assets/images/b3.jpg',
        '/pulse-template/assets/images/b1.jpg',
        '/pulse-template/assets/images/b7.jpg',
        '/pulse-template/assets/images/b10.jpg'
    ];

    criticalImages.forEach(src => {
        const img = new Image();
        img.src = src;
    });
}

// Initialize preloading
preloadCriticalImages();

// Export functions for potential external use
window.FestValley = {
    initScrollAnimations,
    initFloatingActionButton,
    initParallaxEffects,
    initSmoothScrolling
};
