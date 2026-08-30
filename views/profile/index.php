<div class="page-header">
    <div class="container">
        <h1>Profil & Pengaturan Akun</h1>
        <p>Kelola data informasi akun dan kelengkapan profil Anda di Jagantara.</p>
    </div>
</div>

<div class="container py-8">
    <div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">

        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-gray-200 p-6 text-center shadow-sm">
                <div class="w-24 h-24 mx-auto rounded-full bg-[#1D4533] text-white flex items-center justify-center text-3xl font-bold mb-4 overflow-hidden border-4 border-white shadow-md">
                    <?php if (!empty($user['avatar_path']) && file_exists(UPLOAD_PATH . '/' . $user['avatar_path'])): ?>
                        <img src="<?= uploadUrl($user['avatar_path']) ?>" class="w-full h-full object-cover" alt="<?= e($user['username'] ?? '') ?>">
                    <?php else: ?>
                        <?= strtoupper(substr($user['username'] ?? 'U', 0, 1)) ?>
                    <?php endif; ?>
                </div>

                <h2 class="text-lg font-extrabold text-gray-900"><?= e($user['full_name'] ?? $user['username']) ?></h2>
                <p class="text-xs text-gray-500 mb-3">@<?= e($user['username'] ?? 'user') ?></p>

                <div class="inline-block px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-semibold rounded-full capitalize mb-4">
                    <?= e(str_replace('_', ' ', $user['role'])) ?>
                </div>

                <div class="pt-4 border-t border-gray-100 text-left">
                    <div class="flex items-center justify-between text-xs mb-1.5">
                        <span class="font-bold text-gray-700">Status Profil</span>
                        <?php if (!empty($user['is_complete'])): ?>
                            <span class="text-emerald-700 font-bold">Lengkap</span>
                        <?php else: ?>
                            <span class="text-amber-700 font-bold">Belum Lengkap</span>
                        <?php endif; ?>
                    </div>
                    <?php if (empty($user['is_complete'])): ?>
                        <p class="text-[11px] text-gray-500 mb-3">Lengkapi profil untuk mempermudah pendaftaran anggota organizer.</p>
                    <?php endif; ?>
                </div>

                <a href="<?= url('profile/edit') ?>" class="btn btn-primary w-full py-2.5 text-xs font-bold mt-2">
                    Edit Profil & Pengaturan
                </a>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 p-4 shadow-sm space-y-1">
                <a href="<?= url('reports/status') ?>" class="flex items-center justify-between p-2.5 rounded-xl hover:bg-gray-50 text-xs font-semibold text-gray-700 transition">
                    <span>Laporan Saya (<?= $reportsCount ?? 0 ?>)</span>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                <a href="<?= url('organizers/my-status') ?>" class="flex items-center justify-between p-2.5 rounded-xl hover:bg-gray-50 text-xs font-semibold text-gray-700 transition">
                    <span>Status Organizer</span>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                <a href="<?= url('auth/logout') ?>" class="flex items-center justify-between p-2.5 rounded-xl hover:bg-red-50 text-xs font-semibold text-red-600 transition">
                    <span>Keluar Akun</span>
                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </a>
            </div>
        </div>

        <div class="md:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-gray-200 p-6 sm:p-8 shadow-sm">
                <h3 class="text-base font-bold text-gray-900 mb-6 pb-3 border-b border-gray-100">
                    Informasi Akun & Pribadi
                </h3>

                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-6 text-sm">
                    <div>
                        <dt class="text-xs font-semibold text-gray-500 mb-0.5">Nama Lengkap</dt>
                        <dd class="font-medium text-gray-900"><?= e($user['full_name'] ?? '-') ?></dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold text-gray-500 mb-0.5">Username</dt>
                        <dd class="font-medium text-gray-900">@<?= e($user['username'] ?? '-') ?></dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold text-gray-500 mb-0.5">Alamat Email</dt>
                        <dd class="font-medium text-gray-900"><?= e($user['email']) ?></dd>
                        <span class="text-[10px] text-gray-400">Email bersifat tetap dan tidak dapat diubah di sini.</span>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold text-gray-500 mb-0.5">Nomor Handphone</dt>
                        <dd class="font-medium text-gray-900"><?= e($user['phone'] ?? '-') ?></dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold text-gray-500 mb-0.5">Wilayah Tempat Tinggal</dt>
                        <dd class="font-medium text-gray-900"><?= e($user['region_name'] ?? 'Belum diatur') ?></dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold text-gray-500 mb-0.5">Tanggal Lahir</dt>
                        <dd class="font-medium text-gray-900"><?= !empty($user['birth_date']) ? formatDate($user['birth_date']) : '-' ?></dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold text-gray-500 mb-0.5">Jenis Kelamin</dt>
                        <dd class="font-medium text-gray-900">
                            <?= match($user['gender'] ?? '') {
                                'male' => 'Laki-laki',
                                'female' => 'Perempuan',
                                default => '-'
                            } ?>
                        </dd>
                    </div>

                    <div class="sm:col-span-2">
                        <dt class="text-xs font-semibold text-gray-500 mb-0.5">Alamat Lengkap</dt>
                        <dd class="font-medium text-gray-900"><?= e($user['address'] ?? '-') ?></dd>
                    </div>
                </dl>
            </div>
        </div>

    </div>
</div>
