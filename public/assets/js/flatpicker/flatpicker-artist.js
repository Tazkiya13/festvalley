// File ini tidak lagi digunakan. Semua logika flatpickr dan badge/tag tanggal sudah dihandle oleh JS lain.
function startFlatpickrpackage() {
    document.querySelectorAll('.flatpickr-artist').forEach(function(input) {
        let availableDates = [];
        try {
            availableDates = JSON.parse(input.getAttribute('data-available-dates'));
        } catch (e) {}
        window.initFlatpickrCustom([input], {
            mode: "single",
            enable: availableDates,
            allowInput: false,
            disableMobile: false,
            onDayCreate: function(dObj, dStr, fp, dayElem) {
                const date = dayElem.dateObj;
                const ymd  = [
                    date.getFullYear(),
                    window.pad(date.getMonth() + 1),
                    window.pad(date.getDate())
                ].join('-');
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                if (
                    date >= today &&
                    availableDates.includes(ymd) &&
                    (
                        date.getDay() === 0 ||
                        (window.liburMap && window.liburMap[ymd])
                    )
                ) {
                    dayElem.style.color = '#d00';
                }
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', function() {
    if (window.liburMap) {
        startFlatpickrpackage();
    } else {
        window.addEventListener('liburReady', startFlatpickrpackage);
    }
});
