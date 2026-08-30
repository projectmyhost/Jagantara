<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>

<style>
.cropper-view-box,
.cropper-face {
    border-radius: 50%;
}
.cropper-view-box {
    outline: 2px solid rgba(29, 69, 51, 0.85);
}
.avatar-cropper-preview {
    overflow: hidden;
    border-radius: 50%;
    background-color: #f3f4f6;
}
#cropper-modal.active {
    display: flex !important;
    animation: fadeInModal 0.2s ease-out;
}
@keyframes fadeInModal {
    from { opacity: 0; }
    to { opacity: 1; }
}
</style>

<div class="page-header">
    <div class="container">
        <h1>Edit Profil Pengguna</h1>
        <p>Perbarui informasi profil dan data pribadi Anda.</p>
    </div>
</div>

<div class="container py-8">
    <div class="max-w-2xl mx-auto">

        <div class="bg-white rounded-2xl border border-gray-200 p-6 sm:p-8 shadow-sm">
            <form id="profile-edit-form" action="<?= url('profile/update') ?>" method="POST" enctype="multipart/form-data">
                <?= csrfField() ?>
                <input type="hidden" name="avatar_cropped" id="avatar_cropped" value="">

                <div class="space-y-6">

                    <div>
                        <label class="form-label block mb-2 font-bold text-gray-900 text-sm">Foto Profil</label>
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5 p-4 rounded-2xl bg-gray-50 border border-gray-100">
                            
                            <div class="relative flex-shrink-0">
                                <div id="avatar-container" class="w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-[#1D4533] text-white flex items-center justify-center text-2xl font-bold overflow-hidden border-2 border-emerald-600 shadow-sm relative group">
                                    <?php if (!empty($user['avatar_path']) && file_exists(UPLOAD_PATH . '/' . $user['avatar_path'])): ?>
                                        <img id="avatar-preview" src="<?= uploadUrl($user['avatar_path']) ?>" class="w-full h-full object-cover" alt="Avatar">
                                    <?php else: ?>
                                        <span id="avatar-placeholder"><?= strtoupper(substr($user['username'] ?? 'U', 0, 1)) ?></span>
                                    <?php endif; ?>
                                </div>
                                <span id="cropped-badge" class="hidden absolute -bottom-1 -right-1 bg-emerald-600 text-white rounded-full p-1 shadow border-2 border-white" title="Foto baru siap disimpan">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                            </div>

                            <div class="flex-1 space-y-2">
                                <div class="flex flex-wrap items-center gap-2">
                                    <label for="avatar" class="btn btn-primary btn-sm cursor-pointer inline-flex items-center gap-1.5 py-2 px-3.5 text-xs font-bold">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span id="btn-choose-label">Pilih Foto Baru</span>
                                    </label>
                                    <input type="file" name="avatar" id="avatar" accept="image/jpeg,image/png,image/webp" class="hidden" onchange="onAvatarFileSelected(this)">

                                    <button type="button" id="btn-recrop" onclick="reopenCropper()" class="hidden btn btn-outline btn-sm py-2 px-3 text-xs inline-flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                        Atur Potongan
                                    </button>

                                    <button type="button" id="btn-reset-avatar" onclick="resetAvatarSelection()" class="hidden text-xs text-red-600 hover:text-red-800 hover:underline px-2 py-1 transition font-medium">
                                        Batal Ganti Foto
                                    </button>
                                </div>
                                <p class="text-[11px] text-gray-500 leading-relaxed">
                                    Format: JPG, PNG, atau WebP (Maks. 5MB). Anda dapat memotong, memutar, dan menyesuaikan foto secara presisi sebelum menyimpan.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <label for="email_disabled" class="form-label">Alamat Email</label>
                        <input type="text" id="email_disabled" value="<?= e($user['email']) ?>" disabled
                               class="form-control bg-gray-100 text-gray-500 cursor-not-allowed">
                        <span class="text-[11px] text-gray-400 mt-1 block">Email tidak dapat diubah melalui pengaturan profil.</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="form-group mb-0">
                            <label for="username" class="form-label">Username <span class="required">*</span></label>
                            <input type="text" id="username" name="username" required
                                   value="<?= e($user['username'] ?? '') ?>"
                                   class="form-control" placeholder="username">
                        </div>

                        <div class="form-group mb-0">
                            <label for="full_name" class="form-label">Nama Lengkap <span class="required">*</span></label>
                            <input type="text" id="full_name" name="full_name" required
                                   value="<?= e($user['full_name'] ?? '') ?>"
                                   class="form-control" placeholder="Nama Lengkap">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="form-group mb-0">
                            <label for="phone" class="form-label">Nomor Handphone</label>
                            <input type="text" id="phone" name="phone"
                                   value="<?= e($user['phone'] ?? '') ?>"
                                   class="form-control" placeholder="Contoh: 08123456789">
                        </div>

                        <div class="form-group mb-0">
                            <label for="region_id" class="form-label">Wilayah (JABODETABEK)</label>
                            <select id="region_id" name="region_id" class="form-control">
                                <option value="">-- Pilih Wilayah --</option>
                                <?php foreach ($regions as $r): ?>
                                    <option value="<?= $r['id'] ?>" <?= ($user['region_id'] ?? '') == $r['id'] ? 'selected' : '' ?>>
                                        <?= e($r['name']) ?> <?= !empty($r['parent_name']) ? '(' . e($r['parent_name']) . ')' : '' ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="form-group mb-0">
                            <label for="birth_date" class="form-label">Tanggal Lahir</label>
                            <input type="date" id="birth_date" name="birth_date"
                                   value="<?= e($user['birth_date'] ?? '') ?>"
                                   class="form-control">
                        </div>

                        <div class="form-group mb-0">
                            <label for="gender" class="form-label">Jenis Kelamin</label>
                            <select id="gender" name="gender" class="form-control">
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="male" <?= ($user['gender'] ?? '') === 'male' ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="female" <?= ($user['gender'] ?? '') === 'female' ? 'selected' : '' ?>>Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-1.5">
                            <label for="address" class="form-label mb-0">Alamat Lengkap</label>
                            <button type="button" id="geo-btn" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#1D4533] hover:bg-[#14301F] text-white text-xs font-semibold rounded-xl shadow-sm transition-all focus:ring-2 focus:ring-emerald-400 focus:outline-none cursor-pointer">
                                <svg class="w-3.5 h-3.5 text-[#F9D2BA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>Gunakan Lokasi Terkini</span>
                            </button>
                        </div>
                        <textarea id="address" name="address" rows="3"
                                  class="form-control"
                                  placeholder="Alamat domisili tempat tinggal"><?= e($user['address'] ?? '') ?></textarea>
                        <div id="geo-status" class="mt-2 hidden"></div>
                        <p class="text-[11px] text-gray-400 mt-1">
                            Klik <strong>"Gunakan Lokasi Terkini"</strong> untuk mendeteksi alamat dan memilih wilayah tempat tinggal Anda secara otomatis dari GPS.
                        </p>
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                        <a href="<?= url('profile') ?>" class="btn btn-outline py-2.5 px-5">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary py-2.5 px-6 font-bold shadow-md">
                            Simpan Perubahan
                        </button>
                    </div>

                </div>
            </form>
        </div>

    </div>
