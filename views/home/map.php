<div class="page-header">
    <div class="container">
        <h1>Peta Sebaran Laporan Lingkungan</h1>
        <p>Peta interaktif titik permasalahan kebersihan dan lokasi aksi lingkungan di wilayah JABODETABEK.</p>
    </div>
</div>

<div class="container py-8">

    <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-6 shadow-sm mb-6">

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-5 pb-4 border-b border-gray-100">
            <div>
                <h2 class="text-base font-bold text-gray-900">Sebaran Titik Laporan & Lokasi Aksi</h2>
                <p class="text-xs text-gray-500 mt-0.5">Klik marker pada peta untuk melihat informasi detail dan perkembangan penanganan.</p>
            </div>

            
            <div class="flex flex-wrap items-center gap-3 text-xs">
                <div class="font-bold text-gray-700 text-[11px] uppercase tracking-wider">Status:</div>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-amber-50 text-amber-800 border border-amber-200/80 font-medium">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#FFC107]"></span> Menunggu
                </span>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-blue-50 text-blue-800 border border-blue-200/80 font-medium">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#2196F3]"></span> Terverifikasi
                </span>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-orange-50 text-orange-800 border border-orange-200/80 font-medium">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#FF9800]"></span> Ditangani
                </span>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200/80 font-medium">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#4CAF50]"></span> Selesai
                </span>
            </div>
        </div>

        
        <div id="map-container" style="height: 520px; width: 100%; min-height: 420px; border-radius: 14px;" class="border border-gray-200 shadow-inner z-0"></div>

    </div>

    <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 text-xs text-gray-600 space-y-1">
        <span class="font-bold text-gray-800 block">Keterangan Privasi Lokasi:</span>
        <p>
            Titik pada peta menunjukkan lokasi permasalahan lingkungan di tempat umum / ruang publik. Data pribadi pelapor dilindungi dan tidak ditampilkan pada peta publik.
        </p>
    </div>

</div>

