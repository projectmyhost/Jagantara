<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-base font-bold text-gray-900">Manajemen Organizer & Komunitas</h2>
            <p class="text-xs text-gray-500">Kelola komunitas organizer, verifikasi pendaftaran anggota, dan wilayah operasional.</p>
        </div>
        <a href="<?= url('admin/organizers/create') ?>" class="btn btn-primary btn-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Organizer Baru
        </a>
    </div>

    <?php if (!empty($organizers)): ?>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Organizer</th>
                        <th>Wilayah</th>
                        <th>Anggota</th>
                        <th>Permohonan Pending</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($organizers as $org): ?>
                        <tr>
                            <td class="font-bold text-gray-500 text-xs">
                            <td>
                                <div class="font-bold text-gray-900 text-sm"><?= e($org['name']) ?></div>
                                <div class="text-xs text-gray-400 truncate max-w-xs"><?= e($org['description']) ?></div>
                            </td>
                            <td class="text-xs text-gray-600"><?= e($org['region_name'] ?? 'JABODETABEK') ?></td>
                            <td class="text-xs font-bold text-gray-700"><?= (int)($org['member_count'] ?? 0) ?></td>
                            <td>
                                <?php if ((int)($org['pending_apps'] ?? 0) > 0): ?>
                                    <span class="badge badge-pending font-bold">
                                        <?= (int)$org['pending_apps'] ?> Menunggu
                                    </span>
                                <?php else: ?>
                                    <span class="text-xs text-gray-400">0</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge <?= $org['is_active'] ? 'badge-completed' : 'badge-rejected' ?>">
                                    <?= $org['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                                </span>
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <a href="<?= url('admin/organizers/' . $org['id'] . '/applications') ?>" class="btn btn-primary btn-sm text-xs py-1 px-2.5">
                                        Permohonan
                                    </a>
                                    <a href="<?= url('admin/organizers/' . $org['id'] . '/edit') ?>" class="btn btn-outline btn-sm text-xs py-1 px-2.5">
                                        Edit
                                    </a>
                                    <form action="<?= url('admin/organizers/' . $org['id'] . '/delete') ?>"
                                          method="POST"
                                          data-confirm="Apakah Anda yakin ingin menghapus organizer ini?"
                                          data-confirm-danger
                                          data-confirm-title="Hapus Organizer?"
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
            <h3 class="text-base font-bold text-gray-800 mb-1">Belum Ada Organizer</h3>
            <p class="text-xs text-gray-500 mb-4">Tambahkan komunitas organizer pertama untuk memulai.</p>
            <a href="<?= url('admin/organizers/create') ?>" class="btn btn-primary btn-sm">Tambah Organizer</a>
        </div>
    <?php endif; ?>

</div>
