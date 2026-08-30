<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-base font-bold text-gray-900">Tambah Lokasi Pembersihan</h2>
            <p class="text-xs text-gray-500">Tandai lokasi pembersihan pada peta dengan pendeteksi alamat otomatis & marker interaktif</p>
        </div>
        <a href="<?= url('admin/map-management') ?>" class="btn btn-outline btn-sm">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-gray-200 p-6 sm:p-8 shadow-sm">

                <form method="POST" action="<?= url('admin/map-management/store') ?>" id="locationForm" class="space-y-5">
                    <?= csrfField() ?>

                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label for="address" class="form-label mb-0">Alamat Lengkap Lokasi <span class="text-red-500">*</span></label>
                            <span class="text-[11px] text-emerald-600 font-semibold flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                Deteksi Otomatis Seperti Google Maps
                            </span>
                        </div>

                        <div class="relative">
                            <textarea id="address" name="address" rows="3" required class="form-control pr-10"
                                      autocomplete="off"
                                      placeholder="Ketik nama jalan, tempat, gedung, atau daerah... Contoh: Jl. Sudirman No. 123, Monas, Senayan Jakarta"><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>

                            <div id="addressSuggestions" class="hidden absolute left-0 right-0 top-full mt-1 bg-white border border-gray-200 rounded-xl shadow-xl z-50 max-h-56 overflow-y-auto divide-y divide-gray-100"></div>
                        </div>

                        <div class="mt-2.5 flex flex-wrap items-center gap-2">
                            <button type="button" id="geocodeBtn" class="btn btn-primary btn-sm py-2 px-3.5 text-xs inline-flex items-center gap-1.5 shadow-sm font-semibold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span id="geocodeBtnText">Tandai Titik dari Alamat</span>
                            </button>

                            <button type="button" id="geoCurrentBtn" onclick="useCurrentLocationForMap()" class="btn btn-sm py-2 px-3.5 text-xs inline-flex items-center gap-1.5 shadow-sm font-semibold bg-[#1D4533] hover:bg-[#14301F] text-white rounded-xl transition-all cursor-pointer">
                                <svg class="w-4 h-4 text-[#F9D2BA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span id="geoCurrentBtnText">Gunakan Lokasi Terkini</span>
                            </button>

                            <span id="geocodeFeedback" class="text-xs text-gray-500 font-medium"></span>
                        </div>
                        <p class="text-[11px] text-gray-400 mt-1.5">
                            Ketik alamat, atau klik <strong>"Gunakan Lokasi Terkini"</strong> untuk otomatis mendeteksi posisi GPS Anda dan langsung memunculkan marker pada peta.
                        </p>
                    </div>

                    <div class="bg-gray-50/90 p-4 rounded-xl border border-gray-200/90 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-gray-700 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                </svg>
                                Titik Koordinat GPS
                            </span>

                            <div class="flex items-center gap-1.5">
                                <button type="button" onclick="useCurrentLocationForMap()" class="text-xs text-emerald-700 hover:text-emerald-800 font-semibold inline-flex items-center gap-1 py-1 px-2.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 transition border border-emerald-200 cursor-pointer" title="Lacak koordinat GPS saya sekarang">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    GPS Saya
                                </button>
                                <button type="button" id="removeMarkerBtn" class="text-xs text-red-600 hover:text-red-700 font-semibold inline-flex items-center gap-1 py-1 px-2.5 rounded-lg hover:bg-red-50 transition border border-transparent hover:border-red-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Lepas Marker
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label for="latitude" class="form-label text-[11px] text-gray-500 mb-1">Latitude (Lintang)</label>
                                <input type="text" id="latitude" name="latitude" class="form-control font-mono text-xs bg-white"
                                       placeholder="Contoh: -6.208763" value="<?= htmlspecialchars($_POST['latitude'] ?? '') ?>">
                            </div>
                            <div>
                                <label for="longitude" class="form-label text-[11px] text-gray-500 mb-1">Longitude (Bujur)</label>
                                <input type="text" id="longitude" name="longitude" class="form-control font-mono text-xs bg-white"
                                       placeholder="Contoh: 106.845599" value="<?= htmlspecialchars($_POST['longitude'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="text-[11px] text-gray-500 flex items-center gap-1.5 pt-1">
                            <svg class="w-3.5 h-3.5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Tip: Anda juga bisa <strong>mengklik pada peta</strong> atau <strong>menggeser pin</strong> untuk mengubah titik secara presisi.</span>
                        </div>
                    </div>

                    <div>
                        <label for="status" class="form-label">Status Penanganan <span class="text-red-500">*</span></label>
                        <select id="status" name="status" required class="form-control">
                            <?php foreach ($statusOptions as $value => $label): ?>
                                <option value="<?= $value ?>" <?= ($value === 'pending') ? 'selected' : '' ?>>
                                    <?= $label ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="description" class="form-label">Keterangan Tambahan (Opsional)</label>
                        <textarea id="description" name="description" rows="3" class="form-control"
                                  placeholder="Keterangan mengenai progres pembersihan, tim relawan, jadwal penanganan, dll..."><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                        <a href="<?= url('admin/map-management') ?>" class="btn btn-outline py-2 px-4 text-xs">Batal</a>
                        <button type="submit" class="btn btn-primary py-2 px-5 text-xs font-bold shadow-md">Simpan Lokasi</button>
                    </div>
                </form>

            </div>
        </div>

        <div class="space-y-6">

            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Peta Titik Lokasi
                    </h3>
                    <span id="mapStatusBadge" class="text-[10px] bg-gray-100 text-gray-600 px-2.5 py-0.5 rounded-full font-semibold">
                        Belum Ada Titik
                    </span>
                </div>

                <div id="preview-map" style="height: 330px; width: 100%; border-radius: 12px;" class="border border-gray-200 shadow-inner mb-3 z-0"></div>

                <div class="p-3 bg-emerald-50/80 rounded-xl border border-emerald-100 text-[11px] text-emerald-900 space-y-1">
                    <div class="font-bold flex items-center gap-1 text-emerald-800">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Kemudahan Penandaan Lokasi:
                    </div>
                    <p class="text-emerald-700 leading-relaxed">
                        • Ketik alamat untuk mendeteksi koordinat otomatis.<br>
                        • Klik tombol <strong>"Lepas / Hapus Marker"</strong> untuk menghapus titik pin.<br>
                        • Klik peta atau geser pin marker untuk mengatur posisi manual.
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <h3 class="text-sm font-bold text-gray-900 mb-3">Warna Marker Berdasarkan Status</h3>
                <div class="space-y-2.5 text-xs">
                    <div class="flex items-center gap-3 p-2 rounded-xl bg-amber-50/50 border border-amber-100">
                        <span class="w-4 h-4 rounded-full flex-shrink-0 shadow-sm" style="background-color: #FFC107;"></span>
                        <div>
                            <div class="font-bold text-gray-800">Menunggu (Kuning)</div>
                            <div class="text-gray-500 text-[11px]">Lokasi ditandai, verifikasi awal</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-2 rounded-xl bg-blue-50/50 border border-blue-100">
                        <span class="w-4 h-4 rounded-full flex-shrink-0 shadow-sm" style="background-color: #2196F3;"></span>
                        <div>
                            <div class="font-bold text-gray-800">Terverifikasi (Biru)</div>
                            <div class="text-gray-500 text-[11px]">Siap dijadwalkan pembersihan</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-2 rounded-xl bg-orange-50/50 border border-orange-100">
                        <span class="w-4 h-4 rounded-full flex-shrink-0 shadow-sm" style="background-color: #FF9800;"></span>
                        <div>
                            <div class="font-bold text-gray-800">Ditangani (Orange)</div>
                            <div class="text-gray-500 text-[11px]">Proses pembersihan aktif</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-2 rounded-xl bg-emerald-50/50 border border-emerald-100">
                        <span class="w-4 h-4 rounded-full flex-shrink-0 shadow-sm" style="background-color: #4CAF50;"></span>
                        <div>
                            <div class="font-bold text-gray-800">Selesai (Hijau)</div>
                            <div class="text-gray-500 text-[11px]">Area bersih tuntas</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<style>
