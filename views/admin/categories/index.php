<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-base font-bold text-gray-900">Kategori Permasalahan Lingkungan</h2>
            <p class="text-xs text-gray-500">Kelola daftar kategori yang dapat dipilih warga saat membuat laporan.</p>
        </div>
        <a href="<?= url('admin/categories/create') ?>" class="btn btn-primary btn-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Kategori Baru
        </a>
    </div>

    <?php if (!empty($categories)): ?>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Urutan</th>
                        <th>Nama Kategori</th>
                        <th>Slug</th>
                        <th>Deskripsi</th>
                        <th>Warna</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $c): ?>
                        <tr>
                            <td class="font-bold text-gray-500 text-xs w-12 text-center"><?= (int)$c['order'] ?></td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <span class="w-3.5 h-3.5 rounded-full flex-shrink-0" style="background-color: <?= e($c['color'] ?? '#1D4533') ?>"></span>
                                    <span class="font-bold text-gray-900 text-sm"><?= e($c['name']) ?></span>
                                </div>
                            </td>
                            <td class="text-xs font-mono text-gray-500"><?= e($c['slug']) ?></td>
                            <td class="text-xs text-gray-600 max-w-xs truncate"><?= e($c['description'] ?? '-') ?></td>
                            <td class="text-xs font-mono text-gray-600"><?= e($c['color'] ?? '#1D4533') ?></td>
                            <td>
                                <span class="badge <?= $c['is_active'] ? 'badge-completed' : 'badge-rejected' ?>">
                                    <?= $c['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                                </span>
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <a href="<?= url('admin/categories/' . $c['id'] . '/edit') ?>" class="btn btn-outline btn-sm text-xs py-1 px-2.5">
                                        Edit
                                    </a>
                                    <form action="<?= url('admin/categories/' . $c['id'] . '/delete') ?>"
                                          method="POST"
                                          data-confirm="Apakah Anda yakin ingin menghapus kategori ini?"
                                          data-confirm-danger
                                          data-confirm-title="Hapus Kategori?"
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
            <h3 class="text-base font-bold text-gray-800 mb-1">Belum Ada Kategori</h3>
            <p class="text-xs text-gray-500 mb-4">Tambahkan kategori lingkungan untuk pelaporan warga.</p>
            <a href="<?= url('admin/categories/create') ?>" class="btn btn-primary btn-sm">Tambah Kategori</a>
        </div>
    <?php endif; ?>

</div>
