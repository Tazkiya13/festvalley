 $(document).ready(function() {
            // Initialize mobile functionality after DOM is ready
            setTimeout(function() {
                initializeMobileFeatures();
            }, 100);

            function initializeMobileFeatures() {
                // Update mobile header title based on current page
                updateMobileHeaderTitle();

                // Initialize hamburger menu
                initializeHamburgerMenu();

                // Window resize handler
                $(window).on('resize', function() {
                    setTimeout(function() {
                        updateMobileHeaderTitle();
                        initializeHamburgerMenu();
                    }, 100);
                });

                // AJAX complete handler
                $(document).ajaxComplete(function() {
                    setTimeout(updateMobileHeaderTitle, 100);
                });

                // Popstate handler
                $(window).on('popstate', function() {
                    setTimeout(updateMobileHeaderTitle, 100);
                });
            }

            function updateMobileHeaderTitle() {
                if ($(window).width() <= 991) {
                    let mobileTitle = 'Festvalley'; // Default title

                    // Try to get title from various sources
                    const currentUrl = window.location.pathname;
                    const pageTitle = document.title;
                    const navbarBrand = $('.navbar-brand').text().trim();
                    const breadcrumb = $('.breadcrumb-item.active').text().trim();
                    const pageHeader = $('h1, h2, h3').first().text().trim();

                    // Priority order: URL-based detection, section title, breadcrumb, page header
                    if (currentUrl.includes('/booking')) {
                        mobileTitle = 'Booking';
                    } else if (currentUrl.includes('/order')) {
                        mobileTitle = 'Order';
                    } else if (currentUrl.includes('/invoice')) {
                        mobileTitle = 'Invoice';
                    } else if (currentUrl.includes('/package')) {
                        mobileTitle = 'package';
                    } else if (currentUrl.includes('/artist')) {
                        mobileTitle = 'Artist';
                    } else if (currentUrl.includes('/customer')) {
                        mobileTitle = 'Customer';
                    } else if (currentUrl.includes('/user')) {
                        mobileTitle = 'User';
                    } else if (currentUrl.includes('/role')) {
                        mobileTitle = 'Role';
                    } else if (currentUrl.includes('/permission')) {
                        mobileTitle = 'Permission';
                    } else if (currentUrl.includes('/chat') || currentUrl.includes('/message')) {
                        mobileTitle = 'Chat';
                    } else if (currentUrl.includes('/admin/home') || currentUrl === '/admin' || currentUrl === '/admin/') {
                        mobileTitle = 'Dashboard';
                    } else {
                        // Try to extract from navbar brand or page title
                        if (navbarBrand && navbarBrand !== 'Festvalley' && navbarBrand.length <= 20) {
                            mobileTitle = navbarBrand;
                        } else if (pageTitle.includes('|')) {
                            // Extract from page title like "Booking | Festvalley"
                            const titleParts = pageTitle.split('|');
                            if (titleParts.length > 1) {
                                const firstPart = titleParts[0].trim();
                                if (firstPart.length <= 20 && firstPart !== 'Festvalley') {
                                    mobileTitle = firstPart;
                                }
                            }
                        } else if (breadcrumb && breadcrumb !== 'Festvalley' && breadcrumb.length <= 20) {
                            mobileTitle = breadcrumb;
                        } else if (pageHeader && pageHeader !== 'Festvalley' && pageHeader.length <= 20) {
                            const firstWord = pageHeader.split(' ')[0];
                            if (firstWord.length <= 20) {
                                mobileTitle = firstWord;
                            }
                        }
                    }

                    // Update mobile header title
                    $('.mobile-brand-text').text(mobileTitle);

                    console.log('Mobile title updated to:', mobileTitle);
                } else {
                    // On desktop, always show Festvalley (though mobile header is hidden)
                    $('.mobile-brand-text').text('Festvalley');
                }
            }

            function initializeHamburgerMenu() {
                // Remove any existing event handlers to prevent duplicates
                $('.mobile-hamburger-btn').off('click.hamburger');

                // Add new event handler with namespace
                $('.mobile-hamburger-btn').on('click.hamburger', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();

                    console.log('Hamburger menu clicked');

                    const modal = $('#aside');

                    if (modal.length) {
                        // Check if Bootstrap modal is available
                        if (typeof $.fn.modal !== 'undefined' && modal.modal) {
                            try {
                                modal.modal('show');
                                console.log('Modal opened via Bootstrap');
                            } catch (error) {
                                console.log('Bootstrap modal failed, using manual method');
                                openModalManually(modal);
                            }
                        } else {
                            console.log('Bootstrap modal not available, using manual method');
                            openModalManually(modal);
                        }
                    } else {
                        console.error('Modal #aside not found');
                    }

                    return false;
                });

                // Prevent modal from closing when clicking inside sidebar content
                $('#aside').off('click.modalContent').on('click.modalContent', function(e) {
                    e.stopPropagation();
                });

                $('#aside .navside, #aside .app-aside').off('click.modalContent').on('click.modalContent', function(e) {
                    e.stopPropagation();
                });

                // Initialize profile menu functionality
                initializeProfileMenu();
            }

            function openModalManually(modal) {
                // Manual modal opening
                modal.addClass('show').css('display', 'block');
                $('body').addClass('modal-open');

                // Add backdrop if it doesn't exist
                if ($('.modal-backdrop').length === 0) {
                    const backdrop = $('<div class="modal-backdrop fade show"></div>');
                    $('body').append(backdrop);

                    // Close modal when clicking backdrop
                    backdrop.off('click.closeModal').on('click.closeModal', function(e) {
                        e.preventDefault();
                        closeModalManually(modal);
                    });
                }

                console.log('Modal opened manually');
            }

            function closeModalManually(modal) {
                modal.removeClass('show').css('display', 'none');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                console.log('Modal closed manually');
            }

            function initializeProfileMenu() {
                console.log('Initializing profile menu...');

                // Remove any existing event handlers
                $('#profile-menu-trigger').off('click.profileMenu');

                // Add click handler for profile menu
                $('#profile-menu-trigger').on('click.profileMenu', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    console.log('Profile menu trigger clicked');

                    const isDesktop = $(window).width() >= 992;
                    const $modal = $('#profile-menu-modal');

                    if (isDesktop) {
                        // Desktop mode - position near profile button
                        console.log('Opening profile menu in desktop mode');

                        // Remove backdrop for desktop
                        $modal.modal({
                            backdrop: false,
                            keyboard: true
                        });

                        $modal.modal('show');

                        // Close modal when clicking outside on desktop
                        setTimeout(function() {
                            $(document).on('click.profileMenuDesktop', function(e) {
                                if (!$(e.target).closest('#profile-menu-modal').length &&
                                    !$(e.target).closest('#profile-menu-trigger').length) {
                                    $modal.modal('hide');
                                    $(document).off('click.profileMenuDesktop');
                                }
                            });
                        }, 100);

                    } else {
                        // Mobile mode - center modal
                        console.log('Opening profile menu in mobile mode');
                        $modal.modal({
                            backdrop: true,
                            keyboard: true
                        });
                        $modal.modal('show');
                    }

                    return false;
                });

                // Handle modal close events
                $('#profile-menu-modal').on('hidden.bs.modal', function() {
                    $(document).off('click.profileMenuDesktop');
                });

                // Handle logout link
                $('#profile-menu-modal a[href*="logout"]').on('click', function(e) {
                    e.preventDefault();
                    const href = $(this).attr('href');
                    console.log('Logout clicked, redirecting to:', href);

                    // Close modal first
                    $('#profile-menu-modal').modal('hide');

                    // Redirect after modal closes
                    setTimeout(function() {
                        window.location.href = href;
                    }, 300);
                });

                console.log('Profile menu initialization complete');
            }

            // Global close modal handlers
            $(document).off('click.closeModal').on('click.closeModal', '[data-dismiss="modal"]', function(e) {
                e.preventDefault();
                const modal = $('#aside');

                if (typeof $.fn.modal !== 'undefined' && modal.modal) {
                    modal.modal('hide');
                } else {
                    closeModalManually(modal);
                }
            });
        });
