<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-base font-bold text-gray-900">Daftar Banner & Slider</h2>
            <p class="text-xs text-gray-500">Kelola banner promosi dan pengumuman di halaman depan website secara dinamis.</p>
        </div>
        <a href="<?= url('admin/banners/create') ?>" class="btn btn-primary btn-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Banner Baru
        </a>
    </div>

    <?php if (!empty($banners)): ?>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Urutan</th>
                        <th>Preview</th>
                        <th>Judul Banner</th>
                        <th>Tautan (URL)</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($banners as $b): ?>
                        <tr>
                            <td class="font-bold text-gray-500 w-12 text-center">
                                <?= (int)$b['order'] ?>
                            </td>
                            <td class="w-24">
                                <?php if (!empty($b['image_path']) && file_exists(UPLOAD_PATH . '/' . $b['image_path'])): ?>
                                    <img src="<?= uploadUrl($b['image_path']) ?>" class="w-20 h-10 object-cover rounded-lg bg-gray-100" alt="Banner">
                                <?php else: ?>
                                    <div class="w-20 h-10 bg-gray-200 rounded-lg flex items-center justify-center text-[10px] text-gray-500">No Image</div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="font-bold text-gray-900 text-sm"><?= e($b['title']) ?></div>
                                <?php if (!empty($b['subtitle'])): ?>
                                    <div class="text-xs text-gray-500 truncate max-w-xs"><?= e($b['subtitle']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="text-xs text-gray-600">
                                <?= e($b['link_url'] ?? '-') ?>
                            </td>
                            <td>
                                <form action="<?= url('admin/banners/' . $b['id'] . '/toggle') ?>" method="POST">
                                    <?= csrfField() ?>
                                    <button type="submit" class="badge <?= $b['is_active'] ? 'badge-completed' : 'badge-rejected' ?> cursor-pointer">
                                        <?= $b['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                                    </button>
                                </form>
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <a href="<?= url('admin/banners/' . $b['id'] . '/edit') ?>" class="btn btn-outline btn-sm text-xs py-1 px-2.5">
                                        Edit
                                    </a>
                                    <form action="<?= url('admin/banners/' . $b['id'] . '/delete') ?>"
                                          method="POST"
                                          data-confirm="Apakah Anda yakin ingin menghapus banner ini?"
                                          data-confirm-danger
                                          data-confirm-title="Hapus Banner?"
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
            <h3 class="text-base font-bold text-gray-800 mb-1">Belum Ada Banner</h3>
            <p class="text-xs text-gray-500 mb-4">Tambahkan banner untuk menghiasi halaman beranda website.</p>
            <a href="<?= url('admin/banners/create') ?>" class="btn btn-primary btn-sm">Tambah Banner</a>
        </div>
    <?php endif; ?>

</div>
