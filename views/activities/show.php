<div class="container py-8">

    <div class="flex items-center gap-2 text-xs text-gray-500 mb-6">
        <a href="<?= url('') ?>" class="hover:text-[#1D4533]">Beranda</a>
        <span>/</span>
        <a href="<?= url('activities') ?>" class="hover:text-[#1D4533]">Kegiatan</a>
        <span>/</span>
        <span class="text-gray-800 font-medium truncate max-w-xs"><?= e($activity['title']) ?></span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-2 space-y-6">

            <div class="bg-white rounded-2xl border border-gray-200 p-6 sm:p-8 shadow-sm">
                <div class="flex flex-wrap items-center gap-2 mb-3">
                    <span class="bg-emerald-100 text-emerald-800 text-xs px-2.5 py-0.5 rounded-full font-semibold">
                        <?= e($activity['organizer_name']) ?>
                    </span>
                    <span class="text-xs text-gray-500 font-medium"><?= e($activity['region_name']) ?></span>
                </div>

                <h1 class="text-xl sm:text-2xl font-extrabold text-gray-900 leading-snug mb-4">
                    <?= e($activity['title']) ?>
                </h1>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 rounded-xl bg-gray-50 border border-gray-100 text-xs text-gray-700">
                    <div>
                        <span class="font-bold text-gray-500 block mb-0.5">Waktu Pelaksanaan:</span>
                        <span class="font-medium text-gray-900"><?= formatDate($activity['scheduled_at'] ?? date('Y-m-d'), 'd M Y H:i') ?> WIB</span>
                    </div>
                    <div>
                        <span class="font-bold text-gray-500 block mb-0.5">Lokasi Kegiatan:</span>
                        <span class="font-medium text-gray-900"><?= e($activity['location_name'] ?? '-') ?></span>
                    </div>
                    <div class="sm:col-span-2">
                        <span class="font-bold text-gray-500 block mb-0.5">Alamat Lengkap:</span>
                        <span class="font-medium text-gray-900"><?= e($activity['address'] ?? '-') ?></span>
                    </div>
                </div>

                <hr class="my-6 border-gray-100">

                <h2 class="text-base font-bold text-gray-900 mb-3">Deskripsi Kegiatan</h2>
                <div class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">
                    <?= e($activity['description']) ?>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <h3 class="text-base font-bold text-gray-900 mb-4">
                    Peserta Terdaftar (<?= count($participants) ?> <?= !empty($activity['max_participants']) ? '/ ' . $activity['max_participants'] : '' ?>)
                </h3>

                <?php if (!empty($participants)): ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-80 overflow-y-auto pr-1">
                        <?php foreach ($participants as $p): ?>
                            <div class="flex items-center gap-3 p-2.5 rounded-xl bg-gray-50 border border-gray-100">
                                <div class="w-8 h-8 rounded-full bg-[#1D4533] text-white flex items-center justify-center text-xs font-bold overflow-hidden flex-shrink-0">
                                    <?php if (!empty($p['avatar_path']) && file_exists(UPLOAD_PATH . '/' . $p['avatar_path'])): ?>
                                        <img src="<?= uploadUrl($p['avatar_path']) ?>" class="w-full h-full object-cover" alt="Avatar">
                                    <?php else: ?>
                                        <?= strtoupper(substr($p['full_name'] ?? 'P', 0, 1)) ?>
                                    <?php endif; ?>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="text-xs font-bold text-gray-900 truncate">
                                        <?= e($p['full_name'] ?? $p['username']) ?>
                                    </div>
                                    <div class="text-[10px] text-gray-400">
                                        Mendaftar: <?= formatDate($p['joined_at']) ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-xs text-gray-500">Belum ada peserta yang mendaftar. Jadilah yang pertama!</p>
                <?php endif; ?>
            </div>

        </div>

        <div>
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm space-y-4 sticky top-20">
                <h3 class="text-base font-bold text-gray-900">Partisipasi Aksi</h3>

                <?php if (in_array($activity['status'] ?? '', ['completed', 'cancelled'], true)): ?>
                    <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-600 text-center font-medium">
                        Kegiatan ini telah <?= ($activity['status'] ?? '') === 'completed' ? 'selesai' : 'dibatalkan' ?> dan pendaftaran peserta telah ditutup.
                    </div>
                <?php elseif (isLoggedIn()): ?>
                    <?php if ($isJoined): ?>
                        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-xs text-emerald-800">
                            <span class="font-bold block mb-1">Status: Terdaftar</span>
                            Anda telah terdaftar sebagai peserta dalam kegiatan ini.
                        </div>

                        <form action="<?= url('activities/' . $activity['id'] . '/leave') ?>"
                              method="POST"
                              data-confirm="Apakah Anda yakin ingin membatalkan keikutsertaan dalam kegiatan ini?"
                              data-confirm-danger
                              data-confirm-title="Batalkan Pendaftaran Kegiatan?"
                              data-confirm-yes="Ya, Batalkan"
                              data-confirm-no="Kembali">
                            <?= csrfField() ?>
                            <button type="submit" class="btn btn-danger btn-sm w-full py-2.5 text-xs">
                                Batalkan Pendaftaran
                            </button>
                        </form>
                    <?php else: ?>
                        <?php if (!empty($activity['max_participants']) && count($participants) >= $activity['max_participants']): ?>
                            <div class="p-3 bg-red-50 text-red-800 text-xs font-bold rounded-xl text-center">
                                Kuota Peserta Penuh
                            </div>
                        <?php else: ?>
                            <form action="<?= url('activities/' . $activity['id'] . '/join') ?>" method="POST">
                                <?= csrfField() ?>
                                <button type="submit" class="btn btn-primary w-full py-3 font-bold shadow-md text-sm">
                                    Daftar Jadi Peserta
                                </button>
                            </form>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php else: ?>
                    <button type="button" onclick="openGuestAuthModal('kegiatan')" class="btn btn-primary w-full py-3 font-bold shadow-md text-sm">
                        Daftar Jadi Peserta
                    </button>
                    <p class="text-[11px] text-gray-400 text-center">Login diperlukan untuk mendaftar.</p>
                <?php endif; ?>

                <div class="pt-4 border-t border-gray-100 text-xs text-gray-500 space-y-1">
                    <p>Penyelenggara: <strong><?= e($activity['organizer_name']) ?></strong></p>
                    <p>Wilayah: <strong><?= e($activity['region_name']) ?></strong></p>
                </div>
            </div>
        </div>

    </div>

</div>