<style>
.custom-map-pin {
    background: transparent;
    border: none;
}
.map-pin-anim {
    animation: pinDrop 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
@keyframes pinDrop {
    0% { transform: translateY(-14px) scale(0.85); opacity: 0; }
    100% { transform: translateY(0) scale(1); opacity: 1; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {

    
    function createLocationPinIcon(color) {
        return L.divIcon({
            className: 'custom-map-pin map-pin-anim',
            html: `
                <div style="position: relative; width: 34px; height: 42px; display: flex; flex-direction: column; align-items: center; justify-content: center; filter: drop-shadow(0 3px 6px rgba(0,0,0,0.32)); cursor: pointer;">
                    <svg width="34" height="42" viewBox="0 0 36 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18 0C8.05887 0 0 8.05887 0 18C0 28.5 15 42.5 18 44C21 42.5 36 28.5 36 18C36 8.05887 27.9411 0 18 0Z" fill="${color}"/>
                        <circle cx="18" cy="17" r="8" fill="white"/>
                        <path d="M18 11C14.6863 11 12 13.6863 12 17C12 20.3137 14.6863 23 18 23C21.3137 23 24 20.3137 24 17C24 13.6863 21.3137 11 18 11Z" fill="${color}"/>
                        <circle cx="18" cy="17" r="2.5" fill="white"/>
                    </svg>
                </div>
            `,
            iconSize: [34, 42],
            iconAnchor: [17, 42],
            popupAnchor: [0, -40]
        });
    }

    function getReportStatusColor(status) {
        switch (status) {
            case 'verified': return '#2196F3';
            case 'in_progress': return '#FF9800';
            case 'completed': return '#4CAF50';
            case 'rejected': return '#EF4444';
            default: return '#FFC107';
        }
    }

    const defaultLat = <?= (float)setting('map_default_lat', '-6.2088') ?>;
    const defaultLng = <?= (float)setting('map_default_lng', '106.8456') ?>;
    const defaultZoom = <?= (int)setting('map_default_zoom', '11') ?>;

    const map = L.map('map-container', {
        worldCopyJump: false,
        maxBounds: [[-90, -180], [90, 180]],
        maxBoundsViscosity: 1.0,
        minZoom: 2
    }).setView([defaultLat, defaultLng], defaultZoom);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19,
        noWrap: true
    }).addTo(map);

    
    setTimeout(() => {
        map.invalidateSize();
    }, 150);

    const allMarkers = [];

    
    fetch('<?= url('api/map-data') ?>')
        .then(response => response.json())
        .then(res => {
            if (res.success && res.data.length > 0) {
                res.data.forEach(item => {
                    if (!item.lat || !item.lng) return;

                    const statusColor = getReportStatusColor(item.status);
                    const marker = L.marker([item.lat, item.lng], {
                        icon: createLocationPinIcon(statusColor)
                    }).addTo(map);

                    const popupContent = `
                        <div style="min-width: 200px; font-family: inherit; padding: 4px;">
                            <div style="font-size: 10px; font-weight: 700; background: #FEE2E2; color: #991B1B; padding: 3px 7px; border-radius: 4px; margin-bottom: 5px; display: inline-block;">
                                LAPORAN WARGA
                            </div>
                            <span style="font-size: 10px; font-weight: 700; background: #F7EAE0; color: #5E3122; padding: 3px 6px; border-radius: 4px; display: inline-block;">
                                ${item.category}
                            </span>
                            <h4 style="font-size: 13px; font-weight: 700; margin: 6px 0 4px; color: #111827; line-height: 1.3;">
                                ${item.title}
                            </h4>
                            <p style="font-size: 11px; color: #4B5563; margin: 0 0 6px;">
                                Lokasi: ${item.location_name || item.region}
                            </p>
                            <div style="margin-bottom: 8px;">
                                <span style="font-size: 10px; font-weight: 700; background: ${statusColor}; color: white; padding: 2px 7px; border-radius: 4px;">
                                    ${item.status_label}
                                </span>
                            </div>
                            <a href="${item.url}" style="display: inline-block; background: #1D4533; color: white; text-decoration: none; font-size: 11px; font-weight: 700; padding: 5px 10px; border-radius: 6px;">
                                Buka Detail
                            </a>
                        </div>
                    `;

                    marker.bindPopup(popupContent);
                    allMarkers.push([item.lat, item.lng]);
                });
            }
        })
        .catch(err => {
            console.error('Failed to load map data:', err);
        });

    
    fetch('<?= url('api/cleanup-locations') ?>')
        .then(response => response.json())
        .then(res => {
            if (res.success && res.data.length > 0) {
                res.data.forEach(location => {
                    const lat = parseFloat(location.latitude);
                    const lng = parseFloat(location.longitude);
                    if (isNaN(lat) || isNaN(lng)) return;

                    const marker = L.marker([lat, lng], {
                        icon: createLocationPinIcon(location.statusColor)
                    }).addTo(map);

                    const popupContent = `
                        <div style="min-width: 220px; font-family: inherit; padding: 4px;">
                            <div style="font-size: 10px; font-weight: 700; background: #DBEAFE; color: #1E40AF; padding: 3px 7px; border-radius: 4px; margin-bottom: 5px; display: inline-block;">
                                LOKASI PEMBERSIHAN
                            </div>
                            <h4 style="font-size: 13px; font-weight: 700; margin: 6px 0 4px; color: #111827; line-height: 1.3;">
                                ${location.address}
                            </h4>
                            ${location.description ? `<p style="font-size: 11px; color: #4B5563; margin: 4px 0 6px; line-height: 1.4;">${location.description}</p>` : ''}
                            <div style="margin: 6px 0;">
                                <span style="font-size: 10px; font-weight: 700; background: ${location.statusColor}; color: white; padding: 2px 7px; border-radius: 4px;">
                                    ${location.statusLabel}
                                </span>
                            </div>
                            <div style="font-size: 10px; color: #9CA3AF; margin-top: 6px; padding-top: 4px; border-top: 1px solid #F3F4F6;">
                                Dicatat: ${new Date(location.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })}
                            </div>
                        </div>
                    `;

                    marker.bindPopup(popupContent);
                    allMarkers.push([lat, lng]);
                });

                
                if (allMarkers.length > 1) {
                    const bounds = L.latLngBounds(allMarkers);
                    map.fitBounds(bounds, { padding: [50, 50] });
                }
            }
        })
        .catch(err => {
            console.error('Failed to load cleanup locations:', err);
        });
});
</script>
