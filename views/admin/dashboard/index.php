<div class="space-y-6">

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center font-bold text-xl flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <div class="text-2xl font-extrabold text-gray-900"><?= number_format($reportStats['pending'] ?? 0) ?></div>
                <div class="text-xs text-gray-500 font-semibold">Menunggu Verifikasi</div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-xl flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <div class="text-2xl font-extrabold text-gray-900"><?= number_format($reportStats['total'] ?? 0) ?></div>
                <div class="text-xs text-gray-500 font-semibold">Total Laporan Warga</div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-800 flex items-center justify-center font-bold text-xl flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div>
                <div class="text-2xl font-extrabold text-gray-900"><?= number_format($organizerCount ?? 0) ?></div>
                <div class="text-xs text-gray-500 font-semibold">Organizer Komunitas</div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-800 flex items-center justify-center font-bold text-xl flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <div>
                <div class="text-2xl font-extrabold text-gray-900"><?= number_format($userStats['total'] ?? 0) ?></div>
                <div class="text-xs text-gray-500 font-semibold">Total Pengguna Terdaftar</div>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-bold text-gray-900">Laporan Menunggu Verifikasi</h2>
                    <a href="<?= url('admin/reports?status=pending') ?>" class="text-xs font-semibold text-[#1D4533] hover:underline">
                        Lihat Semua
                    </a>
                </div>

                <?php if (!empty($pendingReports)): ?>
                    <div class="divide-y divide-gray-100">
                        <?php foreach ($pendingReports as $pr): ?>
                            <div class="py-3.5 flex items-center justify-between gap-4">
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="badge badge-pending">Menunggu</span>
                                        <span class="text-xs text-gray-500"><?= e($pr['category_name'] ?? 'Umum') ?> &bull; <?= e($pr['region_name'] ?? 'Jabodetabek') ?></span>
                                    </div>
                                    <h4 class="text-sm font-bold text-gray-900 truncate">
                                        <a href="<?= url('admin/reports/' . $pr['id']) ?>" class="hover:text-[#1D4533]">
                                            <?= e($pr['title']) ?>
                                        </a>
                                    </h4>
                                    <span class="text-[11px] text-gray-400">
                                        Pelapor: <?= e($pr['reporter_name'] ?? 'Warga') ?> &bull; <?= formatDate($pr['created_at'], 'relative') ?>
                                    </span>
                                </div>

                                <a href="<?= url('admin/reports/' . $pr['id']) ?>" class="btn btn-primary btn-sm text-xs flex-shrink-0">
                                    Verifikasi
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-xs text-gray-500 py-6 text-center">Tidak ada laporan yang menunggu verifikasi saat ini.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="space-y-4">
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-bold text-gray-900">Aktivitas Sistem</h2>
                    <a href="<?= url('admin/audit-logs') ?>" class="text-xs font-semibold text-[#1D4533] hover:underline">
                        Semua Log
                    </a>
                </div>

                <?php if (!empty($recentAudit)): ?>
                    <div class="space-y-3">
                        <?php foreach ($recentAudit as $log): ?>
                            <div class="text-xs p-2.5 rounded-xl bg-gray-50 border border-gray-100">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="font-bold text-gray-800 uppercase text-[10px] tracking-wide"><?= e($log['action']) ?></span>
                                    <span class="text-[10px] text-gray-400"><?= formatDate($log['created_at'], 'relative') ?></span>
                                </div>
                                <div class="text-[11px] text-gray-600 truncate">
                                    User: <?= e($log['email'] ?? 'Sistem/Guest') ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-xs text-gray-500 text-center py-4">Belum ada aktivitas.</p>
                <?php endif; ?>
            </div>
        </div>

    </div>

</div>
