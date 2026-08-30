<div class="container py-8">

    <div class="bg-white rounded-2xl border border-gray-200 p-6 sm:p-8 shadow-sm mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">

            <div class="flex items-center gap-5">
                <div class="w-20 h-20 rounded-2xl bg-[#F7EAE0] text-[#1D4533] flex items-center justify-center font-bold text-3xl flex-shrink-0 overflow-hidden border border-[#F9D2BA] shadow-sm">
                    <?php if (!empty($organizer['logo_path']) && file_exists(UPLOAD_PATH . '/' . $organizer['logo_path'])): ?>
                        <img src="<?= uploadUrl($organizer['logo_path']) ?>" class="w-full h-full object-cover" alt="<?= e($organizer['name']) ?>">
                    <?php else: ?>
                        <?= strtoupper(substr($organizer['name'], 0, 1)) ?>
                    <?php endif; ?>
                </div>

                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="bg-emerald-100 text-emerald-800 text-xs px-2.5 py-0.5 rounded-full font-semibold">
                            Organizer Terverifikasi
                        </span>
                        <span class="text-xs text-gray-500 font-medium"><?= e($organizer['region_name'] ?? 'JABODETABEK') ?></span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-gray-900"><?= e($organizer['name']) ?></h1>
                    <p class="text-xs text-gray-500 mt-1"><?= (int)($organizer['member_count'] ?? 0) ?> Anggota Terdaftar</p>
                </div>
            </div>

            <div class="flex-shrink-0">
                <?php if ($isMember): ?>
                    <span class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-emerald-50 text-emerald-800 rounded-xl text-xs font-bold border border-emerald-200">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Anda Sudah Bergabung
                    </span>
                <?php elseif ($hasPendingApp): ?>
                    <span class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-amber-50 text-amber-800 rounded-xl text-xs font-bold border border-amber-200">
                        Menunggu Verifikasi Pendaftaran
                    </span>
                <?php elseif (!isOrganizerJoinEnabled()): ?>
                    <span class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-gray-100 text-gray-500 rounded-xl text-xs font-semibold border border-gray-200 cursor-not-allowed" title="Pendaftaran anggota baru sedang ditutup oleh administrator">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Pendaftaran Anggota Ditutup
                    </span>
                <?php else: ?>
                    <a href="<?= url('organizers/join/' . $organizer['id']) ?>" class="btn btn-primary py-3 px-6 font-bold shadow-md text-sm">
                        Gabung Organizer Ini
                    </a>
                <?php endif; ?>
            </div>

        </div>

        <?php if (!isOrganizerJoinEnabled() && !$isMember && !$hasPendingApp): ?>
            <div class="mt-6 p-4 rounded-xl bg-gradient-to-r from-amber-50 to-orange-50/50 border border-amber-200 flex items-start gap-3 text-xs text-amber-900">
                <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="leading-relaxed">
                    <strong class="font-bold text-amber-950">Informasi Pendaftaran:</strong> Penerimaan anggota baru untuk komunitas/organizer ini sedang <span class="font-semibold bg-amber-200/60 px-1.5 py-0.5 rounded text-amber-950">ditutup sementara</span> oleh Administrator. Warga tetap dapat melihat agenda kegiatan lingkungan yang diselenggarakan.
                </div>
            </div>
        <?php endif; ?>

        <hr class="my-6 border-gray-100">

        <div>
            <h2 class="text-sm font-bold text-gray-900 mb-2">Tentang Organizer</h2>
            <p class="text-xs sm:text-sm text-gray-700 leading-relaxed whitespace-pre-line">
                <?= e($organizer['description'] ?? 'Organizer aksi lingkungan di wilayah JABODETABEK.') ?>
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-2 space-y-6">
            <h2 class="text-lg font-bold text-gray-900">Kegiatan & Aksi Lingkungan</h2>

            <?php if (!empty($activities)): ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <?php foreach ($activities as $act): ?>
                        <div class="card p-5 flex flex-col justify-between">
                            <div>
                                <span class="text-[11px] bg-gray-100 text-gray-600 px-2 py-0.5 rounded font-semibold mb-2 inline-block">
                                    <?= formatDate($act['scheduled_at'] ?? date('Y-m-d'), 'd M Y') ?>
                                </span>
                                <h3 class="font-bold text-gray-900 text-sm mb-2">
                                    <a href="<?= url('activities/' . $act['id']) ?>" class="hover:text-[#1D4533]">
                                        <?= e($act['title']) ?>
                                    </a>
                                </h3>
                                <p class="text-xs text-gray-600 line-clamp-2 leading-relaxed">
                                    <?= e(truncate($act['description'], 100)) ?>
                                </p>
                            </div>

                            <div class="pt-3 border-t border-gray-100 mt-4 flex items-center justify-between">
                                <span class="text-[11px] text-gray-500"><?= e($act['location_name'] ?? '-') ?></span>
                                <a href="<?= url('activities/' . $act['id']) ?>" class="btn btn-outline btn-sm text-xs py-1">
                                    Detail
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="bg-white rounded-2xl border border-gray-200 p-8 text-center text-xs text-gray-500">
                    Belum ada agenda kegiatan yang dipublikasikan oleh organizer ini.
                </div>
            <?php endif; ?>
        </div>

        <div>
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <h3 class="text-base font-bold text-gray-900 mb-4">Anggota Terdaftar</h3>

                <?php if (!empty($members)): ?>
                    <div class="space-y-3 max-h-96 overflow-y-auto pr-1">
                        <?php foreach ($members as $m): ?>
                            <div class="flex items-center gap-3 p-2 rounded-xl bg-gray-50 border border-gray-100">
                                <div class="w-8 h-8 rounded-full bg-[#1D4533] text-white flex items-center justify-center text-xs font-bold overflow-hidden flex-shrink-0">
                                    <?php if (!empty($m['avatar_path']) && file_exists(UPLOAD_PATH . '/' . $m['avatar_path'])): ?>
                                        <img src="<?= uploadUrl($m['avatar_path']) ?>" class="w-full h-full object-cover" alt="Member">
                                    <?php else: ?>
                                        <?= strtoupper(substr($m['full_name'] ?? 'M', 0, 1)) ?>
                                    <?php endif; ?>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="text-xs font-bold text-gray-900 truncate">
                                        <?= e($m['full_name'] ?? 'Anggota') ?>
                                    </div>
                                    <div class="text-[10px] text-gray-500 capitalize">
                                        <?= e($m['role'] ?? 'member') ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-xs text-gray-500">Belum ada anggota yang ditampilkan.</p>
                <?php endif; ?>
            </div>
        </div>

    </div>

</div>
