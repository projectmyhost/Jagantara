<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-base font-bold text-gray-900">Permohonan Anggota: <?= e($organizer['name']) ?></h2>
            <p class="text-xs text-gray-500">Periksa dan verifikasi berkas identitas calon anggota komunitas.</p>
        </div>
        <a href="<?= url('admin/organizers') ?>" class="btn btn-outline btn-sm">
            &larr; Kembali ke Daftar Organizer
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 p-4 shadow-sm">
        <form method="GET" action="<?= url('admin/organizers/' . $organizer['id'] . '/applications') ?>" class="flex items-center gap-3">
            <select name="status" class="form-control text-xs w-48">
                <option value="">Semua Status Permohonan</option>
                <option value="pending" <?= ($status ?? '') === 'pending' ? 'selected' : '' ?>>Menunggu Verifikasi</option>
                <option value="approved" <?= ($status ?? '') === 'approved' ? 'selected' : '' ?>>Disetujui</option>
                <option value="rejected" <?= ($status ?? '') === 'rejected' ? 'selected' : '' ?>>Ditolak</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm h-[38px] px-4 font-semibold">
                Filter
            </button>
        </form>
    </div>

    <?php if (!empty($apps)): ?>
        <div class="space-y-4">
            <?php foreach ($apps as $a): ?>
                <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                    <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-6">

                        <div class="space-y-3 flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="badge <?= $a['status'] === 'approved' ? 'badge-completed' : ($a['status'] === 'rejected' ? 'badge-rejected' : 'badge-pending') ?>">
                                    <?= match($a['status']) {
                                        'approved' => 'Disetujui',
                                        'rejected' => 'Ditolak',
                                        default => 'Menunggu Verifikasi'
                                    } ?>
                                </span>
                                <span class="text-xs text-gray-400">Diajukan: <?= formatDate($a['created_at'], 'd M Y H:i') ?></span>
                            </div>

                            <h3 class="text-lg font-bold text-gray-900"><?= e($a['full_name']) ?></h3>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-gray-600">
                                <div><strong>Email:</strong> <?= e($a['applicant_email'] ?? '-') ?></div>
                                <div><strong>Telepon (WA):</strong> <?= e($a['phone']) ?></div>
                                <div><strong>Wilayah:</strong> <?= e($a['region_name'] ?? '-') ?></div>
                                <div><strong>Tanggal Lahir:</strong> <?= !empty($a['birth_date']) ? formatDate($a['birth_date']) : '-' ?></div>
                                <div><strong>Jenis Kelamin:</strong> <?= $a['gender'] === 'male' ? 'Laki-laki' : ($a['gender'] === 'female' ? 'Perempuan' : '-') ?></div>
                                <div class="sm:col-span-2"><strong>Alamat:</strong> <?= e($a['address']) ?></div>
                            </div>

                            <?php if (!empty($a['motivation'])): ?>
                                <div class="p-3 bg-gray-50 rounded-xl border border-gray-100 text-xs text-gray-700">
                                    <span class="font-bold block mb-0.5">Motivasi Bergabung:</span>
                                    <?= e($a['motivation']) ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($a['status'] === 'rejected' && !empty($a['rejection_reason'])): ?>
                                <div class="p-3 bg-red-50 rounded-xl border border-red-200 text-xs text-red-800">
                                    <span class="font-bold block mb-0.5">Alasan Penolakan:</span>
                                    <?= e($a['rejection_reason']) ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        </div>

                        <?php if ($a['status'] === 'pending'): ?>
                            <div class="lg:w-48 flex-shrink-0 flex flex-col justify-center gap-2 pt-2 lg:pt-0 border-t lg:border-t-0 lg:border-l border-gray-100 lg:pl-5">
                                <form action="<?= url('admin/organizers/applications/' . $a['id'] . '/approve') ?>"
                                      method="POST"
                                      data-confirm="Setujui calon anggota ini sebagai anggota aktif organizer?"
                                      data-confirm-title="Setujui Anggota?"
                                      data-confirm-yes="Ya, Setujui"
                                      data-confirm-no="Batal">
                                    <?= csrfField() ?>
                                    <button type="submit" class="btn btn-primary btn-sm w-full font-bold">
                                        Setujui Pendaftaran
                                    </button>
                                </form>

                                <button type="button" onclick="openRejectModal(<?= $a['id'] ?>)" class="btn btn-danger btn-sm w-full">
                                    Tolak Pendaftaran
                                </button>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state bg-white rounded-2xl border border-gray-200 p-12">
            <h3 class="text-base font-bold text-gray-800 mb-1">Tidak Ada Permohonan</h3>
            <p class="text-xs text-gray-500">Belum ada permohonan anggota untuk organizer ini.</p>
        </div>
    <?php endif; ?>

</div>

<div id="reject-modal" class="modal-overlay" onclick="closeRejectModalOnBackdrop(event)">
    <div class="modal max-w-md w-full" onclick="event.stopPropagation()">
        <h3 class="text-base font-bold text-gray-900 mb-2">Tolak Permohonan Anggota</h3>
        <p class="text-xs text-gray-500 mb-4">Berikan alasan penolakan agar calon anggota mendapatkan pemberitahuan yang jelas.</p>

        <form id="reject-form" action="" method="POST">
            <?= csrfField() ?>
            <div class="form-group mb-4">
                <label for="rejection_reason" class="form-label">Alasan Penolakan <span class="required">*</span></label>
                <textarea id="rejection_reason" name="rejection_reason" rows="3" required class="form-control text-xs" placeholder="Contoh: Domisili di luar jangkauan operasional / data tidak sesuai"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2">
                <button type="button" onclick="closeRejectModal()" class="btn btn-outline btn-sm">Batal</button>
                <button type="submit" class="btn btn-danger btn-sm font-bold">Kirim Penolakan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openRejectModal(id) {
    const modal = document.getElementById('reject-modal');
    const form = document.getElementById('reject-form');
    form.action = '<?= url('admin/organizers/applications/') ?>' + id + '/reject';
    modal.classList.add('show');
}

function closeRejectModal() {
    document.getElementById('reject-modal').classList.remove('show');
}

function closeRejectModalOnBackdrop(e) {
    if (e.target.id === 'reject-modal') {
        closeRejectModal();
    }
}
</script>
