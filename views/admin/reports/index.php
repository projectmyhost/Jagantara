<div class="space-y-6">

    <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
        <form method="GET" action="<?= url('admin/reports') ?>" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">

            <div class="lg:col-span-2">
                <label for="q" class="text-xs font-bold text-gray-700 block mb-1">Cari Kata Kunci</label>
                <input type="text" id="q" name="q" value="<?= e($filters['search'] ?? '') ?>" placeholder="Cari judul / pelapor..." class="form-control text-xs">
            </div>

            <div>
                <label for="status" class="text-xs font-bold text-gray-700 block mb-1">Status</label>
                <select id="status" name="status" class="form-control text-xs">
                    <option value="">Semua Status</option>
                    <?php foreach (REPORT_STATUS_LABELS as $sKey => $sLabel): ?>
                        <option value="<?= $sKey ?>" <?= ($filters['status'] ?? '') === $sKey ? 'selected' : '' ?>>
                            <?= e($sLabel) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="region_id" class="text-xs font-bold text-gray-700 block mb-1">Wilayah</label>
                <select id="region_id" name="region_id" class="form-control text-xs">
                    <option value="">Semua Wilayah</option>
                    <?php foreach ($regions as $r): ?>
                        <option value="<?= $r['id'] ?>" <?= ($filters['region_id'] ?? '') == $r['id'] ? 'selected' : '' ?>>
                            <?= e($r['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit" class="btn btn-primary btn-sm h-[38px] w-full font-semibold">
                    Filter Laporan
                </button>
            </div>
        </form>
    </div>

    <?php if (!empty($reports)): ?>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Foto</th>
                        <th>Judul Laporan</th>
                        <th>Kategori & Wilayah</th>
                        <th>Pelapor</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reports as $r): ?>
                        <tr>
                            <td class="font-bold text-gray-500 text-xs">
                            <td>
                                <?php if (!empty($r['first_photo']) && file_exists(UPLOAD_PATH . '/' . $r['first_photo'])): ?>
                                    <img src="<?= uploadUrl($r['first_photo']) ?>" class="w-14 h-12 object-cover rounded-lg bg-gray-100" alt="Thumbnail">
                                <?php else: ?>
                                    <div class="w-14 h-12 bg-gray-200 rounded-lg flex items-center justify-center text-[9px] text-gray-400">No Img</div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?= url('admin/reports/' . $r['id']) ?>" class="font-bold text-gray-900 text-sm hover:text-[#1D4533]">
                                    <?= e($r['title']) ?>
                                </a>
                            </td>
                            <td class="text-xs text-gray-600">
                                <div><?= e($r['category_name'] ?? 'Umum') ?></div>
                                <div class="text-[11px] text-gray-400"><?= e($r['region_name'] ?? 'JABODETABEK') ?></div>
                            </td>
                            <td class="text-xs text-gray-600">
                                <div class="font-medium text-gray-900"><?= e($r['reporter_name'] ?? 'Warga') ?></div>
                                <div class="text-[11px] text-gray-400"><?= e($r['reporter_email'] ?? '') ?></div>
                            </td>
                            <td>
                                <span class="badge badge-<?= e($r['status']) ?>">
                                    <?= statusLabel($r['status']) ?>
                                </span>
                            </td>
                            <td class="text-xs text-gray-500 whitespace-nowrap">
                                <?= formatDate($r['created_at'], 'd M Y') ?>
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <a href="<?= url('admin/reports/' . $r['id']) ?>" class="btn btn-primary btn-sm text-xs py-1 px-2.5">
                                        Periksa
                                    </a>
                                    <form action="<?= url('admin/reports/' . $r['id'] . '/delete') ?>"
                                          method="POST"
                                          data-confirm="Apakah Anda yakin ingin menghapus laporan ini?"
                                          data-confirm-danger
                                          data-confirm-title="Hapus Laporan?"
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

        <?php if (($pagination['total_pages'] ?? 1) > 1): ?>
            <div class="flex items-center justify-center gap-2 pt-6">
                <?php if ($pagination['has_prev']): ?>
                    <a href="<?= url('admin/reports?page=' . ($pagination['current_page'] - 1) . '&status=' . ($filters['status'] ?? '') . '&region_id=' . ($filters['region_id'] ?? '') . '&q=' . urlencode($filters['search'] ?? '')) ?>" class="btn btn-outline btn-sm">
                        Sebelumnya
                    </a>
                <?php endif; ?>
                <span class="text-xs text-gray-600 font-medium px-2">
                    Halaman <?= $pagination['current_page'] ?> dari <?= $pagination['total_pages'] ?>
                </span>
                <?php if ($pagination['has_next']): ?>
                    <a href="<?= url('admin/reports?page=' . ($pagination['current_page'] + 1) . '&status=' . ($filters['status'] ?? '') . '&region_id=' . ($filters['region_id'] ?? '') . '&q=' . urlencode($filters['search'] ?? '')) ?>" class="btn btn-outline btn-sm">
                        Selanjutnya
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <div class="empty-state bg-white rounded-2xl border border-gray-200 p-12">
            <h3 class="text-base font-bold text-gray-800 mb-1">Tidak Ada Laporan</h3>
            <p class="text-xs text-gray-500">Tidak ada laporan yang sesuai dengan filter pencarian saat ini.</p>
        </div>
    <?php endif; ?>

</div>
