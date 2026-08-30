<div class="page-header">
    <div class="container">
        <h1>Status Keanggotaan Organizer</h1>
        <p>Pantau status permohonan gabung dan komunitas organizer tempat Anda terdaftar.</p>
    </div>
</div>

<div class="container py-8 max-w-4xl">

    <div class="mb-10">
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-[#1D4533]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            Keanggotaan Aktif
        </h2>

        <?php if (!empty($memberships)): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <?php foreach ($memberships as $m): ?>
                    <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
                        <div class="flex items-center justify-between mb-2">
                            <span class="badge badge-completed">Anggota Aktif</span>
                            <span class="text-xs text-gray-400">Bergabung: <?= formatDate($m['joined_at']) ?></span>
                        </div>
                        <h3 class="font-bold text-gray-900 text-base mb-1">
                            <?= e($m['organizer_name']) ?>
                        </h3>
                        <p class="text-xs text-gray-500 mb-4">Wilayah: <?= e($m['region_name'] ?? 'JABODETABEK') ?></p>
                        <a href="<?= url('organizers/' . $m['organizer_id']) ?>" class="btn btn-outline btn-sm w-full text-xs">
                            Halaman Organizer
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-2xl border border-gray-200 p-8 text-center text-xs text-gray-500">
                Anda belum terdaftar sebagai anggota di organizer mana pun.
                <div class="mt-3">
                    <a href="<?= url('organizers') ?>" class="btn btn-primary btn-sm">Jelajahi Organizer</a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div>
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-[#1D4533]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Riwayat Permohonan Pendaftaran
        </h2>

        <?php if (!empty($applications)): ?>
            <div class="space-y-3">
                <?php foreach ($applications as $app): ?>
                    <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <?php if ($app['status'] === 'approved'): ?>
                                    <span class="badge badge-completed">Disetujui</span>
                                <?php elseif ($app['status'] === 'rejected'): ?>
                                    <span class="badge badge-rejected">Ditolak</span>
                                <?php else: ?>
                                    <span class="badge badge-pending">Menunggu Verifikasi</span>
                                <?php endif; ?>

                                <span class="text-xs text-gray-400">
                                    Diajukan: <?= formatDate($app['created_at'], 'd M Y H:i') ?>
                                </span>
                            </div>

                            <h3 class="font-bold text-gray-900 text-sm">
                                <?= e($app['organizer_name']) ?>
                            </h3>

                            <?php if ($app['status'] === 'rejected' && !empty($app['rejection_reason'])): ?>
                                <div class="mt-2 text-xs text-red-700 bg-red-50 p-2.5 rounded-lg border border-red-100">
                                    <span class="font-bold">Alasan Penolakan:</span> <?= e($app['rejection_reason']) ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="flex-shrink-0">
                            <a href="<?= url('organizers/' . $app['organizer_id']) ?>" class="btn btn-outline btn-sm text-xs">
                                Lihat Komunitas
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-2xl border border-gray-200 p-8 text-center text-xs text-gray-500">
                Belum ada riwayat permohonan pendaftaran.
            </div>
        <?php endif; ?>
    </div>

</div>
