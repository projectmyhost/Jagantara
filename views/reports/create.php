<div class="page-header">
    <div class="container">
        <h1>Buat Laporan Lingkungan</h1>
        <p>Sampaikan permasalahan kebersihan dan lingkungan di sekitar Anda untuk ditindaklanjuti.</p>
    </div>
</div>

<div class="container py-8">
    <div class="max-w-3xl mx-auto">

        <div class="bg-[#F7EAE0] border border-[#F9D2BA] rounded-2xl p-5 mb-8 text-[#5E3122]">
            <div class="flex items-start gap-3">
                <div class="p-2 rounded-xl bg-white/60 text-[#1D4533] flex-shrink-0 mt-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="text-xs sm:text-sm space-y-2">
                    <p class="font-bold text-base text-[#1D4533]">Petunjuk Penggunaan Forum Pelaporan</p>
                    <p>Fitur ini digunakan untuk:</p>
                    <ul class="list-disc list-inside space-y-1 pl-1">
                        <li>Melaporkan sampah berserakan atau penumpukan sampah liar.</li>
                        <li>Melaporkan saluran air/drainase yang mampet dan lingkungan kotor.</li>
                        <li>Mengajukan kebutuhan kerja bakti atau pembersihan lingkungan.</li>
                        <li>Melaporkan pencemaran lingkungan yang membutuhkan bantuan organizer.</li>
                    </ul>
                    <p class="font-semibold text-red-800 pt-1">
                        Peringatan: Berikan data lokasi dan foto yang benar. Laporan palsu, spam, atau tidak relevan akan ditolak oleh admin dan tidak akan ditindaklanjuti.
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-6 sm:p-8 shadow-sm">
            <form action="<?= url('reports/store') ?>" method="POST" enctype="multipart/form-data" id="report-form">
                <?= csrfField() ?>

                <div class="space-y-6">

                    <div class="form-group mb-0">
                        <label for="title" class="form-label">
                            Judul Laporan <span class="required">*</span>
                        </label>
                        <input type="text" id="title" name="title" required
                               value="<?= e($values['title'] ?? '') ?>"
                               class="form-control <?= !empty($errors['title']) ? 'error' : '' ?>"
                               placeholder="Contoh: Penumpukan Sampah Liar di Pinggir Kali Baru">
                        <?php if (!empty($errors['title'])): ?>
                            <div class="form-error"><?= e($errors['title']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="form-group mb-0">
                            <label for="category_id" class="form-label">
                                Kategori Lingkungan <span class="required">*</span>
                            </label>
                            <select id="category_id" name="category_id" required class="form-control <?= !empty($errors['category_id']) ? 'error' : '' ?>" onchange="toggleCustomCategory(this)">
                                <option value="">-- Pilih Kategori --</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= ($values['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?> data-slug="<?= e($cat['slug']) ?>">
                                        <?= e($cat['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <input type="text" id="custom_category" name="custom_category" maxlength="100"
                                   value="<?= e($values['custom_category'] ?? '') ?>"
                                   class="form-control mt-2 hidden"
                                   placeholder="Tulis jenis kategori lainnya...">
                            <?php if (!empty($errors['category_id'])): ?>
                                <div class="form-error"><?= e($errors['category_id']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group mb-0">
                            <label for="region_id" class="form-label">
                                Wilayah / Daerah <span class="required">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" id="region_search" autocomplete="off"
                                       value="<?= e($values['region_name'] ?? '') ?>"
                                       placeholder="Ketik nama wilayah..."
                                       class="form-control <?= !empty($errors['region_id']) ? 'error' : '' ?>">
                                <input type="hidden" id="region_id" name="region_id" value="<?= e($values['region_id'] ?? '') ?>">
                                <div id="region_dropdown" class="absolute z-20 mt-1 w-full hidden max-h-60 overflow-y-auto rounded-xl border border-gray-200 bg-white shadow-lg"></div>
                            </div>
                            <?php if (!empty($errors['region_id'])): ?>
                                <div class="form-error"><?= e($errors['region_id']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <label for="location_name" class="form-label">
                            Nama / Patokan Lokasi <span class="required">*</span>
                        </label>
                        <input type="text" id="location_name" name="location_name" required
                               value="<?= e($values['location_name'] ?? '') ?>"
                               class="form-control <?= !empty($errors['location_name']) ? 'error' : '' ?>"
                               placeholder="Contoh: Depan Taman RT 04 / Samping Jembatan Merah">
                        <?php if (!empty($errors['location_name'])): ?>
                            <div class="form-error"><?= e($errors['location_name']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group mb-0">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-1.5">
                            <label for="address" class="form-label mb-0">
                                Alamat Lengkap Permasalahan <span class="required">*</span>
                            </label>
                            <button type="button" id="geo-btn" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#1D4533] hover:bg-[#14301F] text-white text-xs font-semibold rounded-xl shadow-sm transition-all focus:ring-2 focus:ring-emerald-400 focus:outline-none cursor-pointer">
                                <svg class="w-3.5 h-3.5 text-[#F9D2BA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>Gunakan Lokasi Terkini</span>
                            </button>
                        </div>
                        <textarea id="address" name="address" rows="2" required
                                  class="form-control <?= !empty($errors['address']) ? 'error' : '' ?>"
                                  placeholder="Tuliskan nama jalan, RT/RW, kelurahan, dan kecamatan lokasi masalah"><?= e($values['address'] ?? '') ?></textarea>
                        
                        <input type="hidden" id="latitude" name="latitude" value="<?= e($values['latitude'] ?? '') ?>">
                        <input type="hidden" id="longitude" name="longitude" value="<?= e($values['longitude'] ?? '') ?>">

                        <div id="geo-status" class="mt-2 hidden"></div>
                        <p class="text-[11px] text-gray-400 mt-1">
                            Klik <strong>"Gunakan Lokasi Terkini"</strong> untuk mendeteksi titik koordinat, alamat jalan, dan wilayah secara otomatis melalui GPS perangkat.
                        </p>
                        <?php if (!empty($errors['address'])): ?>
                            <div class="form-error"><?= e($errors['address']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group mb-0">
                        <label for="description" class="form-label">
                            Deskripsi Rinci Permasalahan <span class="required">*</span>
                        </label>
                        <textarea id="description" name="description" rows="4" required
                                  class="form-control <?= !empty($errors['description']) ? 'error' : '' ?>"
                                  placeholder="Jelaskan kondisi permasalahan, perkiraan volume sampah, dampak bagi warga, dan jenis bantuan pembersihan yang dibutuhkan"><?= e($values['description'] ?? '') ?></textarea>
                        <?php if (!empty($errors['description'])): ?>
                            <div class="form-error"><?= e($errors['description']) ?></div>
                        <?php endif; ?>
                    </div>


                    <?php
                        $maxReportPhotos = (int)setting('max_report_photos', '10');
                        $maxPhotoSizeMb = (int)setting('max_photo_size_mb', '5');
                    ?>
                    <div class="form-group mb-0">
                        <label class="form-label">
                            Foto Bukti Permasalahan <span class="required">*</span>
                            <span class="text-xs font-normal text-gray-500 ml-1">(Wajib minimal 1, Maksimal <?= $maxReportPhotos ?> foto, Format: JPG, PNG, WebP, Maks. <?= $maxPhotoSizeMb ?>MB/foto)</span>
                        </label>

                        <div id="photo-drop-zone"
                             class="photo-upload-zone"
                             style="cursor:pointer; transition: border-color .2s, background .2s;"
                             onclick="document.getElementById('photos-input').click()">
                            <input type="file" id="photos-input" name="photos[]" multiple accept="image/jpeg,image/png,image/webp" class="hidden">

                            <div class="flex flex-col items-center justify-center pointer-events-none">
                                <div class="w-12 h-12 rounded-full bg-emerald-50 text-[#1D4533] flex items-center justify-center mb-2" id="drop-icon">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-700">Pilih atau Seret Foto ke Sini</p>
                                <p class="text-xs text-gray-500 mt-1">Klik atau drag &amp; drop foto dari perangkat Anda</p>
                            </div>
                        </div>

                        <div id="photo-preview-grid" class="photo-grid"></div>
                        <div id="photo-count-info" class="text-xs text-gray-500 mt-2 font-medium">0 dari <?= $maxReportPhotos ?> foto terpilih.</div>

                        <?php if (!empty($errors['photos'])): ?>
                            <div class="form-error"><?= e($errors['photos']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="pt-4 flex flex-col sm:flex-row gap-3">
                        <button type="submit" class="btn btn-primary py-3 flex-1 font-bold shadow-md">
                            Kirim Laporan
                        </button>
                        <a href="<?= url('reports') ?>" class="btn btn-outline py-3 text-center sm:w-36">
                            Batal
                        </a>
                    </div>

                </div>
            </form>
        </div>

    </div>
</div>

<script>
const REGIONS = <?= json_encode(array_map(fn($r) => [
    'id'   => $r['id'],
    'name' => $r['name'] . (!empty($r['parent_name']) ? ' (' . $r['parent_name'] . ')' : ''),
], $regions), JSON_UNESCAPED_UNICODE) ?>;

const MAX_PHOTOS = <?= $maxReportPhotos ?>;
const MAX_PHOTO_SIZE_MB = <?= $maxPhotoSizeMb ?>;
let selectedFiles = [];

function syncInputFiles() {
    const dt = new DataTransfer();
    selectedFiles.forEach(f => dt.items.add(f));
    document.getElementById('photos-input').files = dt.files;
}

function addFiles(newFiles) {
    const validTypes = ['image/jpeg', 'image/png', 'image/webp'];
    const skipped = [];

    for (const file of newFiles) {
        if (!validTypes.includes(file.type)) {
            skipped.push(file.name + ' (format tidak didukung)');
            continue;
        }
        if (file.size > MAX_PHOTO_SIZE_MB * 1024 * 1024) {
            skipped.push(file.name + ' (ukuran melebihi ' + MAX_PHOTO_SIZE_MB + 'MB)');
            continue;
        }
        const isDuplicate = selectedFiles.some(f => f.name === file.name && f.size === file.size);
        if (isDuplicate) continue;

        if (selectedFiles.length >= MAX_PHOTOS) {
            alert('Batas maksimal ' + MAX_PHOTOS + ' foto sudah tercapai.');
            break;
        }
        selectedFiles.push(file);
    }

    if (skipped.length > 0) {
        alert('Beberapa file dilewati:\n' + skipped.join('\n'));
    }

    renderPhotoPreviews();
}

function handlePhotoSelect(input) {
    if (!input.files || !input.files.length) return;
    addFiles(Array.from(input.files));
    input.value = '';
    syncInputFiles();
}

function removePhoto(index) {
    selectedFiles.splice(index, 1);
    syncInputFiles();
    renderPhotoPreviews();
}

function renderPhotoPreviews() {
    const grid = document.getElementById('photo-preview-grid');
    const info = document.getElementById('photo-count-info');
    grid.innerHTML = '';
    info.textContent = selectedFiles.length + ' dari ' + MAX_PHOTOS + ' foto terpilih.';

    selectedFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const item = document.createElement('div');
            item.className = 'photo-preview-item';
            item.innerHTML = `
                <img src="${e.target.result}" alt="Preview ${index + 1}">
                <button type="button" class="remove-btn" onclick="removePhoto(${index})" title="Hapus foto">&times;</button>
            `;
            grid.appendChild(item);
        };
        reader.readAsDataURL(file);
    });
}

function initDragAndDrop() {
    const zone = document.getElementById('photo-drop-zone');
    if (!zone) return;

    zone.addEventListener('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        zone.style.borderColor = 'var(--color-primary)';
        zone.style.background = 'rgba(29,69,51,.05)';
    });

    zone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        zone.style.borderColor = '';
        zone.style.background = '';
    });

    zone.addEventListener('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        zone.style.borderColor = '';
        zone.style.background = '';

        const files = e.dataTransfer ? Array.from(e.dataTransfer.files) : [];
        if (files.length > 0) {
            addFiles(files);
            syncInputFiles();
        }
    });

    document.getElementById('photos-input').addEventListener('change', function() {
        handlePhotoSelect(this);
    });
}

function preventEnterSubmit() {
    const form = document.getElementById('report-form');
    if (!form) return;
    form.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            const tag = e.target.tagName.toLowerCase();
            if (tag !== 'textarea' && tag !== 'button' && tag !== 'a') {
                e.preventDefault();
            }
        }
    });
}

