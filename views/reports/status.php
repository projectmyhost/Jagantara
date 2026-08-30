<div class="page-header">
    <div class="container">
        <h1>Status Laporan Saya</h1>
        <p>Pantau perkembangan dan tindak lanjut laporan yang telah Anda kirimkan.</p>
    </div>
</div>

<div class="container py-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Riwayat Laporan</h2>
            <p class="text-xs text-gray-500">Semua laporan yang Anda buat tercatat di bawah ini.</p>
        </div>
        <a href="<?= url('reports/create') ?>" class="btn btn-primary btn-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Buat Laporan Baru
        </a>
    </div>

    <?php if (!empty($reports)): ?>
        <div class="space-y-4">
            <?php foreach ($reports as $report): ?>
                <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm transition hover:shadow-md">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">

                        <div class="flex items-start gap-4 flex-1">
                            <?php if (!empty($report['first_photo']) && file_exists(UPLOAD_PATH . '/' . $report['first_photo'])): ?>
                                <img src="<?= uploadUrl($report['first_photo']) ?>"
                                     alt="<?= e($report['title']) ?>"
                                     class="w-20 h-20 rounded-xl object-cover bg-gray-100 flex-shrink-0">
                            <?php else: ?>
                                <div class="w-20 h-20 rounded-xl bg-[#F7EAE0] text-[#5E3122] flex items-center justify-center flex-shrink-0">
                                    <svg class="w-8 h-8 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            <?php endif; ?>

                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                    <span class="badge badge-<?= e($report['status']) ?>">
                                        <?= statusLabel($report['status']) ?>
                                    </span>
                                    <span class="text-xs text-gray-500 font-medium">
                                        <?= e($report['category_name'] ?? 'Umum') ?> &bull; <?= e($report['region_name'] ?? 'JABODETABEK') ?>
                                    </span>
                                </div>

                                <h3 class="text-base font-bold text-gray-900 truncate mb-1">
                                    <a href="<?= url('reports/' . $report['id']) ?>" class="hover:text-[#1D4533]">
                                        <?= e($report['title']) ?>
                                    </a>
                                </h3>

                                <div class="text-xs text-gray-400">
                                    Dikirim pada: <?= formatDate($report['created_at'], 'd M Y H:i') ?>
                                </div>

                                <?php if ($report['status'] === STATUS_REJECTED && !empty($report['rejection_reason'])): ?>
                                    <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-xl text-xs text-red-800">
                                        <span class="font-bold block mb-0.5">Alasan Penolakan oleh Admin:</span>
                                        <?= e($report['rejection_reason']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 md:flex-col md:items-end flex-shrink-0 pt-3 md:pt-0 border-t md:border-t-0 border-gray-100">
                            <a href="<?= url('reports/' . $report['id']) ?>" class="btn btn-outline btn-sm">
                                Detail Laporan
                            </a>

                            <?php if (in_array($report['status'], [STATUS_PENDING, STATUS_REJECTED], true)): ?>
                                <form action="<?= url('reports/' . $report['id'] . '/delete') ?>"
                                      method="POST"
                                      data-confirm="Apakah Anda yakin ingin menghapus laporan ini? Tindakan ini tidak dapat dibatalkan."
                                      data-confirm-danger
                                      data-confirm-title="Hapus Laporan?"
                                      data-confirm-yes="Ya, Hapus"
                                      data-confirm-no="Batal">
                                    <?= csrfField() ?>
                                    <button type="submit" class="btn btn-sm bg-red-600 hover:bg-red-700 text-white font-semibold border-0 shadow-sm">
                                        Hapus Laporan
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (($pagination['total_pages'] ?? 1) > 1): ?>
            <div class="flex items-center justify-center gap-2 pt-6">
                <?php if ($pagination['has_prev']): ?>
                    <a href="<?= url('reports/status?page=' . $pagination['prev_page']) ?>" class="btn btn-outline btn-sm">
                        Sebelumnya
                    </a>
                <?php endif; ?>

                <span class="text-xs text-gray-600 font-medium px-2">
                    Halaman <?= $pagination['current_page'] ?> dari <?= $pagination['total_pages'] ?>
                </span>

                <?php if ($pagination['has_next']): ?>
                    <a href="<?= url('reports/status?page=' . $pagination['next_page']) ?>" class="btn btn-outline btn-sm">
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
            <h3 class="text-base font-bold text-gray-800 mb-1">Anda Belum Membuat Laporan</h3>
            <p class="text-xs text-gray-500 max-w-sm mx-auto mb-4">Laporkan kondisi lingkungan di sekitar Anda untuk membantu aksi pembersihan bersama komunitas.</p>
            <a href="<?= url('reports/create') ?>" class="btn btn-primary btn-sm">Buat Laporan Pertama</a>
        </div>
    <?php endif; ?>

</div>
