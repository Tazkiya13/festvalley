<script>
function getImageUrl(image, defaultImage = '{{ asset("images/default.jpg") }}') {
  if (!image) return defaultImage;
  
  // If image already contains http/https, use as-is
  if (image.includes('http://') || image.includes('https://')) {
    return image;
  }
  
  // Otherwise, prepend storage path
  return '{{ asset("storage/") }}' + '/' + image;
}

// Apply proper image URLs after page load
document.addEventListener('DOMContentLoaded', function() {
  // Update all images with data-image attribute
  document.querySelectorAll('img[data-image]').forEach(img => {
    const image = img.getAttribute('data-image');
    img.src = getImageUrl(image);
  });
  
  // Update background images
  document.querySelectorAll('[data-bg-image]').forEach(element => {
    const image = element.getAttribute('data-bg-image');
    if (image) {
      const imageUrl = getImageUrl(image);
      element.style.backgroundImage = `url('${imageUrl}')`;
    }
  });
});
</script>
