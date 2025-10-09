function startFlatpickrPackages() {
    document.querySelectorAll('.flatpickr-packages').forEach(function(input) {
        let availableDates = [];
        try {
            availableDates = JSON.parse(input.getAttribute('data-available-dates'));
        } catch (e) {}
        window.initFlatpickrCustom([input], {
            mode: "single",
            enable: availableDates,
            allowInput: false,
            disableMobile: true,
            onDayCreate: function(dObj, dStr, fp, dayElem) {
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
    });
}

// Hapus definisi ganda dan pemanggilan yang salah di akhir file
if (window.liburMap) {
    startFlatpickrPackages();
} else {
    window.addEventListener('liburReady', startFlatpickrPackages);
}
