// public/assets/js/flatpickr-libur.js
(function() {
    if (window.liburMap && window.liburData && window.renderLiburBulan) return; // prevent double fetch

    const pad = n => n.toString().padStart(2, '0');
    window.pad = pad;

    fetch('https://api-harilibur.vercel.app/api')
    .then(res => res.json())
    .then(data => {
        const liburMap = {};
        data.forEach(item => {
            const [y, m, d] = item.holiday_date.split('-');
            const key = `${y}-${pad(m)}-${pad(d)}`;
            liburMap[key] = item.holiday_name;
        });
        window.liburMap = liburMap;
        window.liburData = data;
        window.dispatchEvent(new Event('liburReady'));
    });
    window.renderLiburBulan = function(fp) {
        const data = window.liburData || [];
        // ...copy isi renderLiburBulan dari flatpicker.js...
        // Bulan & tahun sekarang
        const month = fp.currentMonth + 1;
        const year = fp.currentYear;
        // Bulan & tahun berikutnya
        let nextMonth = month + 1;
        let nextYear = year;
        if (nextMonth > 12) {
            nextMonth = 1;
            nextYear += 1;
        }
        // Filter hari libur bulan ini & bulan depan
        const liburBulanIni = data.filter(item => {
            const [y, m] = item.holiday_date.split('-');
            return parseInt(y) === year && parseInt(m) === month;
        });
        const liburBulanDepan = data.filter(item => {
            const [y, m] = item.holiday_date.split('-');
            return parseInt(y) === nextYear && parseInt(m) === nextMonth;
        });

        // Siapkan kontainer di bawah modal flatpickr
        let calendar = document.querySelector('.flatpickr-calendar.open') || document.querySelector('.flatpickr-calendar');
        if (!calendar) return;

        let infoBox = calendar.querySelector('#flatpickr-libur-info');
        if (!infoBox) {
            infoBox = document.createElement('div');
            infoBox.id = 'flatpickr-libur-info';
            infoBox.style.borderTop = '1px solid #eee';
            infoBox.style.margin = '8px 0 0 0';
            infoBox.style.padding = '10px 12px 6px 12px';
            infoBox.style.fontSize = '13px';
            infoBox.style.background = '#fafbfc';
            calendar.appendChild(infoBox);
        }

        // Render daftar dalam dua kolom
        let html = `
            <div style="display: flex; gap: 24px; justify-content: flex-start; align-items: flex-start;">
                <div style="flex:1; text-align:left;">
                    <div style="font-weight:bold; margin-bottom:4px;"></div>
                    ${
                        liburBulanIni.length
                            ? `<ul style="margin:6px 0 0 0;padding-left:18px; text-align:left;">${liburBulanIni
                                    .map(
                                        item => {
                                                const [, , d] = item.holiday_date.split('-');
                                                return `<li style="text-align:left;"><span style="color:#d00">${parseInt(d)}</span> - ${item.holiday_name}</li>`
                                        }
                                    )
                                    .join('')}</ul>`
                            : `<span style="color:#888">Tidak ada</span>`
                    }
                </div>
                <div style="flex:1; text-align:left;">
                    <div style="font-weight:bold; margin-bottom:4px;"></div>
                    ${
                        liburBulanDepan.length
                            ? `<ul style="margin:6px 0 0 0;padding-left:18px; text-align:left;">${liburBulanDepan
                                    .map(
                                        item => {
                                                const [, , d] = item.holiday_date.split('-');
                                             return `<li style="text-align:left;"><span style="color:#d00">${parseInt(d)}</span> - ${item.holiday_name}</li>`
                                        }
                                    )
                                    .join('')}</ul>`
                            : `<span style="color:#888">Tidak ada</span>`
                    }
                </div>
            </div>
        `;
        infoBox.innerHTML = html;
    };

    // window.dispatchEvent(new Event('liburReady'));
})();
