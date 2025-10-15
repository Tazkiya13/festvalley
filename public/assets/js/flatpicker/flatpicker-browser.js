document.addEventListener('DOMContentLoaded', function() {
    function startFlatpickrTanggal() {
        window.initFlatpickrCustom('.flatpickr-browser');
    }
    if (window.liburMap) {
        // console.log('libur', window.liburMap);
        startFlatpickrTanggal();
    } else {
        window.addEventListener('liburReady', startFlatpickrTanggal);
    }
});