</div>

<div id="cropper-modal" class="fixed inset-0 z-50 hidden bg-black/70 backdrop-blur-sm items-center justify-center p-4 sm:p-6 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full overflow-hidden flex flex-col max-h-[90vh] my-auto" onclick="event.stopPropagation()">
        
        
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
            <div>
                <h3 class="text-base font-extrabold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#1D4533]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                    Sesuaikan Foto Profil
                </h3>
                <p class="text-xs text-gray-500 mt-0.5">Geser, atur skala (zoom), dan rotasi foto agar pas pada bingkai foto profil.</p>
            </div>
            <button type="button" onclick="closeCropperModal()" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 hover:text-gray-800 flex items-center justify-center transition" aria-label="Tutup">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        
        <div class="p-6 overflow-y-auto space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 items-center">
                
                
                <div class="sm:col-span-2">
                    <div class="w-full h-72 sm:h-80 bg-gray-950 rounded-xl overflow-hidden shadow-inner flex items-center justify-center border border-gray-800">
                        <img id="cropper-image" src="" alt="Crop target" class="max-w-full max-h-full block">
                    </div>
                </div>

                
                <div class="sm:col-span-1 flex flex-col items-center justify-center text-center p-4 bg-gray-50 rounded-xl border border-gray-200 space-y-4">
                    <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">Hasil Pratinjau</span>
                    
                    
                    <div class="avatar-cropper-preview w-24 h-24 shadow-md border-2 border-emerald-600"></div>
                    <span class="text-[11px] text-gray-500 font-medium">Ukuran Profil (1:1)</span>

                    
                    <div class="flex items-center gap-2 pt-2 border-t border-gray-200 w-full justify-center">
                        <div class="avatar-cropper-preview w-10 h-10 border border-gray-300"></div>
                        <span class="text-[10px] text-gray-400">Header / Navigasi</span>
                    </div>
                </div>

            </div>

            
            <div class="flex flex-wrap items-center justify-center sm:justify-between gap-2 p-3 bg-gray-50 rounded-xl border border-gray-200">
                <div class="flex items-center gap-1.5">
                    <button type="button" onclick="cropperAction('zoom', 0.1)" class="btn btn-outline btn-sm py-1.5 px-2.5 text-xs font-semibold" title="Perbesar (Zoom In)">
                        <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Zoom +
                    </button>
                    <button type="button" onclick="cropperAction('zoom', -0.1)" class="btn btn-outline btn-sm py-1.5 px-2.5 text-xs font-semibold" title="Perkecil (Zoom Out)">
                        <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"/>
                        </svg>
                        Zoom -
                    </button>
                </div>

                <div class="flex items-center gap-1.5">
                    <button type="button" onclick="cropperAction('rotate', -90)" class="btn btn-outline btn-sm py-1.5 px-2.5 text-xs font-semibold" title="Putar Kiri 90 Derajat">
                        <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                        </svg>
                        Putar Kiri
                    </button>
                    <button type="button" onclick="cropperAction('rotate', 90)" class="btn btn-outline btn-sm py-1.5 px-2.5 text-xs font-semibold" title="Putar Kanan 90 Derajat">
                        <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10H11a8 8 0 00-8 8v2m18-10l-6 6m6-6l-6-6"/>
                        </svg>
                        Putar Kanan
                    </button>
                </div>

                <div class="flex items-center gap-1.5">
                    <button type="button" onclick="cropperAction('flipX')" class="btn btn-outline btn-sm py-1.5 px-2.5 text-xs font-semibold" title="Balik Horizontal">
                        ⇆ Balik
                    </button>
                    <button type="button" onclick="cropperAction('reset')" class="btn btn-outline btn-sm py-1.5 px-2.5 text-xs font-semibold text-gray-600" title="Kembalikan Pengaturan Awal">
                        ↺ Reset
                    </button>
                </div>
            </div>
        </div>

        
        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-3 bg-gray-50/50">
            <button type="button" onclick="closeCropperModal()" class="btn btn-outline py-2 px-4 text-xs font-semibold">
                Batal
            </button>
            <button type="button" onclick="applyCrop()" class="btn btn-primary py-2 px-5 text-xs font-bold shadow-md inline-flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                Terapkan & Gunakan Foto
            </button>
        </div>

    </div>