.custom-map-pin {
    background: transparent;
    border: none;
}
.map-pin-pulse {
    animation: pinBounce 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
@keyframes pinBounce {
    0% { transform: translateY(-20px) scale(0.8); opacity: 0; }
    60% { transform: translateY(4px) scale(1.05); opacity: 1; }
    100% { transform: translateY(0) scale(1); opacity: 1; }
}
.suggestion-item:hover {
    background-color: #f0fdf4;
}
</style>

<script>

function getStatusColor(status) {
    switch(status) {
        case 'pending': return '#FFC107';
        case 'verified': return '#2196F3';
        case 'in_progress': return '#FF9800';
        case 'completed': return '#4CAF50';
        default: return '#1D4533';
    }
}

function getStatusLabel(status) {
    switch(status) {
        case 'pending': return 'Menunggu';
        case 'verified': return 'Terverifikasi';
        case 'in_progress': return 'Ditangani';
        case 'completed': return 'Selesai Ditangani';
        default: return status;
    }
}

function createLocationPinIcon(color) {
    return L.divIcon({
        className: 'custom-map-pin map-pin-pulse',
        html: `
            <div style="position: relative; width: 36px; height: 44px; display: flex; flex-direction: column; align-items: center; justify-content: center; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.35)); cursor: grab;">
                <svg width="36" height="44" viewBox="0 0 36 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18 0C8.05887 0 0 8.05887 0 18C0 28.5 15 42.5 18 44C21 42.5 36 28.5 36 18C36 8.05887 27.9411 0 18 0Z" fill="${color}"/>
                    <circle cx="18" cy="17" r="8" fill="white"/>
                    <path d="M18 11C14.6863 11 12 13.6863 12 17C12 20.3137 14.6863 23 18 23C21.3137 23 24 20.3137 24 17C24 13.6863 21.3137 11 18 11Z" fill="${color}"/>
                    <circle cx="18" cy="17" r="2.5" fill="white"/>
                </svg>
            </div>
        `,
        iconSize: [36, 44],
        iconAnchor: [18, 44],
        popupAnchor: [0, -42]
    });
}

const defaultMapLat = <?= (float)setting('map_default_lat', '-6.2088') ?>;
const defaultMapLng = <?= (float)setting('map_default_lng', '106.8456') ?>;
const defaultMapZoom = <?= (int)setting('map_default_zoom', '11') ?>;
let previewMap = L.map('preview-map', {
    worldCopyJump: false,
    maxBounds: [[-90, -180], [90, 180]],
    maxBoundsViscosity: 1.0,
    minZoom: 2
}).setView([defaultMapLat, defaultMapLng], defaultMapZoom);
let locationMarker = null;

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors',
    maxZoom: 19,
    noWrap: true
}).addTo(previewMap);

