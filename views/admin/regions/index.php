<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-base font-bold text-gray-900">Manajemen Wilayah & Daerah (JABODETABEK)</h2>
            <p class="text-xs text-gray-500">Kelola hierarki wilayah dan kecamatan untuk filter pelaporan terstruktur.</p>
        </div>
        <a href="<?= url('admin/regions/create') ?>" class="btn btn-primary btn-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Wilayah Baru
        </a>
    </div>

    <?php if (!empty($regions)): ?>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Wilayah</th>
                        <th>Slug</th>
                        <th>Induk Wilayah</th>
                        <th>Urutan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($regions as $r): ?>
                        <tr>
                            <td class="font-bold text-gray-500 text-xs w-12 text-center">
                            <td>
                                <div class="font-bold text-gray-900 text-sm">
                                    <?= empty($r['parent_id']) ? '<strong>' . e($r['name']) . '</strong>' : '&mdash; ' . e($r['name']) ?>
                                </div>
                            </td>
                            <td class="text-xs font-mono text-gray-500"><?= e($r['slug']) ?></td>
                            <td class="text-xs text-gray-600">
                                <?= !empty($r['parent_name']) ? '<span class="bg-gray-100 px-2 py-0.5 rounded font-semibold text-gray-700">' . e($r['parent_name']) . '</span>' : '<em>(Wilayah Induk)</em>' ?>
                            </td>
                            <td class="text-xs text-gray-500"><?= (int)$r['order'] ?></td>
                            <td>
                                <span class="badge <?= $r['is_active'] ? 'badge-completed' : 'badge-rejected' ?>">
                                    <?= $r['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                                </span>
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <a href="<?= url('admin/regions/' . $r['id'] . '/edit') ?>" class="btn btn-outline btn-sm text-xs py-1 px-2.5">
                                        Edit
                                    </a>
                                    <form action="<?= url('admin/regions/' . $r['id'] . '/delete') ?>"
                                          method="POST"
                                          data-confirm="Apakah Anda yakin ingin menghapus wilayah ini?"
                                          data-confirm-danger
                                          data-confirm-title="Hapus Wilayah?"
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
            <h3 class="text-base font-bold text-gray-800 mb-1">Belum Ada Wilayah</h3>
            <p class="text-xs text-gray-500 mb-4">Tambahkan wilayah JABODETABEK untuk pengelompokan laporan.</p>
            <a href="<?= url('admin/regions/create') ?>" class="btn btn-primary btn-sm">Tambah Wilayah</a>
        </div>
    <?php endif; ?>

</div>