</div>

<script>
let cropperInstance = null;
let currentRawImageSrc = null;
let flipXState = 1;
let flipYState = 1;

const originalAvatarHtml = document.getElementById('avatar-container').innerHTML;

function onAvatarFileSelected(input) {
    if (!input.files || !input.files[0]) return;

    const file = input.files[0];
    const validTypes = ['image/jpeg', 'image/png', 'image/webp'];
    
    if (!validTypes.includes(file.type)) {
        alert('Format file tidak didukung. Harap pilih gambar dengan format JPG, PNG, atau WebP.');
        input.value = '';
        return;
    }

    if (file.size > 5 * 1024 * 1024) {
        alert('Ukuran file terlalu besar. Maksimal ukuran gambar adalah 5 MB.');
        input.value = '';
        return;
    }

    const reader = new FileReader();
    reader.onload = function(e) {
        currentRawImageSrc = e.target.result;
        openCropperModal(currentRawImageSrc);
    };
    reader.onerror = function() {
        alert('Gagal membaca file gambar. Silakan coba lagi.');
        input.value = '';
    };
    reader.readAsDataURL(file);
}

function openCropperModal(imageSrc) {
    const modal = document.getElementById('cropper-modal');
    const cropperImage = document.getElementById('cropper-image');
    
    if (typeof Cropper === 'undefined') {
        alert('Komponen pemotong gambar sedang dimuat atau gagal terhubung. Foto akan digunakan secara langsung.');
        document.getElementById('avatar_cropped').value = imageSrc;
        const container = document.getElementById('avatar-container');
        container.innerHTML = `<img id="avatar-preview" src="${imageSrc}" class="w-full h-full object-cover" alt="Avatar Baru">`;
        document.getElementById('cropped-badge').classList.remove('hidden');
        document.getElementById('btn-reset-avatar').classList.remove('hidden');
        document.getElementById('btn-choose-label').textContent = 'Ganti Foto';
        return;
    }

    if (cropperInstance) {
        try { cropperInstance.destroy(); } catch(err) {}
        cropperInstance = null;
    }

    cropperImage.src = imageSrc;
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';

    flipXState = 1;
    flipYState = 1;

    setTimeout(() => {
        try {
            cropperInstance = new Cropper(cropperImage, {
                aspectRatio: 1,
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 0.9,
                restore: false,
                guides: true,
                center: true,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false,
                preview: '.avatar-cropper-preview'
            });
        } catch (e) {
            console.error('Cropper init error:', e);
            closeCropperModal();
        }
    }, 60);
}