function placeLocationMarker(lat, lng, addressText = '', shouldFly = true) {
    const status = document.getElementById('status').value;
    const color = getStatusColor(status);
    const pinIcon = createLocationPinIcon(color);

    if (locationMarker) {
        locationMarker.setLatLng([lat, lng]);
        locationMarker.setIcon(pinIcon);
    } else {
        locationMarker = L.marker([lat, lng], {
            icon: pinIcon,
            draggable: true
        }).addTo(previewMap);

        
        locationMarker.on('dragend', function(e) {
            const pos = e.target.getLatLng();
            document.getElementById('latitude').value = pos.lat.toFixed(6);
            document.getElementById('longitude').value = pos.lng.toFixed(6);
            updateMarkerPopup(pos.lat, pos.lng);
            if (window.showToast) {
                showToast('info', 'Posisi koordinat marker diperbarui.');
            }
        });
    }

    updateMarkerPopup(lat, lng, addressText);

    if (shouldFly) {
        previewMap.flyTo([lat, lng], 16, { animate: true, duration: 1.2 });
    }

    
    const badge = document.getElementById('mapStatusBadge');
    if (badge) {
        badge.className = 'text-[10px] bg-emerald-100 text-emerald-800 px-2.5 py-0.5 rounded-full font-semibold';
        badge.innerText = 'Marker Terpasang';
    }
}

