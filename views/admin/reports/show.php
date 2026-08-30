<div class="space-y-6 max-w-5xl">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Pemeriksaan Laporan 
            <p class="text-xs text-gray-500">Verifikasi bukti foto, tetapkan status, atau berikan catatan tindak lanjut.</p>
        </div>
        <a href="<?= url('admin/reports') ?>" class="btn btn-outline btn-sm">
            &larr; Kembali ke Daftar Laporan
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 space-y-6">

            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <div class="flex flex-wrap items-center gap-2 mb-3">
                    <span class="badge badge-<?= e($report['status']) ?>">
                        <?= statusLabel($report['status']) ?>
                    </span>
                    <span class="text-xs bg-[#F7EAE0] text-[#5E3122] px-2.5 py-0.5 rounded-full font-semibold">
                        <?= e($report['category_name'] ?? 'Umum') ?>
                    </span>
                    <span class="text-xs text-gray-400">&bull; <?= formatDate($report['created_at'], 'd M Y H:i') ?></span>
                </div>

                <h1 class="text-xl font-extrabold text-gray-900 mb-4"><?= e($report['title']) ?></h1>

                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Deskripsi Masalah:</h3>
                <p class="text-sm text-gray-800 leading-relaxed whitespace-pre-line mb-4">
                    <?= e($report['description']) ?>
                </p>

                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Lokasi Permasalahan:</h3>
                <div class="bg-gray-50 p-3.5 rounded-xl border border-gray-100 text-xs text-gray-700 space-y-1">
                    <div><strong>Wilayah:</strong> <?= e($report['region_name'] ?? '-') ?></div>
                    <div><strong>Patokan:</strong> <?= e($report['location_name'] ?? '-') ?></div>
                    <div><strong>Alamat:</strong> <?= e($report['address'] ?? '-') ?></div>
                    <?php if (!empty($report['latitude']) && !empty($report['longitude'])): ?>
                        <div><strong>Koordinat:</strong> <?= (float)$report['latitude'] ?>, <?= (float)$report['longitude'] ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($photos)): ?>
                <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                    <h3 class="text-sm font-bold text-gray-900 mb-3">
                        Foto Bukti (<?= count($photos) ?> Foto)
                    </h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        <?php foreach ($photos as $p): ?>
                            <a href="<?= uploadUrl($p['file_path']) ?>" target="_blank" rel="noopener noreferrer" class="block aspect-square rounded-xl overflow-hidden bg-gray-100 border border-gray-200 hover:opacity-90 transition">
                                <img src="<?= uploadUrl($p['file_path']) ?>" class="w-full h-full object-cover" alt="Foto Laporan" width="200" height="200" loading="lazy" decoding="async">
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <h3 class="text-sm font-bold text-gray-900 mb-4">Riwayat Perubahan Status</h3>
                <?php if (!empty($statusHistory)): ?>
                    <div class="space-y-4 text-xs">
                        <?php foreach ($statusHistory as $h): ?>
                            <div class="p-3 rounded-xl bg-gray-50 border border-gray-100">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="font-bold text-gray-900"><?= statusLabel($h['status']) ?></span>
                                    <span class="text-gray-400"><?= formatDate($h['created_at'], 'd M Y H:i') ?></span>
                                </div>
                                <div class="text-gray-600">
                                    Diubah oleh: <?= e($h['changed_by_name'] ?? 'Sistem') ?>
                                </div>
                                <?php if (!empty($h['notes'])): ?>
                                    <div class="mt-1 text-gray-700 bg-white p-2 rounded border border-gray-200">
                                        <?= e($h['notes']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-xs text-gray-500">Belum ada riwayat status.</p>
                <?php endif; ?>
            </div>

        </div>

        <div class="space-y-6">

            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <h3 class="text-sm font-bold text-gray-900 mb-4">Perbarui Status Laporan</h3>

                <form action="<?= url('admin/reports/' . $report['id'] . '/status') ?>" method="POST">
                    <?= csrfField() ?>

                    <div class="space-y-4">
                        <div class="form-group mb-0">
                            <label for="status" class="form-label">Pilih Status Baru <span class="required">*</span></label>
                            <select id="status" name="status" required class="form-control text-xs" onchange="toggleRejectionField(this.value)">
                                <?php foreach (REPORT_STATUS_LABELS as $sKey => $sLabel): ?>
                                    <option value="<?= $sKey ?>" <?= $report['status'] === $sKey ? 'selected' : '' ?>>
                                        <?= e($sLabel) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div id="rejection-reason-group" class="<?= $report['status'] === STATUS_REJECTED ? '' : 'hidden' ?> form-group mb-0">
                            <label for="rejection_reason" class="form-label text-red-700">
                                Alasan Penolakan <span class="required">*</span>
                            </label>
                            <textarea id="rejection_reason" name="rejection_reason" rows="3" class="form-control text-xs border-red-300" placeholder="Jelaskan alasan laporan ditolak agar pelapor memahami penyebabnya..."><?= e($report['rejection_reason'] ?? '') ?></textarea>
                        </div>

                        <div class="form-group mb-0">
                            <label for="notes" class="form-label">Catatan Tindak Lanjut (Opsional)</label>
                            <textarea id="notes" name="notes" rows="2" class="form-control text-xs" placeholder="Contoh: Telah dijadwalkan kerja bakti bersama warga..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-full py-2.5 text-xs font-bold shadow-md">
                            Simpan Perubahan Status
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <h3 class="text-sm font-bold text-gray-900 mb-3">Informasi Pelapor</h3>
                <dl class="text-xs space-y-2">
                    <div>
                        <dt class="text-gray-400">Nama Pelapor:</dt>
                        <dd class="font-semibold text-gray-800"><?= e($report['reporter_name'] ?? '-') ?></dd>
                    </div>
                    <div>
                        <dt class="text-gray-400">Username:</dt>
                        <dd class="font-medium text-gray-800">@<?= e($report['reporter_username'] ?? '-') ?></dd>
                    </div>
                    <div>
                        <dt class="text-gray-400">Email:</dt>
                        <dd class="font-medium text-gray-800"><?= e($report['reporter_email'] ?? '-') ?></dd>
                    </div>
                    <div>
                        <dt class="text-gray-400">Nomor Telepon:</dt>
                        <dd class="font-medium text-gray-800"><?= e($report['reporter_phone'] ?? '-') ?></dd>
                    </div>
                </dl>
            </div>

        </div>

    </div>

</div>

<script>
function toggleRejectionField(status) {
    const group = document.getElementById('rejection-reason-group');
    if (status === 'rejected') {
        group.classList.remove('hidden');
    } else {
        group.classList.add('hidden');
    }
}
</script>
