// Chat widget functionality
document.addEventListener('DOMContentLoaded', function() {
    // Force reload CSS to apply new styles and fix visibility issues
    function reloadCSS() {
        const links = document.getElementsByTagName('link');
        for (let i = 0; i < links.length; i++) {
            if (links[i].rel === 'stylesheet' && links[i].href.includes('chat-widget.css')) {
                const href = links[i].href.split('?')[0]; // Remove any existing query params
                links[i].href = href + '?v=' + new Date().getTime();
                console.log('Reloaded chat widget CSS');
            }
        }
    }

    // Function removed as we're only using the header close button now
    function manageChatButtonVisibility() {
        // No-op - floating close button has been removed
    }    // Call initially
    reloadCSS();

    // No need for interval checking since we removed the floating close button
    // and are only using the header close button now
});