function updateMarkerPopup(lat, lng, addressText = '') {
    if (!locationMarker) return;
    const addr = addressText || document.getElementById('address').value.trim() || 'Lokasi Pembersihan';
    const status = document.getElementById('status').value;
    const color = getStatusColor(status);
    const label = getStatusLabel(status);

    locationMarker.bindPopup(`
        <div style="font-family: inherit; font-size: 12px; min-width: 200px; padding: 3px;">
            <div style="font-weight: 700; color: #111827; margin-bottom: 4px; font-size: 13px;">Titik Lokasi Terpilih</div>
            <div style="color: #4B5563; font-size: 11px; margin-bottom: 6px; line-height: 1.35;">${addr}</div>
            <div style="display: inline-block; background: ${color}; color: #fff; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 4px;">
                ${label}
            </div>
            <div style="color: #9CA3AF; font-size: 10px; margin-top: 6px; font-family: monospace;">
                ${lat.toFixed(6)}, ${lng.toFixed(6)}
            </div>
        </div>
    `).openPopup();
}

function removeMarker() {
    if (locationMarker) {
        previewMap.removeLayer(locationMarker);
        locationMarker = null;
    }
    document.getElementById('latitude').value = '';
    document.getElementById('longitude').value = '';

    const badge = document.getElementById('mapStatusBadge');
    if (badge) {
        badge.className = 'text-[10px] bg-gray-100 text-gray-600 px-2.5 py-0.5 rounded-full font-semibold';
        badge.innerText = 'Belum Ada Titik';
    }

    const feedback = document.getElementById('geocodeFeedback');
    if (feedback) {
        feedback.className = 'text-xs text-gray-500 font-medium';
        feedback.innerText = '';
    }

    if (window.showToast) {
        showToast('info', 'Titik marker telah dilepas dari peta.');
    }
}

document.getElementById('removeMarkerBtn').addEventListener('click', removeMarker);

previewMap.on('click', function(e) {
    const lat = e.latlng.lat;
    const lng = e.latlng.lng;

    document.getElementById('latitude').value = lat.toFixed(6);
    document.getElementById('longitude').value = lng.toFixed(6);

    placeLocationMarker(lat, lng, '', false);

    if (window.showToast) {
        showToast('info', 'Titik lokasi dipilih pada peta.');
    }
});

document.getElementById('status').addEventListener('change', function() {
    const lat = parseFloat(document.getElementById('latitude').value);
    const lng = parseFloat(document.getElementById('longitude').value);
    if (!isNaN(lat) && !isNaN(lng)) {
        placeLocationMarker(lat, lng, '', false);
    }
});

function handleManualCoords() {
    const lat = parseFloat(document.getElementById('latitude').value);
    const lng = parseFloat(document.getElementById('longitude').value);
    if (!isNaN(lat) && !isNaN(lng)) {
        placeLocationMarker(lat, lng, '', true);
    }
}
document.getElementById('latitude').addEventListener('change', handleManualCoords);
document.getElementById('longitude').addEventListener('change', handleManualCoords);

