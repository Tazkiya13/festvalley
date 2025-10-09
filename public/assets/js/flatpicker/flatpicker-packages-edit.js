document.addEventListener('DOMContentLoaded', function() {
    if (window.liburMap) {
        startFlatpickrEditpackage();
    } else {
        window.addEventListener('liburReady', startFlatpickrEditpackage);
    }
});

function startFlatpickrEditpackage() {
    const input = document.getElementById('available_dates_input');
    const badgeDiv = document.getElementById('selected-dates-badge');
    if (!input || !badgeDiv) return;

    let selectedDates = [];

    if (window.flatpickr) {
        flatpickr(input, {
            mode: "multiple",
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "F j, Y",
            showMonths: 2,
            minDate: "today",
            defaultDate: window.availableDates || [],
            onChange: function(dates, dateStr, instance) {
                selectedDates = dates.map(d => instance.formatDate(d, "Y-m-d"));
                updateBadges();
                setTimeout(() => { input.value = ''; }, 10);
            },
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

    function updateBadges() {
        badgeDiv.innerHTML = '';
        document.querySelectorAll('input[name="available_dates[]"]').forEach(e => e.remove());
        selectedDates.forEach((date, idx) => {
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'available_dates[]';
            hidden.value = date;
            badgeDiv.parentNode.insertBefore(hidden, badgeDiv);
            const badge = document.createElement('span');
            badge.className = 'label green me-2 mb-2';
            badge.style.display = 'inline-flex';
            badge.style.alignItems = 'center';
            badge.textContent = new Date(date).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
            const closeBtn = document.createElement('span');
            closeBtn.innerHTML = '&times;';
            closeBtn.style.cursor = 'pointer';
            closeBtn.style.marginLeft = '8px';
            closeBtn.style.color = '#fff';
            closeBtn.style.background = '#d00';
            closeBtn.style.borderRadius = '50%';
            closeBtn.style.width = '20px';
            closeBtn.style.height = '20px';
            closeBtn.style.display = 'inline-flex';
            closeBtn.style.alignItems = 'center';
            closeBtn.style.justifyContent = 'center';
            closeBtn.onclick = function() {
                selectedDates = selectedDates.filter(d => d !== date);
                input._flatpickr.setDate(selectedDates, true);
                updateBadges();
            };
            badge.appendChild(closeBtn);
            badgeDiv.appendChild(badge);
        });
    }

    if (window.availableDates && Array.isArray(window.availableDates)) {
        selectedDates = [...window.availableDates];
    }
    updateBadges();
    input.value = '';
}

// Bagian add_date_btn dan new_date tidak digunakan pada UI ini, jadi bisa diabaikan atau dihapus jika tidak dipakai.
