 $(document).ready(function() {
            console.log('Order detail modal script loaded');

            // Remove any existing handlers
            $(document).off('click', '.btn-detail-modal');

            // Handle detail modal button click
            $(document).on('click', '.btn-detail-modal', function(e) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();

                console.log('Detail modal button clicked');

                var img = $(this).data('img');
                var price = $(this).data('price');
                var tanggal = $(this).data('tanggal');

                console.log('Modal data:', {
                    img: img,
                    price: price,
                    tanggal: tanggal
                });

                // Update modal content
                $('#detailModalImg').attr('src', img || '');
                $('#detailModalHarga').text(price || '-');
                $('#detailModalTanggal').text(tanggal || '-');

                // Show modal WITHOUT any backdrop
                $('#detailModal').modal({
                    backdrop: false, // No backdrop at all
                    keyboard: true,
                    focus: true
                }).modal('show');

                console.log('Modal shown without backdrop');

                return false;
            });

            // Handle modal close
            $('#detailModal').on('hidden.bs.modal', function() {
                console.log('Modal closed');
                // Remove any leftover backdrops
                $('.modal-backdrop').remove();
            });

            // Close modal when clicking close button
            $('#detailModal .close, #detailModal [data-dismiss="modal"]').on('click', function(e) {
                e.preventDefault();
                $('#detailModal').modal('hide');
            });

            // Prevent modal from closing when clicking inside modal content
            $('#detailModal .modal-content').on('click', function(e) {
                e.stopPropagation();
            });

            // Handle ESC key
            $(document).on('keydown', function(e) {
                if (e.keyCode === 27 && $('#detailModal').hasClass('show')) {
                    $('#detailModal').modal('hide');
                }
            });

            // Force remove backdrop after modal is shown
            $('#detailModal').on('shown.bs.modal', function() {
                console.log('Modal shown event triggered');

                // Force remove any backdrop
                $('.modal-backdrop').remove();

                // Ensure modal is visible
                $(this).css({
                    'display': 'block',
                    'z-index': '9999'
                });
                $(this).find('.modal-dialog').css({
                    'z-index': '10000',
                    'pointer-events': 'auto'
                });
                $(this).find('.modal-content').css({
                    'z-index': '10001',
                    'pointer-events': 'auto',
                    'position': 'relative'
                });
            });

            // Force remove backdrop on any backdrop creation
            $(document).on('DOMNodeInserted', function(e) {
                if ($(e.target).hasClass('modal-backdrop')) {
                    $(e.target).remove();
                }
            });
        });
