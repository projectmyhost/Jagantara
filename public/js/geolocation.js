(function () {
    'use strict';

    function getApiUrl() {
        const base = document.querySelector('base')?.getAttribute('href') || '/';
        const cleanBase = base.endsWith('/') ? base : base + '/';
        return cleanBase + 'api/reverse-geocode';
    }

    function triggerEvents(el) {
        if (!el) return;
        el.dispatchEvent(new Event('input', { bubbles: true }));
        el.dispatchEvent(new Event('change', { bubbles: true }));
    }

    function getCurrentPosition() {
        return new Promise((resolve, reject) => {
            if (!navigator.geolocation) {
                reject(new Error('Browser Anda tidak mendukung fitur Geolocation / GPS.'));
                return;
            }

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
    }

    async function reverseGeocodeCoords(lat, lng) {
        const url = getApiUrl() + '?lat=' + encodeURIComponent(lat) + '&lng=' + encodeURIComponent(lng);

        try {
            const resp = await fetch(url, { credentials: 'same-origin' });
            if (resp.ok) {
                const data = await resp.json();
                if (data && data.success) {
                    return data;
                }
            }
        } catch (e) {
            console.warn('Backend reverse geocode error, using browser fallback:', e);
        }

        return clientSideFallbackGeocode(lat, lng);
    }

    async function clientSideFallbackGeocode(lat, lng) {
        try {
            const osmUrl = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1&accept-language=id`;
            const osmResp = await fetch(osmUrl, { headers: { 'Accept-Language': 'id' } });
            if (osmResp.ok) {
                const data = await osmResp.json();
                const addr = data.display_name || '';
                const cleanAddr = addr.replace(/, Indonesia$/i, '');
                return {
                    success: true,
                    address: cleanAddr,
                    region_id: null,
                    region_name: null,
                    latitude: lat,
                    longitude: lng,
                    details: data.address || {}
                };
            }
        } catch (e) {
            try {
                const arcUrl = `https://geocode.arcgis.com/arcgis/rest/services/World/GeocodeServer/reverseGeocode?f=json&location=${lng},${lat}`;
                const arcResp = await fetch(arcUrl);
                if (arcResp.ok) {
                    const arcData = await arcResp.json();
                    if (arcData?.address?.Match_addr) {
                        return {
                            success: true,
                            address: arcData.address.Match_addr,
                            region_id: null,
                            region_name: null,
                            latitude: lat,
                            longitude: lng,
                            details: arcData.address
                        };
                    }
                }
            } catch (arcErr) {
                console.warn('Fallback ArcGIS failed too', arcErr);
            }
        }

        return {
            success: true,
            address: `Titik Koordinat: ${lat.toFixed(6)}, ${lng.toFixed(6)}`,
            region_id: null,
            region_name: null,
            latitude: lat,
            longitude: lng
        };
    }

    function autoSetRegion(regionId, regionName, config) {
        const searchInput = typeof config.regionSearch === 'string'
            ? document.querySelector(config.regionSearch)
            : config.regionSearch;

        const hiddenIdInput = typeof config.regionId === 'string'
            ? document.querySelector(config.regionId)
            : config.regionId;

        if (hiddenIdInput && hiddenIdInput.tagName && hiddenIdInput.tagName.toLowerCase() === 'select') {
            const selectEl = hiddenIdInput;
            let matched = false;

            if (regionId) {
                for (let i = 0; i < selectEl.options.length; i++) {
                    if (String(selectEl.options[i].value) === String(regionId)) {
                        selectEl.selectedIndex = i;
                        matched = true;
                        break;
                    }
                }
            }

            if (!matched && regionName) {
                const target = regionName.toLowerCase();
                for (let i = 0; i < selectEl.options.length; i++) {
                    const optText = selectEl.options[i].textContent.toLowerCase();
                    if (optText.includes(target) || target.includes(optText)) {
                        selectEl.selectedIndex = i;
                        matched = true;
                        break;
                    }
                }
            }

            if (matched) {
                triggerEvents(selectEl);
            }
            return matched;
        }

        if (hiddenIdInput) {
            if (regionId) {
                hiddenIdInput.value = regionId;
                triggerEvents(hiddenIdInput);
            }
        }

        if (searchInput && regionName) {
            searchInput.value = regionName;
            triggerEvents(searchInput);
        }

        if ((!regionId || !hiddenIdInput?.value) && Array.isArray(window.REGIONS) && regionName) {
            const lowerName = regionName.toLowerCase();
            const found = window.REGIONS.find(r => r.name.toLowerCase().includes(lowerName) || lowerName.includes(r.name.toLowerCase()));
            if (found) {
                if (hiddenIdInput) hiddenIdInput.value = found.id;
                if (searchInput) searchInput.value = found.name;
                triggerEvents(hiddenIdInput);
                triggerEvents(searchInput);
            }
        }

        return true;
    }

    function getFriendlyErrorMessage(err) {
        if (!err) return 'Gagal mendapatkan lokasi saat ini.';
        if (typeof err === 'string') return err;
        if (err.message && typeof err.code === 'undefined') return err.message;

        switch (err.code) {
            case 1:
                return 'Akses lokasi (GPS) ditolak. Silakan aktifkan izin lokasi di ikon gembok / info pada address bar browser Anda lalu coba lagi.';
            case 2:
                return 'Lokasi tidak dapat ditentukan. Pastikan GPS atau jaringan internet / Wi-Fi Anda aktif lalu coba kembali.';
            case 3:
                return 'Waktu pencarian lokasi habis. Silakan periksa koneksi internet Anda dan coba klik kembali.';
            default:
                return 'Gagal mendeteksi lokasi terkini. Silakan masukkan alamat atau koordinat secara manual.';
        }
    }

    function initLocationButton(options) {
        if (!options || !options.btn) return;

        const btn = typeof options.btn === 'string' ? document.querySelector(options.btn) : options.btn;
        if (!btn) return;

        const originalHtml = btn.innerHTML;

        btn.addEventListener('click', async function (e) {
            e.preventDefault();

            btn.disabled = true;
            btn.classList.add('opacity-80', 'cursor-wait');

            btn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-current inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Mencari titik lokasi &amp; alamat...</span>
            `;

            const statusEl = typeof options.status === 'string' ? document.querySelector(options.status) : options.status;
            if (statusEl) {
                statusEl.classList.remove('hidden');
                statusEl.innerHTML = '<span class="inline-flex items-center gap-1.5 text-amber-700 bg-amber-50 px-2.5 py-1 rounded-lg text-xs font-medium"><svg class="animate-spin h-3.5 w-3.5" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Menghubungkan GPS perangkat...</span>';
            }

            try {
                const position = await getCurrentPosition();
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                if (statusEl) {
                    statusEl.innerHTML = '<span class="inline-flex items-center gap-1.5 text-blue-700 bg-blue-50 px-2.5 py-1 rounded-lg text-xs font-medium"><svg class="animate-spin h-3.5 w-3.5" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Titik GPS didapat. Menerjemahkan alamat &amp; wilayah...</span>';
                }

                const result = await reverseGeocodeCoords(lat, lng);

                const addressEl = typeof options.address === 'string' ? document.querySelector(options.address) : options.address;
                if (addressEl && result.address) {
                    addressEl.value = result.address;
                    triggerEvents(addressEl);
                }

                if (options.regionId || options.regionSearch) {
                    autoSetRegion(result.region_id, result.region_name, options);
                }

                const latEl = typeof options.lat === 'string' ? document.querySelector(options.lat) : options.lat;
                const lngEl = typeof options.lng === 'string' ? document.querySelector(options.lng) : options.lng;
                if (latEl) {
                    latEl.value = lat.toFixed(6);
                    triggerEvents(latEl);
                }
                if (lngEl) {
                    lngEl.value = lng.toFixed(6);
                    triggerEvents(lngEl);
                }

                if (options.locationName) {
                    const locEl = typeof options.locationName === 'string' ? document.querySelector(options.locationName) : options.locationName;
                    if (locEl && !locEl.value.trim() && result.location_name) {
                        locEl.value = result.location_name;
                        triggerEvents(locEl);
                    }
                }

                if (typeof options.mapCallback === 'function') {
                    options.mapCallback(lat, lng, result.address);
                }

                if (statusEl) {
                    statusEl.innerHTML = `
                        <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-xs text-emerald-800 flex items-start gap-2">
                            <svg class="w-4 h-4 text-emerald-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <div>
                                <span class="font-bold">Lokasi GPS Terdeteksi:</span>
                                <span class="block text-emerald-700 mt-0.5">${result.address}</span>
                                <span class="text-[11px] text-emerald-600 font-mono mt-0.5 block">Koordinat: ${lat.toFixed(6)}, ${lng.toFixed(6)}${result.region_name ? ' • Wilayah: ' + result.region_name : ''}</span>
                            </div>
                        </div>
                    `;
                }

                if (window.showToast) {
                    window.showToast('success', 'Lokasi terkini berhasil dideteksi dan alamat telah terisi otomatis!');
                }

                if (typeof options.onSuccess === 'function') {
                    options.onSuccess(result);
                }
            } catch (err) {
                console.error('Geolocation tracking error:', err);
                const errorMsg = getFriendlyErrorMessage(err);

                if (statusEl) {
                    statusEl.innerHTML = `
                        <div class="p-3 bg-red-50 border border-red-200 rounded-xl text-xs text-red-700 flex items-start gap-2">
                            <svg class="w-4 h-4 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <div>
                                <span class="font-bold">Gagal Mendeteksi Lokasi</span>
                                <p class="mt-0.5">${errorMsg}</p>
                            </div>
                        </div>
                    `;
                }

                if (window.showToast) {
                    window.showToast('error', errorMsg);
                } else {
                    alert(errorMsg);
                }

                if (typeof options.onError === 'function') {
                    options.onError(errorMsg);
                }
            } finally {
                btn.disabled = false;
                btn.classList.remove('opacity-80', 'cursor-wait');
                btn.innerHTML = originalHtml;
            }
        });
    }

    window.JagantaraGeo = {
        init: initLocationButton,
        getCurrentPosition: getCurrentPosition,
        reverseGeocodeCoords: reverseGeocodeCoords
    };

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-geo-btn]').forEach(btn => {
            const addressSelector = btn.getAttribute('data-geo-address') || '#address';
            const regionSearchSelector = btn.getAttribute('data-geo-region-search') || '#region_search';
            const regionIdSelector = btn.getAttribute('data-geo-region-id') || '#region_id';
            const latSelector = btn.getAttribute('data-geo-lat') || '#latitude';
            const lngSelector = btn.getAttribute('data-geo-lng') || '#longitude';
            const statusSelector = btn.getAttribute('data-geo-status') || '#geo-status';

            initLocationButton({
                btn: btn,
                address: addressSelector,
                regionSearch: regionSearchSelector,
                regionId: regionIdSelector,
                lat: latSelector,
                lng: lngSelector,
                status: statusSelector
            });
        });
    });
})();
