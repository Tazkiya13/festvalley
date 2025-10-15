document.addEventListener('DOMContentLoaded', function() {
  // Featured Artists Carousel
  const carousel = document.getElementById('featuredCarousel');
  const prevBtn = document.getElementById('featuredPrev');
  const nextBtn = document.getElementById('featuredNext');

  // Periksa apakah semua elemen carousel ada sebelum inisialisasi
  if (carousel && prevBtn && nextBtn) {
    // Jumlah item yang terlihat pada satu waktu (responsif)
    const getVisibleItems = () => {
      if (window.innerWidth < 768) return 1;
      if (window.innerWidth < 1024) return 2;
      return 3;
    };

    let currentPosition = 0;
    const cardWidth = 300 + 32; // width + gap

    // Fungsi untuk menggeser carousel
    const moveCarousel = (direction) => {
      const totalItems = carousel.children.length;
      const visibleItems = getVisibleItems();
      const maxPosition = totalItems - visibleItems;

      if (direction === 'next' && currentPosition < maxPosition) {
        currentPosition++;
      } else if (direction === 'prev' && currentPosition > 0) {
        currentPosition--;
      }

      carousel.style.transform = `translateX(-${currentPosition * cardWidth}px)`;
    };

    // Event listeners untuk tombol navigasi
    nextBtn.addEventListener('click', () => moveCarousel('next'));
    prevBtn.addEventListener('click', () => moveCarousel('prev'));

    // Optional: Auto-play carousel
    let autoplay = setInterval(() => moveCarousel('next'), 5000);

    // Pause autoplay saat hover
    carousel.addEventListener('mouseenter', () => clearInterval(autoplay));
    carousel.addEventListener('mouseleave', () => {
      autoplay = setInterval(() => moveCarousel('next'), 5000);
    });

    // Recalculate on window resize
    window.addEventListener('resize', () => {
      carousel.style.transform = `translateX(-${currentPosition * cardWidth}px)`;
    });
  }

  // Initialize masonry layout
  initMasonry();
});

// Load event for additional masonry setup after all images are loaded
window.addEventListener('load', function() {
  setupMasonry();
});

// Clean masonry implementation - single unified function

// Unified masonry implementation
function setupMasonry() {
  console.log("Setting up Pinterest-style masonry layout for discover");
  const grid = document.getElementById('pinterestGrid');
  if (!grid) {
    console.warn("Pinterest grid not found!");
    return;
  }

  // Define responsive row height and gap (must match CSS)
  const getGridSettings = () => {
    const width = window.innerWidth;
    if (width <= 480) {
      return { rowHeight: 5, rowGap: 10 };
    } else if (width <= 768) {
      return { rowHeight: 6, rowGap: 12 };
    } else if (width <= 1200) {
      return { rowHeight: 8, rowGap: 14 };
    } else {
      return { rowHeight: 8, rowGap: 16 };
    }
  };

  const { rowHeight, rowGap } = getGridSettings();

  // Process each card
  const cards = grid.querySelectorAll('.pinterest-card');
  if (cards.length === 0) {
    console.warn("No pinterest cards found in grid");
    return;
  }

  // Reset all cards first
  cards.forEach(card => {
    card.style.gridRowEnd = 'auto';
    const imageWrapper = card.querySelector('.pinterest-image-wrap');
    if (imageWrapper) {
      imageWrapper.style.height = 'auto';
    }
  });

  // Process cards after reset
  cards.forEach((card, index) => {
    // Force layout recalculation
    void card.offsetHeight;

    // Get the card content element
    const content = card.querySelector('.pinterest-card-content');
    const imageWrapper = content?.querySelector('.pinterest-image-wrap');
    const image = content?.querySelector('.pinterest-image');

    if (!content || !imageWrapper || !image) {
      console.warn(`Card ${index} missing required elements - content: ${!!content}, imageWrapper: ${!!imageWrapper}, image: ${!!image}`);
      return;
    }

    // Wait for image to load if not already loaded
    if (!image.complete || image.naturalHeight === 0) {
      image.addEventListener('load', () => processCard(card, content, imageWrapper, image, index, rowHeight, rowGap), { once: true });
      // Also set a fallback if image fails to load
      image.addEventListener('error', () => processCard(card, content, imageWrapper, image, index, rowHeight, rowGap), { once: true });
      return;
    }

    // Process the card immediately if image is ready
    processCard(card, content, imageWrapper, image, index, rowHeight, rowGap);
  });

  console.log('Pinterest masonry layout setup complete');
}

