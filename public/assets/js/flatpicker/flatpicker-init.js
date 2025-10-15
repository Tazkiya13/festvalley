// public/assets/js/flatpickr-init.js
window.initFlatpickrCustom = function (selectorOrElements, options = {}) {
    const pad = window.pad || (n => n.toString().padStart(2, '0'));
    let elements = [];
    if (typeof selectorOrElements === 'string') {
        elements = document.querySelectorAll(selectorOrElements);
    } else if (selectorOrElements instanceof Element) {
        elements = [selectorOrElements];
    } else if (selectorOrElements instanceof NodeList || Array.isArray(selectorOrElements)) {
        elements = selectorOrElements;
    }
    // console.log(window.liburMap, window.liburData, window.renderLiburBulan);
    elements.forEach(function (input) {
        flatpickr(input, Object.assign({
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "F j, Y",
            showMonths: 2,
            disableMobile: false,
            minDate: "today",
            onDayCreate: function (dObj, dStr, fp, dayElem) {
                const date = dayElem.dateObj;
                const ymd = [
                    date.getFullYear(),
                    pad(date.getMonth() + 1),
                    pad(date.getDate())
                ].join('-');
                // console.log(window.liburMap);
                // Default: merah untuk hari libur nasional dan Minggu
                const today = new Date();
                today.setHours(0, 0, 0, 0); // Set waktu ke tengah malam untuk perbandingan yang tepat

                if (date >= today) {
                    if ((window.liburMap && window.liburMap[ymd]) || date.getDay() === 0) {
                        dayElem.style.color = '#d00';
                    }
                }
                // Custom onDayCreate
                if (typeof options.onDayCreate === 'function') {
                    options.onDayCreate(dObj, dStr, fp, dayElem);
                }
            },
            onOpen: function (selectedDates, dateStr, instance) {
                if (window.renderLiburBulan) setTimeout(() => window.renderLiburBulan(instance), 10);
                if (typeof options.onOpen === 'function') options.onOpen(selectedDates, dateStr, instance);
            },
            onMonthChange: function (selectedDates, dateStr, instance) {
                if (window.renderLiburBulan) window.renderLiburBulan(instance);
                if (typeof options.onMonthChange === 'function') options.onMonthChange(selectedDates, dateStr, instance);
            }
        }, options));
    });
};
