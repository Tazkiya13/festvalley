// Masonry Layout Implementation for Browser Page
        document.addEventListener('DOMContentLoaded', function() {
            initializeMasonry();
            initializeCategoryFilter();
        });

        // Category Filter Implementation
        function initializeCategoryFilter() {
            const categoryCards = document.querySelectorAll('.category-browse-card');
            const contentCards = document.querySelectorAll('.pinterest-card');

            categoryCards.forEach(categoryCard => {
                categoryCard.addEventListener('click', function() {
                    const selectedCategory = this.getAttribute('data-category');

                    // Remove active class from all category cards
                    categoryCards.forEach(card => card.classList.remove('active'));

                    // Add active class to clicked category
                    this.classList.add('active');

                    // Filter content cards
                    filterCardsByCategory(selectedCategory);
                });
            });

            // Add "Show All" functionality - click anywhere outside categories
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.category-browse-card')) {
                    // If clicking outside categories, check if we should show all
                    const hasActiveCategory = document.querySelector('.category-browse-card.active');
                    if (hasActiveCategory && !e.target.closest('.pinterest-card')) {
                        showAllCards();
                        categoryCards.forEach(card => card.classList.remove('active'));
                    }
                }
            });
        }

        function filterCardsByCategory(category) {
            const contentCards = document.querySelectorAll('.pinterest-card');
            const categorySection = document.querySelector('.category-browse-section');
            const browserContent = document.querySelector('.browser-content');
            let visibleCount = 0;

            // Hide category section when filtering
            if (categorySection) {
                categorySection.style.display = 'none';
            }

            // Add filter active indicator
            addFilterIndicator(category);

            contentCards.forEach(card => {
                const cardCategory = card.getAttribute('data-category');

                if (cardCategory === category) {
                    card.style.display = 'block';
                    card.style.opacity = '1';
                    card.style.transform = 'scale(1)';
                    visibleCount++;
                } else {
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.8)';
                    setTimeout(() => {
                        if (card.style.opacity === '0') {
                            card.style.display = 'none';
                        }
                    }, 300);
                }
            });

            // Refresh masonry layout after filtering
            setTimeout(() => {
                setupMasonryLayout();
            }, 350);

            console.log(`Filtered to category: ${category}, showing ${visibleCount} cards`);
        }

        function showAllCards() {
            const contentCards = document.querySelectorAll('.pinterest-card');
            const categorySection = document.querySelector('.category-browse-section');

            // Show category section again
            if (categorySection) {
                categorySection.style.display = 'block';
            }

            // Remove filter indicator
            removeFilterIndicator();

            contentCards.forEach(card => {
                card.style.display = 'block';
                card.style.opacity = '1';
                card.style.transform = 'scale(1)';
            });

            // Refresh masonry layout
            setTimeout(() => {
                setupMasonryLayout();
            }, 100);

            console.log('Showing all cards');
        }

        function initializeMasonry() {
            const grid = document.getElementById('pinterestGrid');
            if (!grid) {
                console.error('Pinterest grid not found!');
                return;
            }

            // Wait for all images to load before setting up masonry
            const images = grid.querySelectorAll('.pinterest-image');
            let loadedCount = 0;
            const totalImages = images.length;

            if (totalImages === 0) {
                setupMasonryLayout();
                return;
            }

            function onImageLoad() {
                loadedCount++;
                if (loadedCount === totalImages) {
                    // All images loaded, setup masonry
                    setTimeout(setupMasonryLayout, 100);
                }
            }

            images.forEach(img => {
                if (img.complete && img.naturalHeight > 0) {
                    onImageLoad();
                } else {
                    img.addEventListener('load', onImageLoad);
                    img.addEventListener('error', onImageLoad); // Handle broken images
                }
            });

            // Debounced resize handler
            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(setupMasonryLayout, 300);
            });
        }

        function setupMasonryLayout() {
            const grid = document.getElementById('pinterestGrid');
            if (!grid) return;

            const cards = grid.querySelectorAll('.pinterest-card');
            if (cards.length === 0) return;

            // Constants from CSS
            const rowHeight = 10; // grid-auto-rows
            const rowGap = 16;    // grid-gap

            console.log('Setting up masonry for browser', cards.length, 'cards');

            cards.forEach((card, index) => {
                // Reset any previous grid settings
                card.style.gridRowEnd = 'auto';

                // Force layout recalculation
                void card.offsetHeight;

                // Get the card content element
                const content = card.querySelector('.pinterest-card-content');
                const imageWrapper = content?.querySelector('.pinterest-image-wrap');
                const image = content?.querySelector('.pinterest-image');

                if (!content || !imageWrapper || !image) {
                    console.warn(`Card ${index} missing required elements`);
                    return;
                }

                // Reset image wrapper height to get natural dimensions
                imageWrapper.style.height = 'auto';

                // Get image natural dimensions and calculate aspect ratio
                const naturalWidth = image.naturalWidth || 300;
                const naturalHeight = image.naturalHeight || 200;
                const aspectRatio = naturalHeight / naturalWidth;

                // Get current wrapper width
                const wrapperWidth = imageWrapper.offsetWidth;

                // Pinterest-style height patterns - more realistic and varied like real Pinterest
                const heightPatterns = [
                    { baseHeight: 380, variation: 1.2 },   // Medium-tall
                    { baseHeight: 320, variation: 0.8 },   // Medium
                    { baseHeight: 520, variation: 1.6 },   // Tall
                    { baseHeight: 360, variation: 1.0 },   // Medium-standard
                    { baseHeight: 450, variation: 1.4 },   // Medium-tall
                    { baseHeight: 280, variation: 0.6 },   // Shorter but not too short
                    { baseHeight: 420, variation: 1.3 },   // Standard-tall
                    { baseHeight: 580, variation: 1.8 },   // Very tall
                ];

                const pattern = heightPatterns[index % heightPatterns.length];

                // Calculate target height with more natural variation
                let targetHeight;

                // For images with extreme aspect ratios, respect them more
                if (aspectRatio > 1.5) {
                    // Tall images - let them be tall but controlled
                    targetHeight = Math.min(wrapperWidth * aspectRatio * 1.4, 650);
                } else if (aspectRatio < 0.6) {
                    // Wide images - keep them moderate height
                    targetHeight = Math.max(wrapperWidth * aspectRatio * 2.2, 280);
                } else {
                    // Normal aspect ratio - use pattern variations with more height
                    targetHeight = pattern.baseHeight * (0.8 + Math.random() * 0.8); // Increased randomness range
                }

                // Ensure reasonable bounds - increased overall height range for Pinterest authenticity
                targetHeight = Math.max(260, Math.min(700, targetHeight));

                // Apply the calculated height
                imageWrapper.style.height = `${Math.floor(targetHeight)}px`;

                // Force reflow to get updated dimensions
                void imageWrapper.offsetHeight;

                // Calculate final content height
                const finalContentHeight = content.getBoundingClientRect().height;

                // Calculate required grid row span
                const rowSpan = Math.ceil((finalContentHeight + rowGap) / (rowHeight + rowGap));
                const finalRowSpan = Math.max(rowSpan, 8); // Even lower minimum

                // Apply the calculated span
                card.style.gridRowEnd = `span ${finalRowSpan}`;

                console.log(`Card ${index}: Natural=${naturalWidth}x${naturalHeight} (ratio=${aspectRatio.toFixed(2)}), Target=${Math.floor(targetHeight)}px, Final=${Math.floor(finalContentHeight)}px, Span=${finalRowSpan}`);
            });

            console.log('Masonry layout setup complete');
        }

        // Add filter indicator when category is selected
        function addFilterIndicator(category) {
            // Remove existing indicator first
            removeFilterIndicator();

            const browserContent = document.querySelector('.browser-content');
            if (!browserContent) return;

            // Create filter indicator
            const indicator = document.createElement('div');
            indicator.className = 'category-filter-indicator';
            indicator.innerHTML = `
                <div class="filter-indicator-content">
                    <i class="material-icons">filter_list</i>
                    <span>Kategori: <strong>${category.charAt(0).toUpperCase() + category.slice(1)}</strong></span>
                    <button class="filter-clear-btn" onclick="clearCategoryFilter()">
                        <i class="material-icons">close</i>
                    </button>
                </div>
            `;

            // Insert before the title
            const title = browserContent.querySelector('.category-browse-title');
            if (title) {
                browserContent.insertBefore(indicator, title);
            }
        }

        // Remove filter indicator
        function removeFilterIndicator() {
            const existing = document.querySelector('.category-filter-indicator');
            if (existing) {
                existing.remove();
            }
        }

        // Clear category filter (global function for button onclick)
        function clearCategoryFilter() {
            showAllCards();
            document.querySelectorAll('.category-browse-card').forEach(card => {
                card.classList.remove('active');
            });
        }

        // Additional helper function to refresh masonry if needed
        function refreshMasonry() {
            setTimeout(setupMasonryLayout, 100);
        }

        // Expose function globally for debugging
        window.refreshMasonry = refreshMasonry;
