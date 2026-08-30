<div class="page-header">
    <div class="container">
        <h1>Komunitas & Organizer Lingkungan</h1>
        <p>Temukan komunitas penggerak aksi lingkungan di wilayah Anda dan bergabunglah bersama mereka.</p>
    </div>
</div>

<div class="container py-8">

    <?php if (!isOrganizerJoinEnabled()): ?>

        <div class="relative overflow-hidden mb-8 rounded-2xl bg-gradient-to-r from-amber-50 via-orange-50/60 to-amber-50 border border-amber-300 p-5 sm:p-6 shadow-sm">

            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-amber-400/10 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-100/90 text-amber-800 border border-amber-300 flex items-center justify-center flex-shrink-0 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-amber-200/80 text-amber-900 border border-amber-300/80">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-600 animate-pulse"></span>
                                Informasi Pendaftaran
                            </span>
                        </div>
                        <h2 class="text-base sm:text-lg font-extrabold text-amber-950 mt-1">
                            Pendaftaran Anggota Baru Organizer Sedang Ditutup Sementara
                        </h2>
                        <p class="text-xs sm:text-sm text-amber-900/85 mt-1.5 leading-relaxed max-w-3xl">
                            Saat ini seluruh pengajuan pendaftaran anggota baru untuk komunitas / organizer lingkungan sedang <span class="font-bold text-amber-950 bg-amber-200/60 px-1.5 py-0.5 rounded">dinonaktifkan sementara</span> oleh Administrator. Warga tetap dapat melihat profil organizer dan dokumentasi kegiatan aksi lingkungan di bawah ini.
                        </p>
                    </div>
                </div>

                <div class="sm:flex-shrink-0 self-stretch sm:self-auto flex items-center justify-end">
                    <span class="inline-flex items-center gap-1.5 px-3 py-2 bg-white/80 border border-amber-300 text-amber-900 rounded-xl text-xs font-bold shadow-xs">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Rekrutmen Ditutup
                    </span>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Daftar Organizer Aktif</h2>
            <p class="text-xs text-gray-500">Komunitas terverifikasi yang mengorganisir kerja bakti dan penanganan laporan.</p>
        </div>
        <?php if (isLoggedIn()): ?>
            <a href="<?= url('organizers/my-status') ?>" class="btn btn-outline btn-sm">
                Status Keanggotaan Saya
            </a>
        <?php endif; ?>
    </div>

    <?php if (!empty($organizers)): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($organizers as $org): ?>
                <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                    <div>
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-14 h-14 rounded-2xl bg-[#F7EAE0] text-[#1D4533] flex items-center justify-center font-bold text-xl flex-shrink-0 overflow-hidden border border-[#F9D2BA]">
                                <?php if (!empty($org['logo_path']) && file_exists(UPLOAD_PATH . '/' . $org['logo_path'])): ?>
                                    <img src="<?= uploadUrl($org['logo_path']) ?>" class="w-full h-full object-cover" alt="<?= e($org['name']) ?>">
                                <?php else: ?>
                                    <?= strtoupper(substr($org['name'], 0, 1)) ?>
                                <?php endif; ?>
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-bold text-gray-900 text-base truncate">
                                    <?= e($org['name']) ?>
                                </h3>
                                <span class="text-xs text-gray-500 block">
                                    <?= e($org['region_name'] ?? 'JABODETABEK') ?>
                                </span>
                            </div>
                        </div>

                        <p class="text-xs text-gray-600 line-clamp-3 mb-4 leading-relaxed">
                            <?= e($org['description'] ?? 'Komunitas peduli kebersihan dan aksi lingkungan bersama masyarakat.') ?>
                        </p>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                        <div class="text-xs text-gray-500 font-medium">
                            <span><?= (int)($org['member_count'] ?? 0) ?> Anggota</span>
                            <span class="mx-1">&bull;</span>
                            <span><?= (int)($org['activity_count'] ?? 0) ?> Kegiatan</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="<?= url('organizers/' . $org['id']) ?>" class="btn btn-outline btn-sm text-xs">
                                Profil
                            </a>
                            <?php if (isOrganizerJoinEnabled()): ?>
                                <a href="<?= url('organizers/join/' . $org['id']) ?>" class="btn btn-primary btn-sm text-xs">
                                    Gabung
                                </a>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-1.5 rounded-xl bg-amber-50 text-amber-800 border border-amber-200/80" title="Pendaftaran anggota sedang ditutup oleh administrator">
                                    <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    Ditutup
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (($pagination['total_pages'] ?? 1) > 1): ?>
            <div class="flex items-center justify-center gap-2 pt-8">
                <?php if ($pagination['has_prev']): ?>
                    <a href="<?= url('organizers?page=' . $pagination['prev_page']) ?>" class="btn btn-outline btn-sm">
                        Sebelumnya
                    </a>
                <?php endif; ?>
                <span class="text-xs text-gray-600 font-medium px-2">
                    Halaman <?= $pagination['current_page'] ?> dari <?= $pagination['total_pages'] ?>
                </span>
                <?php if ($pagination['has_next']): ?>
                    <a href="<?= url('organizers?page=' . $pagination['next_page']) ?>" class="btn btn-outline btn-sm">
                        Selanjutnya
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <div class="empty-state bg-white rounded-2xl border border-gray-200 p-12">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <h3 class="text-base font-bold text-gray-800 mb-1">Belum Ada Organizer</h3>
            <p class="text-xs text-gray-500 max-w-sm mx-auto">Saat ini belum ada komunitas organizer yang terdaftar.</p>
        </div>
    <?php endif; ?>

</div>
