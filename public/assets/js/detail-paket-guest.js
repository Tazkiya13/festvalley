 // Pinterest-style masonry layout for detail page
        document.addEventListener('DOMContentLoaded', function() {
            setupMasonryDetail();
        });

        window.addEventListener('load', function() {
            setupMasonryDetail();
        });

        function setupMasonryDetail() {
            const grid = document.getElementById('pinterestGridDetail');
            if (!grid) return;

            const getGridSettings = () => {
                const width = window.innerWidth;
                if (width <= 480) {
                    return { rowHeight: 5, rowGap: 10 };
                } else if (width <= 768) {
                    return { rowHeight: 6, rowGap: 12 };
                } else {
                    return { rowHeight: 8, rowGap: 16 };
                }
            };

            const { rowHeight, rowGap } = getGridSettings();
            const cards = grid.querySelectorAll('.pinterest-card-detail');

            if (cards.length === 0) return;

            // Reset all cards first
            cards.forEach(card => {
                card.style.gridRowEnd = 'auto';
                const imageWrapper = card.querySelector('.pinterest-image-wrap');
                if (imageWrapper) {
                    imageWrapper.style.height = 'auto';
                }
            });

            // Process cards
            cards.forEach((card, index) => {
                void card.offsetHeight;

                const content = card.querySelector('.pinterest-card-content');
                const imageWrapper = content?.querySelector('.pinterest-image-wrap');
                const image = content?.querySelector('.pinterest-image');

                if (!content || !imageWrapper || !image) return;

                // Pinterest-style height calculation
                const targetHeight = getPinterestHeightDetail(index, imageWrapper.offsetWidth || 200);
                imageWrapper.style.height = `${targetHeight}px`;

                void imageWrapper.offsetHeight;

                const finalContentHeight = content.getBoundingClientRect().height;
                const totalHeight = finalContentHeight + rowGap;
                const rowSpan = Math.ceil(totalHeight / (rowHeight + rowGap));
                const finalRowSpan = Math.max(rowSpan, 4);

                card.style.gridRowEnd = `span ${finalRowSpan}`;
            });
        }

        function getPinterestHeightDetail(index, wrapperWidth) {
            const seed1 = (index * 17 + 23) % 100;
            const seed2 = (index * 31 + 47) % 100;

            let category;
            if (seed1 < 25) {
                category = 'compact'; // Short pins
            } else if (seed1 < 55) {
                category = 'medium'; // Medium pins (most common)
            } else if (seed1 < 80) {
                category = 'tall'; // Tall pins
            } else {
                category = 'extra-tall'; // Very tall pins
            }

            let baseHeight;
            switch (category) {
                case 'compact':
                    baseHeight = 140 + (seed2 * 1.0); // 140-240px
                    break;
                case 'medium':
                    baseHeight = 200 + (seed2 * 1.5); // 200-350px
                    break;
                case 'tall':
                    baseHeight = 300 + (seed2 * 2.0); // 300-500px
                    break;
                case 'extra-tall':
                    baseHeight = 450 + (seed2 * 1.5); // 450-600px
                    break;
            }

            // Responsive adjustments
            const width = window.innerWidth;
            if (width <= 480) {
                baseHeight *= 0.8;
            } else if (width <= 768) {
                baseHeight *= 0.9;
            }

            // Natural variation
            const variation = Math.sin(index * 0.1) * 30;
            baseHeight += variation;

            // Bounds
            const minHeight = width <= 480 ? 100 : 120;
            const maxHeight = width <= 480 ? 400 : 600;

            return Math.max(minHeight, Math.min(maxHeight, Math.floor(baseHeight)));
        }

        // Debounced resize handler
        let resizeTimeout;
        function handleResizeDetail() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(setupMasonryDetail, 250);
        }        window.addEventListener('resize', handleResizeDetail);

        // Wishlist functionality
        // function addToWishlist(packageId) {
        //     // For guest users, you might want to show a login modal
        //     // For now, let's just show a notification
        //     const button = event.target.closest('.pinterest-btn-save');
        //     const icon = button.querySelector('i');

        //     if (icon.textContent === 'favorite_border') {
        //         icon.textContent = 'favorite';
        //         button.style.background = '#ff6b6b';
        //         button.style.borderColor = '#ff6b6b';
        //         button.style.color = 'white';

        //         // Show notification
        //         showNotification('Added to wishlist!', 'success');
        //     } else {
        //         icon.textContent = 'favorite_border';
        //         button.style.background = 'white';
        //         button.style.borderColor = '#667eea';
        //         button.style.color = '#667eea';

        //         showNotification('Removed from wishlist!', 'info');
        //     }
        // }

        // function showNotification(message, type) {
        //     // Simple notification function
        //     const notification = document.createElement('div');
        //     let bgColor = '#2196F3'; // default blue
        //     if (type === 'success') bgColor = '#4CAF50';
        //     if (type === 'error') bgColor = '#f44336';
        //     if (type === 'info') bgColor = '#2196F3';

        //     notification.style.cssText = `
        //         position: fixed;
        //         top: 20px;
        //         right: 20px;
        //         background: ${bgColor};
        //         color: white;
        //         padding: 12px 20px;
        //         border-radius: 8px;
        //         box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        //         z-index: 10000;
        //         font-weight: 600;
        //         opacity: 0;
        //         transform: translateX(100%);
        //         transition: all 0.3s ease;
        //     `;
        //     notification.textContent = message;
        //     document.body.appendChild(notification);

        //     // Animate in
        //     setTimeout(() => {
        //         notification.style.opacity = '1';
        //         notification.style.transform = 'translateX(0)';
        //     }, 10);

        //     // Remove after 3 seconds
        //     setTimeout(() => {
        //         notification.style.opacity = '0';
        //         notification.style.transform = 'translateX(100%)';
        //         setTimeout(() => document.body.removeChild(notification), 300);
        //     }, 3000);
        // }

        // Enhanced form validation for booking
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('bookingForm');
            const dateInput = document.querySelector('.pinterest-date-input');
            const submitButton = document.querySelector('.pinterest-btn-book[type="submit"]');

            if (form && dateInput && submitButton) {
                form.addEventListener('submit', function(e) {
                    const selectedDate = dateInput.value;
                    if (!selectedDate) {
                        e.preventDefault();
                        showNotification('Mohon pilih tanggal terlebih dahulu', 'error');
                        dateInput.focus();
                        return false;
                    }
                });

                // Enable/disable submit button based on date selection
                dateInput.addEventListener('change', function() {
                    if (this.value) {
                        submitButton.disabled = false;
                        submitButton.style.opacity = '1';
                    } else {
                        submitButton.disabled = true;
                        submitButton.style.opacity = '0.6';
                    }
                });

                // Initial state
                if (!dateInput.value) {
                    submitButton.disabled = true;
                    submitButton.style.opacity = '0.6';
                }
            }
        });