// Pinterest-style height distribution utility
function getPinterestHeight(index, wrapperWidth, aspectRatio) {
  // Create multiple seeds for better randomization
  const seed1 = (index * 17 + 23) % 100;
  const seed2 = (index * 31 + 47) % 100;
  const seed3 = (index * 13 + 71) % 100;

  // Pinterest height categories based on actual Pinterest analysis
  let category;
  if (seed1 < 20) {
    category = 'compact'; // 20% - Short pins
  } else if (seed1 < 50) {
    category = 'medium'; // 30% - Medium pins (most common)
  } else if (seed1 < 80) {
    category = 'tall'; // 30% - Tall pins
  } else {
    category = 'extra-tall'; // 20% - Very tall pins
  }

  let baseHeight;
  switch (category) {
    case 'compact':
      baseHeight = 160 + (seed2 * 1.2); // 160-280px
      break;
    case 'medium':
      baseHeight = 280 + (seed2 * 2.0); // 280-480px
      break;
    case 'tall':
      baseHeight = 480 + (seed2 * 2.4); // 480-720px
      break;
    case 'extra-tall':
      baseHeight = 720 + (seed2 * 1.5); // 720-870px
      break;
  }

  // Responsive adjustments
  const width = window.innerWidth;
  if (width <= 480) {
    baseHeight *= 0.75; // Smaller on mobile
  } else if (width <= 768) {
    baseHeight *= 0.85; // Slightly smaller on tablet
  }

  // Fine-tune based on aspect ratio but keep Pinterest randomness
  if (aspectRatio > 2.0) {
    // Very tall images
    baseHeight = Math.max(baseHeight, wrapperWidth * aspectRatio * 0.7);
  } else if (aspectRatio < 0.4) {
    // Very wide images
    baseHeight = Math.min(baseHeight, wrapperWidth * aspectRatio * 4);
  }

  // Add natural variation
  const variation = Math.sin(seed3 * 0.1) * 40; // ±40px organic variation
  baseHeight += variation;

  // Ensure bounds
  const minHeight = width <= 480 ? 120 : 160;
  const maxHeight = width <= 480 ? 600 : 900;

  return Math.max(minHeight, Math.min(maxHeight, Math.floor(baseHeight)));
}

function processCard(card, content, imageWrapper, image, index, rowHeight, rowGap) {
  // Reset image wrapper height to get natural dimensions
  imageWrapper.style.height = 'auto';

  // Get image natural dimensions and calculate aspect ratio
  const naturalWidth = image.naturalWidth || 300;
  const naturalHeight = image.naturalHeight || 200;
  const aspectRatio = naturalHeight / naturalWidth;

  // Get current wrapper width
  const wrapperWidth = imageWrapper.offsetWidth || 236; // fallback to CSS min-width

  // Use Pinterest-style height calculation
  const targetHeight = getPinterestHeight(index, wrapperWidth, aspectRatio);

  // Apply the calculated height
  imageWrapper.style.height = `${targetHeight}px`;

  // Force reflow to get updated dimensions
  void imageWrapper.offsetHeight;

  // Calculate final content height
  const finalContentHeight = content.getBoundingClientRect().height;

  // Calculate required grid row span with Pinterest-style calculation
  const totalHeight = finalContentHeight + rowGap;
  const rowSpan = Math.ceil(totalHeight / (rowHeight + rowGap));
  const finalRowSpan = Math.max(rowSpan, 4); // Minimum spans

  // Apply the calculated span
  card.style.gridRowEnd = `span ${finalRowSpan}`;

  console.log(`Pinterest Card ${index}: Category height=${targetHeight}px, Natural=${naturalWidth}x${naturalHeight} (ratio=${aspectRatio.toFixed(2)}), Final=${Math.floor(finalContentHeight)}px, Span=${finalRowSpan}`);
}

// Debounced resize handler
let resizeTimeout;
function handleResize() {
  clearTimeout(resizeTimeout);
  resizeTimeout = setTimeout(setupMasonry, 250);
}

// Initialize masonry layout
function initMasonry() {
  const grid = document.getElementById('pinterestGrid');
  if (grid) {
    // Initial setup
    setupMasonry();

    // Setup resize handler
    window.addEventListener('resize', handleResize);

    // Re-run after images load (for any late-loading images)
    grid.querySelectorAll('.pinterest-image').forEach(img => {
      if (!img.complete) {
        img.addEventListener('load', setupMasonry, { once: true });
      }
    });
  }
}
