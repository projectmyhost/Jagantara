<div class="page-header">
    <div class="container">
        <h1>Daftar Laporan Masyarakat</h1>
        <p>Pantau laporan kondisi lingkungan di wilayah JABODETABEK dan perkembangan tindak lanjutnya.</p>
    </div>
</div>

<div class="container py-8">

    <div class="bg-white rounded-2xl border border-gray-200 p-5 mb-8 shadow-sm">
        <form method="GET" action="<?= url('reports') ?>" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">

            <div class="lg:col-span-2">
                <label for="q" class="text-xs font-bold text-gray-700 block mb-1">Cari Kata Kunci</label>
                <div class="relative">
                    <input type="text" id="q" name="q" value="<?= e($filters['search'] ?? '') ?>"
                           placeholder="Cari judul atau keterangan..."
                           class="form-control text-xs">
                </div>
            </div>

            <div>
                <label for="region_id" class="text-xs font-bold text-gray-700 block mb-1">Wilayah</label>
                <div class="searchable-select-wrapper">
                    <input type="hidden" id="region_id" name="region_id" value="<?= e($filters['region_id'] ?? '') ?>">
                    <div class="searchable-select" data-placeholder="Semua Wilayah">
                        <div class="searchable-select-trigger">
                            <span class="searchable-select-value">Semua Wilayah</span>
                            <svg class="searchable-select-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                        <div class="searchable-select-dropdown">
                            <div class="searchable-select-search">
                                <input type="text" class="searchable-select-input" placeholder="Cari wilayah...">
                            </div>
                            <ul class="searchable-select-options">
                                <li class="searchable-select-option <?= empty($filters['region_id']) ? 'selected' : '' ?>" data-value="">Semua Wilayah</li>
                                <?php foreach ($regions as $r): ?>
                                    <li class="searchable-select-option <?= ($filters['region_id'] ?? '') == $r['id'] ? 'selected' : '' ?>"
                                        data-value="<?= $r['id'] ?>"
                                        data-search="<?= strtolower(e($r['name']) . ' ' . ($r['parent_name'] ?? '')) ?>">
                                        <?= e($r['name']) ?> <?= !empty($r['parent_name']) ? '(' . e($r['parent_name']) . ')' : '' ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <label for="category" class="text-xs font-bold text-gray-700 block mb-1">Kategori</label>
                <select id="category" name="category" class="form-control text-xs" onchange="toggleCustomCategoryInput(this)">
                    <option value="">Semua Kategori</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= $c['id'] ?>"
                                data-slug="<?= e($c['slug'] ?? '') ?>"
                                <?= ($filters['category_id'] ?? '') == $c['id'] ? 'selected' : '' ?>>
                            <?= e($c['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <div id="custom-category-wrapper" class="mt-2" style="display: none;">
                    <input type="text"
                           id="custom_category"
                           name="custom_category"
                           value="<?= e($filters['custom_category'] ?? '') ?>"
                           placeholder="Ketik kategori yang Anda cari..."
                           class="form-control text-xs">
                </div>
            </div>

            <div class="flex items-end gap-2">
                <div class="flex-1">
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
                <button type="submit" class="btn btn-primary btn-sm h-[38px] px-4 font-semibold">
                    Filter
                </button>
            </div>

        </form>
    </div>

    <?php if (!empty($reports)): ?>
        <div class="report-grid mb-8">
            <?php foreach ($reports as $report): ?>
                <a href="<?= url('reports/' . $report['id']) ?>" class="card group block">
                    <div class="relative overflow-hidden bg-gray-100">
                        <?php
                            $photo = $report['primary_photo'] ?? $report['first_photo'] ?? null;
                        ?>
                        <?php if (!empty($photo) && file_exists(UPLOAD_PATH . '/' . $photo)): ?>
                            <img src="<?= uploadUrl($photo) ?>"
                                 alt="<?= e($report['title']) ?>"
                                 class="card-img group-hover:scale-105 transition duration-300">
                        <?php else: ?>
                            <div class="card-img flex items-center justify-center bg-[#F7EAE0] text-[#5E3122]">
                                <svg class="w-8 h-8 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        <?php endif; ?>

                        <div class="absolute top-2.5 right-2.5">
                            <span class="badge badge-<?= e($report['status']) ?>">
                                <?= statusLabel($report['status']) ?>
                            </span>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="flex items-center gap-2 mb-1.5 text-xs text-gray-500 font-medium">
                            <span class="text-[#1D4533] font-semibold"><?= e($report['category_name'] ?? 'Umum') ?></span>
                            <span>&bull;</span>
                            <span><?= e($report['region_name'] ?? 'Jabodetabek') ?></span>
                        </div>

                        <h3 class="card-title text-gray-900 group-hover:text-[#1D4533] transition">
                            <?= e($report['title']) ?>
                        </h3>

                        <div class="card-meta mt-2 pt-2 border-t border-gray-100">
                            <span><?= formatDate($report['created_at'], 'relative') ?></span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

        <?php if (($pagination['total_pages'] ?? 1) > 1): ?>
            <div class="flex items-center justify-center gap-2 pt-4">
                <?php if ($pagination['has_prev']): ?>
                    <a href="<?= url('reports?page=' . $pagination['prev_page'] . '&region_id=' . ($filters['region_id'] ?? '') . '&category=' . ($filters['category_id'] ?? '') . '&status=' . ($filters['status'] ?? '') . '&q=' . urlencode($filters['search'] ?? '') . '&custom_category=' . urlencode($filters['custom_category'] ?? '')) ?>" class="btn btn-outline btn-sm">
                        Sebelumnya
                    </a>
                <?php endif; ?>

                <span class="text-xs text-gray-600 font-medium px-2">
                    Halaman <?= $pagination['current_page'] ?> dari <?= $pagination['total_pages'] ?> (Total <?= $pagination['total'] ?> laporan)
                </span>

                <?php if ($pagination['has_next']): ?>
                    <a href="<?= url('reports?page=' . $pagination['next_page'] . '&region_id=' . ($filters['region_id'] ?? '') . '&category=' . ($filters['category_id'] ?? '') . '&status=' . ($filters['status'] ?? '') . '&q=' . urlencode($filters['search'] ?? '') . '&custom_category=' . urlencode($filters['custom_category'] ?? '')) ?>" class="btn btn-outline btn-sm">
                        Selanjutnya
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <div class="empty-state bg-white rounded-2xl border border-gray-200 p-12">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="text-base font-bold text-gray-800 mb-1">Tidak Ada Laporan Ditemukan di Wilayah Ini</h3>
            <p class="text-xs text-gray-500 max-w-sm mx-auto mb-4">Coba sesuaikan filter pencarian atau buat laporan baru di wilayah ini.</p>
            <?php if (isLoggedIn()): ?>
                <a href="<?= url('reports/create') ?>" class="btn btn-primary btn-sm">Buat Laporan Baru</a>
            <?php else: ?>
                <button type="button" onclick="openGuestAuthModal('lapor')" class="btn btn-primary btn-sm">Buat Laporan Baru</button>
            <?php endif; ?>
        </div>
    <?php endif; ?>

</div>