function initGeolocation() {
    if (!window.JagantaraGeo) return;
    JagantaraGeo.init({
        btn: '#geo-btn',
        address: '#address',
        regionSearch: '#region_search',
        regionId: '#region_id',
        lat: '#latitude',
        lng: '#longitude',
        locationName: '#location_name',
        status: '#geo-status'
    });
}

function toggleCustomCategory(select) {
    const input = document.getElementById('custom_category');
    const isLainnya = select.options[select.selectedIndex].getAttribute('data-slug') === 'lainnya';
    input.classList.toggle('hidden', !isLainnya);
    if (isLainnya) {
        input.setAttribute('required', 'required');
        input.focus();
    } else {
        input.removeAttribute('required');
        input.value = '';
    }
}

function initRegionSearch() {
    const search = document.getElementById('region_search');
    const hidden = document.getElementById('region_id');
    const dropdown = document.getElementById('region_dropdown');

    function render(list) {
        dropdown.innerHTML = '';
        if (!list.length) {
            dropdown.innerHTML = '<div class="px-3 py-2 text-xs text-gray-500">Wilayah tidak ditemukan.</div>';
        }
        list.forEach(r => {
            const item = document.createElement('button');
            item.type = 'button';
            item.className = 'w-full text-left px-3 py-2 text-sm hover:bg-[#F7EAE0]';
            item.textContent = r.name;
            item.addEventListener('mousedown', e => {
                e.preventDefault();
                hidden.value = r.id;
                search.value = r.name;
                dropdown.classList.add('hidden');
            });
            dropdown.appendChild(item);
        });
    }

    search.addEventListener('input', () => {
        const q = search.value.trim().toLowerCase();
        render(REGIONS.filter(r => r.name.toLowerCase().includes(q)));
        dropdown.classList.remove('hidden');
    });

    search.addEventListener('focus', () => {
        if (search.value.trim()) render(REGIONS.filter(r => r.name.toLowerCase().includes(search.value.trim().toLowerCase())));
        else render(REGIONS);
        dropdown.classList.remove('hidden');
    });

    document.addEventListener('click', e => {
        if (!search.contains(e.target) && !dropdown.contains(e.target)) dropdown.classList.add('hidden');
    });

    hidden.addEventListener('change', () => {
        const found = hidden.value ? REGIONS.find(r => String(r.id) === String(hidden.value)) : null;
        if (found) search.value = found.name;
    });

    const initial = hidden.value ? REGIONS.find(r => String(r.id) === String(hidden.value)) : null;
    if (initial) search.value = initial.name;
}

document.addEventListener('DOMContentLoaded', function() {
    window.REGIONS = REGIONS;
    toggleCustomCategory(document.getElementById('category_id'));
    initRegionSearch();
    initDragAndDrop();
    preventEnterSubmit();
    initGeolocation();
});
</script>

