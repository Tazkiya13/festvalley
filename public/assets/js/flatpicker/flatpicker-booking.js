document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi untuk semua input flatpickr-update-tanggal-booking
    document.querySelectorAll('.flatpickr-update-tanggal-booking').forEach(function(input) {
        var packageId = input.dataset.packageId;
        var availableDates = JSON.parse(input.dataset.availableDates || '[]');
        var availableIds = JSON.parse(input.dataset.availableIds || '[]');
        // Cari input hidden di dalam parent form
        var hidden = input.closest('form').querySelector('input[name="date_id"]');
        if (!hidden) return;

        function initFlatpickrBooking() {
            // Inisialisasi flatpickr jika belum
            if (typeof flatpickr !== 'undefined') {
                flatpickr(input, {
                    dateFormat: "Y-m-d",
                    altInput: true,
                    altFormat: "F j, Y",
                    showMonths: window.innerWidth < 768 ? 1 : 2,
                    minDate: "today",
                    enable: availableDates,
                    // disableMobile: true, // Removed to enable mobile support
                    onChange: function(selectedDates, dateStr) {
                        var idx = availableDates.indexOf(dateStr);
                        if (idx !== -1) {
                            hidden.value = availableIds[idx];
                        } else {
                            hidden.value = '';
                        }

                    },
                    onOpen: function(selectedDates, dateStr, instance) {
                        if (window.renderLiburBulan) setTimeout(() => window.renderLiburBulan(instance), 10);
                    },
                    onMonthChange: function(selectedDates, dateStr, instance) {
                        if (window.renderLiburBulan) window.renderLiburBulan(instance);
                    },
                    onDayCreate: function(dObj, dStr, fp, dayElem) {
                        if (!dayElem.dateObj) return;
                        const date = dayElem.dateObj;
                        const ymd  = [
                            date.getFullYear(),
                            window.pad(date.getMonth() + 1),
                            window.pad(date.getDate())
                        ].join('-');
                        const today = new Date();
                        today.setHours(0, 0, 0, 0);
                        if (date >= today && availableDates.includes(ymd)) {
                            if ((window.liburMap && window.liburMap[ymd]) || date.getDay() === 0) {
                                dayElem.style.color = '#d00';
                            }
                        }
                    }
                });
            }
        }

        if (window.liburMap) {
            initFlatpickrBooking();
        } else {
            window.addEventListener('liburReady', function() {
                initFlatpickrBooking();
            }, { once: true });
        }

        // Event handler jika user mengetik manual (fallback)
        input.addEventListener('change', function() {
            var val = input.value;
            var idx = availableDates.indexOf(val);
            if (idx !== -1) {
                hidden.value = availableIds[idx];
            } else {
                hidden.value = '';
            }
        });
    });
});