async function performGeocode(isUserTriggered = false) {
    const addressInput = document.getElementById('address');
    const address = addressInput.value.trim();
    const btn = document.getElementById('geocodeBtn');
    const btnText = document.getElementById('geocodeBtnText');
    const feedback = document.getElementById('geocodeFeedback');

    hideSuggestions();

    if (!address) {
        if (isUserTriggered) {
            if (typeof alertWarning === 'function') {
                alertWarning('Silakan ketik alamat lokasi terlebih dahulu.', 'Alamat Kosong');
            } else {
                alert('Silakan ketik alamat lokasi terlebih dahulu.');
            }
        }
        return;
    }

    btn.disabled = true;
    btnText.innerText = 'Mendeteksi Lokasi...';
    feedback.className = 'text-xs text-amber-600 font-medium';
    feedback.innerText = 'Sedang mencari titik koordinat presisi...';

    let foundLocation = null;

    
    try {
        const response = await fetch('<?= url('admin/map-management/geocode') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ address: address })
        });

        const data = await response.json();
        if (data.success && data.latitude && data.longitude) {
            foundLocation = {
                lat: parseFloat(data.latitude),
                lng: parseFloat(data.longitude),
                displayName: data.display_name || address
            };
        }
    } catch (e) {
        console.warn('Backend geocode request failed, trying client fallback...', e);
    }

    
    if (!foundLocation) {
        try {
            const photonUrl = `https://photon.komoot.io/api/?q=${encodeURIComponent(address)}&limit=1&lang=id`;
            const photonRes = await fetch(photonUrl);
            const photonData = await photonRes.json();
            if (photonData && photonData.features && photonData.features.length > 0) {
                const feat = photonData.features[0];
                const coords = feat.geometry.coordinates;
                const props = feat.properties || {};
                const nameParts = [props.name, props.street, props.city, props.state].filter(Boolean);
                foundLocation = {
                    lat: parseFloat(coords[1]),
                    lng: parseFloat(coords[0]),
                    displayName: nameParts.length > 0 ? nameParts.join(', ') : address
                };
            }
        } catch (e) {
            console.warn('Client fallback geocode failed', e);
        }
    }

    if (foundLocation && !isNaN(foundLocation.lat) && !isNaN(foundLocation.lng)) {
        document.getElementById('latitude').value = foundLocation.lat.toFixed(6);
        document.getElementById('longitude').value = foundLocation.lng.toFixed(6);

        placeLocationMarker(foundLocation.lat, foundLocation.lng, foundLocation.displayName, true);

        feedback.className = 'text-xs text-emerald-600 font-semibold';
        feedback.innerText = 'Lokasi ditemukan dan marker terpasang.';

        if (isUserTriggered && window.showToast) {
            showToast('success', 'Titik lokasi berhasil ditandai pada peta.');
        }
    } else {
        feedback.className = 'text-xs text-red-500 font-semibold';
        feedback.innerText = 'Alamat tidak ditemukan di peta satelit.';

        const errorMsg = 'Alamat yang Anda masukkan tidak sesuai atau tidak dapat ditemukan pada peta satelit. Silakan periksa kembali penulisan alamat (cantumkan nama jalan, kelurahan/kecamatan, dan kota).\n\nAnda juga dapat menentukan titik secara langsung dengan mengklik pada peta.';
        if (typeof alertError === 'function') {
            alertError(errorMsg, 'Alamat Tidak Sesuai');
        } else if (typeof customAlert === 'function') {
            customAlert(errorMsg, 'error', 'Alamat Tidak Sesuai');
        } else {
            alert('Alamat Tidak Sesuai:\n' + errorMsg);
        }
    }

    btn.disabled = false;
    btnText.innerText = 'Tandai Titik dari Alamat';
}

document.getElementById('geocodeBtn').addEventListener('click', function() {
    performGeocode(true);
});

document.getElementById('address').addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        performGeocode(true);
    }
});

let debounceTimer = null;
const suggestionsContainer = document.getElementById('addressSuggestions');

function hideSuggestions() {
    suggestionsContainer.classList.add('hidden');
    suggestionsContainer.innerHTML = '';
}

