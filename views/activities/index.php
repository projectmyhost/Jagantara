<div class="page-header">
    <div class="container">
        <h1>Kegiatan & Kerja Bakti Lingkungan</h1>
        <p>Ikuti agenda kegiatan pembersihan, kerja bakti, dan edukasi lingkungan di JABODETABEK.</p>
    </div>
</div>

<div class="container py-8">

    <div class="bg-white rounded-2xl border border-gray-200 p-5 mb-8 shadow-sm">
        <form method="GET" action="<?= url('activities') ?>" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
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
                <label for="status" class="text-xs font-bold text-gray-700 block mb-1">Status Kegiatan</label>
                <select id="status" name="status" class="form-control text-xs">
                    <option value="">Semua Status</option>
                    <option value="published" <?= ($filters['status'] ?? '') === 'published' ? 'selected' : '' ?>>Akan Datang</option>
                    <option value="ongoing" <?= ($filters['status'] ?? '') === 'ongoing' ? 'selected' : '' ?>>Sedang Berlangsung</option>
                    <option value="completed" <?= ($filters['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Selesai</option>
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit" class="btn btn-primary btn-sm h-[38px] w-full font-semibold">
                    Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    <?php if (!empty($activities)): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <?php foreach ($activities as $act): ?>
                <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="text-xs bg-emerald-100 text-emerald-800 font-semibold px-2.5 py-0.5 rounded-full">
                                <?= e($act['organizer_name']) ?>
                            </span>
                            <span class="badge badge-<?= $act['status'] === 'published' ? 'verified' : ($act['status'] === 'completed' ? 'completed' : 'in_progress') ?>">
                                <?= match($act['status']) {
                                    'published' => 'Akan Datang',
                                    'ongoing' => 'Berlangsung',
                                    'completed' => 'Selesai',
                                    default => ucfirst($act['status'])
                                } ?>
                            </span>
                        </div>

                        <h3 class="text-base font-bold text-gray-900 mb-2">
                            <a href="<?= url('activities/' . $act['id']) ?>" class="hover:text-[#1D4533]">
                                <?= e($act['title']) ?>
                            </a>
                        </h3>

                        <p class="text-xs text-gray-600 line-clamp-3 mb-4 leading-relaxed">
                            <?= e(truncate($act['description'], 140)) ?>
                        </p>
                    </div>

                    <div class="space-y-3 pt-4 border-t border-gray-100 text-xs text-gray-600">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span><?= formatDate($act['scheduled_at'] ?? date('Y-m-d'), 'd M Y H:i') ?> WIB</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="truncate"><?= e($act['location_name'] ?? $act['region_name']) ?></span>
                        </div>

                        <div class="flex items-center justify-between pt-2">
                            <span class="text-xs font-semibold text-gray-500">
                                <?= (int)($act['participant_count'] ?? 0) ?> Peserta <?= !empty($act['max_participants']) ? '/ Kuota ' . $act['max_participants'] : '' ?>
                            </span>
                            <a href="<?= url('activities/' . $act['id']) ?>" class="btn btn-primary btn-sm text-xs">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (($pagination['total_pages'] ?? 1) > 1): ?>
            <div class="flex items-center justify-center gap-2 pt-6">
                <?php if ($pagination['has_prev']): ?>
                    <a href="<?= url('activities?page=' . $pagination['prev_page'] . '&region_id=' . ($filters['region_id'] ?? '') . '&status=' . ($filters['status'] ?? '')) ?>" class="btn btn-outline btn-sm">
                        Sebelumnya
                    </a>
                <?php endif; ?>
                <span class="text-xs text-gray-600 font-medium px-2">
                    Halaman <?= $pagination['current_page'] ?> dari <?= $pagination['total_pages'] ?>
                </span>
                <?php if ($pagination['has_next']): ?>
                    <a href="<?= url('activities?page=' . $pagination['next_page'] . '&region_id=' . ($filters['region_id'] ?? '') . '&status=' . ($filters['status'] ?? '')) ?>" class="btn btn-outline btn-sm">
                        Selanjutnya
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <div class="empty-state bg-white rounded-2xl border border-gray-200 p-12">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <h3 class="text-base font-bold text-gray-800 mb-1">Belum Ada Kegiatan Ditemukan</h3>
            <p class="text-xs text-gray-500 max-w-sm mx-auto">Tidak ada jadwal kegiatan atau kerja bakti dengan filter saat ini.</p>
        </div>
    <?php endif; ?>

</div>
