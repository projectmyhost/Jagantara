<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-base font-bold text-gray-900">Manajemen Kegiatan & Kerja Bakti</h2>
            <p class="text-xs text-gray-500">Kelola agenda kegiatan lingkungan, jadwal, dan daftar peserta terdaftar.</p>
        </div>
        <a href="<?= url('admin/activities/create') ?>" class="btn btn-primary btn-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Kegiatan Baru
        </a>
    </div>

    <?php if (!empty($activities)): ?>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Judul Kegiatan</th>
                        <th>Organizer</th>
                        <th>Wilayah</th>
                        <th>Jadwal Pelaksanaan</th>
                        <th>Peserta</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($activities as $a): ?>
                        <tr>
                            <td class="font-bold text-gray-500 text-xs w-12 text-center">
                            <td>
                                <div class="font-bold text-gray-900 text-sm"><?= e($a['title']) ?></div>
                                <div class="text-xs text-gray-400 truncate max-w-xs"><?= e($a['location_name'] ?? '-') ?></div>
                            </td>
                            <td class="text-xs font-semibold text-[#1D4533]"><?= e($a['organizer_name']) ?></td>
                            <td class="text-xs text-gray-600"><?= e($a['region_name']) ?></td>
                            <td class="text-xs text-gray-700 whitespace-nowrap font-medium">
                                <?= formatDate($a['scheduled_at'], 'd M Y H:i') ?>
                            </td>
                            <td class="text-xs">
                                <a href="<?= url('admin/activities/' . $a['id'] . '/participants') ?>" class="text-[#1D4533] font-bold hover:underline">
                                    <?= (int)($a['participant_count'] ?? 0) ?> Peserta
                                </a>
                            </td>
                            <td>
                                <span class="badge badge-<?= $a['status'] === 'published' ? 'verified' : ($a['status'] === 'completed' ? 'completed' : 'rejected') ?>">
                                    <?= e($a['status']) ?>
                                </span>
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <a href="<?= url('admin/activities/' . $a['id'] . '/participants') ?>" class="btn btn-outline btn-sm text-xs py-1 px-2.5">
                                        Peserta
                                    </a>
                                    <a href="<?= url('admin/activities/' . $a['id'] . '/edit') ?>" class="btn btn-outline btn-sm text-xs py-1 px-2.5">
                                        Edit
                                    </a>
                                    <form action="<?= url('admin/activities/' . $a['id'] . '/delete') ?>"
                                          method="POST"
                                          data-confirm="Apakah Anda yakin ingin menghapus kegiatan ini?"
                                          data-confirm-danger
                                          data-confirm-title="Hapus Kegiatan?"
                                          data-confirm-yes="Ya, Hapus"
                                          data-confirm-no="Batal">
                                        <?= csrfField() ?>
                                        <button type="submit" class="btn btn-danger btn-sm text-xs py-1 px-2.5">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-state bg-white rounded-2xl border border-gray-200 p-12">
            <h3 class="text-base font-bold text-gray-800 mb-1">Belum Ada Kegiatan</h3>
            <p class="text-xs text-gray-500 mb-4">Tambahkan agenda kerja bakti atau kegiatan pembersihan lingkungan.</p>
            <a href="<?= url('admin/activities/create') ?>" class="btn btn-primary btn-sm">Tambah Kegiatan</a>
        </div>
    <?php endif; ?>

</div>