function reopenCropper() {
    if (currentRawImageSrc) {
        openCropperModal(currentRawImageSrc);
    } else {
        document.getElementById('avatar').click();
    }
}

function closeCropperModal() {
    const modal = document.getElementById('cropper-modal');
    if (modal) {
        modal.classList.remove('active');
    }
    document.body.style.overflow = '';

    if (cropperInstance) {
        try { cropperInstance.destroy(); } catch(e) {}
        cropperInstance = null;
    }

    const croppedVal = document.getElementById('avatar_cropped').value;
    if (!croppedVal) {
        document.getElementById('avatar').value = '';
    }
}

function cropperAction(action, value) {
    if (!cropperInstance) return;

    try {
        switch (action) {
            case 'zoom':
                cropperInstance.zoom(value);
                break;
            case 'rotate':
                cropperInstance.rotate(value);
                break;
            case 'flipX':
                flipXState = -flipXState;
                cropperInstance.scaleX(flipXState);
                break;
            case 'flipY':
                flipYState = -flipYState;
                cropperInstance.scaleY(flipYState);
                break;
            case 'reset':
                flipXState = 1;
                flipYState = 1;
                cropperInstance.reset();
                break;
        }
    } catch(err) {
        console.error('Cropper action error:', err);
    }
}

function applyCrop() {
    if (!cropperInstance) {
        closeCropperModal();
        return;
    }

    try {
        const canvas = cropperInstance.getCroppedCanvas({
            width: 512,
            height: 512,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
        });

        if (!canvas) {
            alert('Gagal memotong gambar. Silakan coba lagi.');
            closeCropperModal();
            return;
        }

        const croppedBase64 = canvas.toDataURL('image/jpeg', 0.92);
        document.getElementById('avatar_cropped').value = croppedBase64;

        const container = document.getElementById('avatar-container');
        container.innerHTML = `<img id="avatar-preview" src="${croppedBase64}" class="w-full h-full object-cover" alt="Avatar Baru">`;

        document.getElementById('btn-recrop').classList.remove('hidden');
        document.getElementById('btn-reset-avatar').classList.remove('hidden');
        document.getElementById('cropped-badge').classList.remove('hidden');
        document.getElementById('btn-choose-label').textContent = 'Ganti Foto';

        closeCropperModal();
    } catch(err) {
        console.error('Apply crop error:', err);
        alert('Terjadi kesalahan saat memproses gambar.');
        closeCropperModal();
    }
}

function resetAvatarSelection() {
    document.getElementById('avatar').value = '';
    document.getElementById('avatar_cropped').value = '';
    currentRawImageSrc = null;

    const container = document.getElementById('avatar-container');
    container.innerHTML = originalAvatarHtml;

    document.getElementById('btn-recrop').classList.add('hidden');
    document.getElementById('btn-reset-avatar').classList.add('hidden');
    document.getElementById('cropped-badge').classList.add('hidden');
    document.getElementById('btn-choose-label').textContent = 'Pilih Foto Baru';
    document.body.style.overflow = '';
}

document.addEventListener('DOMContentLoaded', function() {
    if (window.JagantaraGeo) {
        JagantaraGeo.init({
            btn: '#geo-btn',
            address: '#address',
            regionId: '#region_id',
            status: '#geo-status'
        });
    }
});
</script>