document.getElementById('address').addEventListener('input', function() {
    const q = this.value.trim();
    clearTimeout(debounceTimer);

    if (q.length < 3) {
        hideSuggestions();
        return;
    }

    debounceTimer = setTimeout(async () => {
        try {
            const arcUrl = `https://geocode.arcgis.com/arcgis/rest/services/World/GeocodeServer/find?text=${encodeURIComponent(q)}&f=json&maxLocations=5&sourceCountry=IDN`;
            const res = await fetch(arcUrl);
            const data = await res.json();

            let candidates = [];
            if (data && data.candidates && data.candidates.length > 0) {
                candidates = data.candidates.map(c => ({
                    title: c.address,
                    lat: parseFloat(c.location.y),
                    lng: parseFloat(c.location.x),
                    subtext: c.attributes && c.attributes.Addr_type ? `(${c.attributes.Addr_type})` : ''
                }));
            } else {
                const osmUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(q)}&limit=5&countrycodes=id&addressdetails=1`;
                const osmRes = await fetch(osmUrl, { headers: { 'Accept-Language': 'id' } });
                const osmItems = await osmRes.json();
                if (osmItems && osmItems.length > 0) {
                    candidates = osmItems.map(item => ({
                        title: item.display_name,
                        lat: parseFloat(item.lat),
                        lng: parseFloat(item.lon),
                        subtext: item.type || ''
                    }));
                }
            }

            if (candidates.length > 0) {
                suggestionsContainer.innerHTML = candidates.map((item, idx) => `
                    <div class="suggestion-item p-2.5 cursor-pointer text-xs text-gray-700 flex items-start gap-2.5 transition border-b border-gray-50 last:border-0 hover:bg-emerald-50/70" data-idx="${idx}">
                        <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-3.5 h-3.5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-gray-900 leading-tight">${item.title}</div>
                            ${item.subtext ? `<div class="text-[10px] text-gray-400 mt-0.5">${item.subtext}</div>` : ''}
                        </div>
                    </div>
                `).join('');

                suggestionsContainer.classList.remove('hidden');

                
                document.querySelectorAll('.suggestion-item').forEach(el => {
                    el.addEventListener('click', function() {
                        const idx = parseInt(this.dataset.idx);
                        const selected = candidates[idx];
                        if (selected && !isNaN(selected.lat) && !isNaN(selected.lng)) {
                            document.getElementById('address').value = selected.title;
                            document.getElementById('latitude').value = selected.lat.toFixed(6);
                            document.getElementById('longitude').value = selected.lng.toFixed(6);

                            placeLocationMarker(selected.lat, selected.lng, selected.title, true);

                            const feedback = document.getElementById('geocodeFeedback');
                            feedback.className = 'text-xs text-emerald-600 font-semibold';
                            feedback.innerText = 'Lokasi terpilih dan marker terpasang.';

                            hideSuggestions();

                            if (window.showToast) {
                                showToast('success', 'Titik lokasi berhasil dipasang pada peta.');
                            }
                        }
                    });
                });
            } else {
                hideSuggestions();
            }
        } catch (e) {
            hideSuggestions();
        }
    }, 300);
});

document.addEventListener('click', function(e) {
    if (!document.getElementById('address').contains(e.target) && !suggestionsContainer.contains(e.target)) {
        hideSuggestions();
    }
});

async function useCurrentLocationForMap() {
    const btn = document.getElementById('geoCurrentBtn');
    const feedback = document.getElementById('geocodeFeedback');
    const addrInput = document.getElementById('address');
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');

    const originalHtml = btn ? btn.innerHTML : '';
    if (btn) {
        btn.disabled = true;
        btn.classList.add('opacity-80', 'cursor-wait');
        btn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-current inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Mencari titik GPS...</span>
        `;
    }

    if (feedback) {
        feedback.className = 'text-xs text-blue-600 font-medium';
        feedback.innerText = 'Menghubungkan sensor GPS perangkat...';
    }

    try {
        if (!navigator.geolocation) {
            throw new Error('Browser Anda tidak mendukung fitur Geolocation / GPS.');
        }

        const position = await new Promise((resolve, reject) => {
            navigator.geolocation.getCurrentPosition(
                pos => resolve(pos),
                err => {
                    if (err.code === err.TIMEOUT || err.code === err.POSITION_UNAVAILABLE) {
                        navigator.geolocation.getCurrentPosition(
                            pos => resolve(pos),
                            fallbackErr => reject(fallbackErr),
                            { enableHighAccuracy: false, timeout: 8000, maximumAge: 60000 }
                        );
                    } else {
                        reject(err);
                    }
                },
                { enableHighAccuracy: true, timeout: 12000, maximumAge: 0 }
            );
        });

        const lat = position.coords.latitude;
        const lng = position.coords.longitude;

        if (latInput) latInput.value = lat.toFixed(6);
        if (lngInput) lngInput.value = lng.toFixed(6);

        placeLocationMarker(lat, lng, 'Mencari alamat...', true);

        if (feedback) {
            feedback.className = 'text-xs text-blue-600 font-medium';
            feedback.innerText = `Titik GPS didapat (${lat.toFixed(6)}, ${lng.toFixed(6)}). Menerjemahkan alamat...`;
        }

        let resolvedAddress = '';
        try {
            const apiRes = await fetch(`<?= url('api/reverse-geocode') ?>?lat=${lat}&lng=${lng}`, {
                credentials: 'same-origin'
            });
            if (apiRes.ok) {
                const geoData = await apiRes.json();
                if (geoData && geoData.success && geoData.address) {
                    resolvedAddress = geoData.address;
                }
            }
        } catch (apiErr) {
            console.warn('API reverse-geocode error, trying client fallback:', apiErr);
        }

        if (!resolvedAddress) {
            try {
                const osmRes = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1&accept-language=id`);
                if (osmRes.ok) {
                    const osmData = await osmRes.json();
                    resolvedAddress = osmData.display_name ? osmData.display_name.replace(/, Indonesia$/i, '') : '';
                }
            } catch (osmErr) {
                console.warn('OSM fallback error:', osmErr);
            }
        }

        if (resolvedAddress && addrInput) {
            addrInput.value = resolvedAddress;
            updateMarkerPopup(lat, lng, resolvedAddress);
        }

        if (feedback) {
            feedback.className = 'text-xs text-emerald-600 font-semibold';
            feedback.innerText = 'Marker berhasil dipasang di titik lokasi GPS Anda!';
        }

        if (window.showToast) {
            window.showToast('success', 'Lokasi GPS berhasil terdeteksi dan marker telah dipasang pada peta!');
        }
    } catch (err) {
        console.error('GPS tracking error:', err);
        let msg = 'Gagal mendeteksi lokasi GPS.';
        if (err.code === 1) {
            msg = 'Izin akses lokasi GPS ditolak oleh browser. Harap klik ikon gembok / info di address bar browser dan aktifkan izin "Lokasi".';
        } else if (err.code === 2) {
            msg = 'Posisi GPS tidak dapat ditentukan. Pastikan sensor GPS atau Wi-Fi perangkat Anda aktif.';
        } else if (err.code === 3) {
            msg = 'Waktu pencarian GPS habis. Silakan periksa koneksi internet lalu coba kembali.';
        } else if (err.message) {
            msg = err.message;
        }

        if (feedback) {
            feedback.className = 'text-xs text-red-600 font-medium';
            feedback.innerText = msg;
        }

        if (window.showToast) {
            window.showToast('error', msg);
        } else {
            alert(msg);
        }
    } finally {
        if (btn) {
            btn.disabled = false;
            btn.classList.remove('opacity-80', 'cursor-wait');
            btn.innerHTML = originalHtml;
        }
    }
}

window.useCurrentLocationForMap = useCurrentLocationForMap;

(function() {
    const lat = parseFloat(document.getElementById('latitude').value);
    const lng = parseFloat(document.getElementById('longitude').value);
    if (!isNaN(lat) && !isNaN(lng)) {
        placeLocationMarker(lat, lng, '', true);
    }
})();
</script>
