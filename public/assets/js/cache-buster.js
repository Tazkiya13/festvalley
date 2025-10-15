/**
 * Cache Buster for CSS and JS files
 * Forces browsers to reload the latest CSS and JS files
 */
document.addEventListener('DOMContentLoaded', function() {
    // Function to add timestamp to CSS and JS links
    function bustCache() {
        const timestamp = new Date().getTime();

        // Update CSS links
        const cssLinks = document.querySelectorAll('link[rel="stylesheet"]');
        cssLinks.forEach(link => {
            if (link.href && !link.href.includes('?v=')) {
                link.href = link.href + '?v=' + timestamp;
            }
        });

        // Update JS scripts
        const scripts = document.querySelectorAll('script[src]');
        scripts.forEach(script => {
            if (script.src && !script.src.includes('?v=')) {
                script.src = script.src + '?v=' + timestamp;
            }
        });

        console.log('Cache busting applied to CSS and JS files');
    }

    // Execute cache busting
    bustCache();
});
