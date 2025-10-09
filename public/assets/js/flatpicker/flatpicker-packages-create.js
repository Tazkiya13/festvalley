// Inisialisasi flatpickr untuk input pertama saat halaman dimuat
function startFlatpickrCreatepackage() {
    document.querySelectorAll('.flatpickr-tanggal').forEach(function(input) {
        flatpickr(input, {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "F j, Y",
            showMonths: 2,
            minDate: "today",
            onDayCreate: function(dObj, dStr, fp, dayElem) {
                const date = dayElem.dateObj;
                const pad = window.pad || (n => n.toString().padStart(2, '0'));
                const ymd  = [
                    date.getFullYear(),
                    pad(date.getMonth() + 1),
                    pad(date.getDate())
                ].join('-');
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                if (date > today) {
                if ((window.liburMap && window.liburMap[ymd]) || date.getDay() === 0 ) {
                    dayElem.style.color = '#d00';
                }
            }
            },
            onOpen: function(selectedDates, dateStr, instance) {
                if (window.renderLiburBulan) setTimeout(() => window.renderLiburBulan(instance), 10);
            },
            onMonthChange: function(selectedDates, dateStr, instance) {
                if (window.renderLiburBulan) window.renderLiburBulan(instance);
            }
        });
    });

    // Fungsi tambah input tanggal baru
    window.addDateField = function() {
        const wrapper = document.getElementById('dates-wrapper');
        const input = document.createElement('input');
        input.type = 'text';
        input.name = 'available_dates[]';
        input.className = 'form-control mb-2 flatpickr-tanggal';
        wrapper.appendChild(input);
        flatpickr(input, {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "F j, Y",
            showMonths: 2,
            minDate: "today",
            onDayCreate: function(dObj, dStr, fp, dayElem) {
                const date = dayElem.dateObj;
                const pad = window.pad || (n => n.toString().padStart(2, '0'));
                const ymd  = [
                    date.getFullYear(),
                    pad(date.getMonth() + 1),
                    pad(date.getDate())
                ].join('-');
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                if (date >= today) {
                if ((window.liburMap && window.liburMap[ymd]) || date.getDay() === 0) {
                    dayElem.style.color = '#d00';
                }
                }
            },
            onOpen: function(selectedDates, dateStr, instance) {
                if (window.renderLiburBulan) setTimeout(() => window.renderLiburBulan(instance), 10);
            },
            onMonthChange: function(selectedDates, dateStr, instance) {
                if (window.renderLiburBulan) window.renderLiburBulan(instance);
            }
        });
    }
}

// document.addEventListener('DOMContentLoaded', function() {
    if (window.liburMap) {
        startFlatpickrCreatepackage();
    } else {
        window.addEventListener('liburReady', startFlatpickrCreatepackage);
    }
// });



