<div class="page-header">
    <div class="container">
        <h1>Formulir Pendaftaran Anggota</h1>
        <p>Bergabung dengan <strong><?= e($organizer['name']) ?></strong> untuk aksi lingkungan nyata.</p>
    </div>
</div>

<div class="container py-8">
    <div class="max-w-2xl mx-auto">

        <?php if ($isComplete): ?>
            <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 mb-6 text-emerald-900 text-xs sm:text-sm">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-emerald-700 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <span class="font-bold block text-emerald-950 mb-0.5">Profil Anda Lengkap</span>
                        Data formulir di bawah ini otomatis terisi dari data profil Anda yang sudah tersimpan. Perubahan data hanya dapat dilakukan melalui menu <a href="<?= url('profile/edit') ?>" class="underline font-semibold">Pengaturan Profil</a>.
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-6 text-amber-900 text-xs sm:text-sm">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-amber-700 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div>
                        <span class="font-bold block text-amber-950 mb-0.5">Profil Belum Lengkap</span>
                        Silakan lengkapi data pada formulir pendaftaran ini. Data yang Anda masukkan hanya digunakan untuk proses verifikasi pendaftaran organizer dan tidak otomatis menjadi profil permanen Anda.
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-2xl border border-gray-200 p-6 sm:p-8 shadow-sm">
            <form action="<?= url('organizers/join/' . $organizer['id']) ?>" method="POST" enctype="multipart/form-data">
                <?= csrfField() ?>

                <div class="space-y-5">

                    <div class="form-group mb-0">
                        <label for="full_name" class="form-label">Nama Lengkap <span class="required">*</span></label>
                        <input type="text" id="full_name" name="full_name" required
                               value="<?= e($userProfile['full_name'] ?? '') ?>"
                               <?= $isComplete ? 'readonly class="form-control bg-gray-50 text-gray-700 cursor-not-allowed"' : 'class="form-control"' ?>
                               placeholder="Nama lengkap sesuai identitas">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="form-group mb-0">
                            <label for="phone" class="form-label">Nomor Handphone (WhatsApp) <span class="required">*</span></label>
                            <input type="text" id="phone" name="phone" required
                                   value="<?= e($userProfile['phone'] ?? '') ?>"
                                   <?= $isComplete ? 'readonly class="form-control bg-gray-50 text-gray-700 cursor-not-allowed"' : 'class="form-control"' ?>
                                   placeholder="Contoh: 08123456789">
                        </div>

                        <div class="form-group mb-0">
                            <label for="region_id" class="form-label">Wilayah Tempat Tinggal <span class="required">*</span></label>
                            <?php if ($isComplete): ?>
                                <input type="hidden" name="region_id" value="<?= (int)($userProfile['region_id'] ?? 0) ?>">
                                <input type="text" readonly value="<?= e($userProfile['region_name'] ?? '-') ?>" class="form-control bg-gray-50 text-gray-700 cursor-not-allowed">
                            <?php else: ?>
                                <div class="relative">
                                    <input type="text" id="region_search" autocomplete="off"
                                           value="<?= e($userProfile['region_name'] ?? '') ?>"
                                           placeholder="Ketik nama wilayah..."
                                           required class="form-control">
                                    <input type="hidden" id="region_id" name="region_id"
                                           value="<?= e($userProfile['region_id'] ?? '') ?>">
                                    <div id="region_dropdown" class="absolute z-20 mt-1 w-full hidden max-h-60 overflow-y-auto rounded-xl border border-gray-200 bg-white shadow-lg"></div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="form-group mb-0">
                            <label for="birth_date" class="form-label">Tanggal Lahir <span class="required">*</span></label>
                            <input type="date" id="birth_date" name="birth_date" required
                                   value="<?= e($userProfile['birth_date'] ?? '') ?>"
                                   <?= $isComplete ? 'readonly class="form-control bg-gray-50 text-gray-700 cursor-not-allowed"' : 'class="form-control"' ?>>
                        </div>

                        <div class="form-group mb-0">
                            <label for="gender" class="form-label">Jenis Kelamin <span class="required">*</span></label>
                            <?php if ($isComplete): ?>
                                <input type="hidden" name="gender" value="<?= e($userProfile['gender'] ?? '') ?>">
                                <input type="text" readonly value="<?= ($userProfile['gender'] ?? '') === 'male' ? 'Laki-laki' : 'Perempuan' ?>" class="form-control bg-gray-50 text-gray-700 cursor-not-allowed">
                            <?php else: ?>
                                <select id="gender" name="gender" required class="form-control">
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="male" <?= ($userProfile['gender'] ?? '') === 'male' ? 'selected' : '' ?>>Laki-laki</option>
                                    <option value="female" <?= ($userProfile['gender'] ?? '') === 'female' ? 'selected' : '' ?>>Perempuan</option>
                                    
                                </select>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-1.5">
                            <label for="address" class="form-label mb-0">Alamat Lengkap <span class="required">*</span></label>
                            <?php if (!$isComplete): ?>
                                <button type="button" id="geo-btn" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#1D4533] hover:bg-[#14301F] text-white text-xs font-semibold rounded-xl shadow-sm transition-all focus:ring-2 focus:ring-emerald-400 focus:outline-none cursor-pointer">
                                    <svg class="w-3.5 h-3.5 text-[#F9D2BA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span>Gunakan Lokasi Terkini</span>
                                </button>
                            <?php endif; ?>
                        </div>
                        <textarea id="address" name="address" rows="2" required
                                  <?= $isComplete ? 'readonly class="form-control bg-gray-50 text-gray-700 cursor-not-allowed"' : 'class="form-control"' ?>
                                  placeholder="Alamat domisili Anda"><?= e($userProfile['address'] ?? '') ?></textarea>
                        <?php if (!$isComplete): ?>
                            <div id="geo-status" class="mt-2 hidden"></div>
                            <p class="text-[11px] text-gray-400 mt-1">
                                Klik <strong>"Gunakan Lokasi Terkini"</strong> untuk mengisi alamat dan wilayah domisili Anda secara otomatis dari GPS.
                            </p>
                        <?php endif; ?>
                    </div>

                    <div class="form-group mb-0">
                        <label for="motivation" class="form-label">Motivasi & Pengalaman Singkat (Opsional)</label>
                        <textarea id="motivation" name="motivation" rows="3" class="form-control"
                                  placeholder="Ceritakan alasan Anda ingin bergabung dan pengalaman aksi lingkungan yang pernah diikuti"></textarea>
                    </div>

                    <div class="pt-3 flex flex-col sm:flex-row gap-3">
                        <button type="submit" class="btn btn-primary py-3 flex-1 font-bold shadow-md">
                            Kirim Pendaftaran
                        </button>
                        <a href="<?= url('organizers/' . $organizer['id']) ?>" class="btn btn-outline py-3 text-center sm:w-32">
                            Batal
                        </a>
    </div>
</div>

<?php if (!$isComplete): ?>
<script>
const REGIONS = <?= json_encode(array_map(fn($r) => [
    'id'   => $r['id'],
    'name' => $r['name'] . (!empty($r['parent_name']) ? ' (' . $r['parent_name'] . ')' : ''),
], $regions), JSON_UNESCAPED_UNICODE) ?>;

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
    initRegionSearch();
    if (window.JagantaraGeo) {
        JagantaraGeo.init({
            btn: '#geo-btn',
            address: '#address',
            regionSearch: '#region_search',
            regionId: '#region_id',
            status: '#geo-status'
        });
    }
});
</script>
<?php endif; ?>

            </form>
        </div>

    </div>
</div>
