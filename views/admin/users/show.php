<div class="space-y-6 max-w-4xl">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Detail Pengguna 
            <p class="text-xs text-gray-500">Informasi profil lengkap, peran akses, dan riwayat laporan.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="<?= url('admin/users/' . $user['id'] . '/edit') ?>" class="btn btn-primary btn-sm">Edit Pengguna</a>
            <a href="<?= url('admin/users') ?>" class="btn btn-outline btn-sm">Kembali</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm text-center">
            <div class="w-20 h-20 mx-auto rounded-full bg-[#1D4533] text-white flex items-center justify-center text-2xl font-bold mb-3">
                <?= strtoupper(substr($user['username'] ?? $user['email'], 0, 1)) ?>
            </div>
            <h3 class="font-bold text-gray-900 text-base"><?= e($user['full_name'] ?? $user['username']) ?></h3>
            <p class="text-xs text-gray-500 mb-3">@<?= e($user['username'] ?? '-') ?></p>

            <div class="flex items-center justify-center gap-2 mb-4">
                <span class="badge badge-verified capitalize"><?= e(str_replace('_', ' ', $user['role'])) ?></span>
                <span class="badge <?= $user['status'] === 'active' ? 'badge-completed' : 'badge-rejected' ?>"><?= e($user['status']) ?></span>
            </div>

            <div class="pt-4 border-t border-gray-100 text-xs text-left space-y-2">
                <div><span class="text-gray-400">Email:</span> <span class="font-medium text-gray-800"><?= e($user['email']) ?></span></div>
                <div><span class="text-gray-400">Telepon:</span> <span class="font-medium text-gray-800"><?= e($user['phone'] ?? '-') ?></span></div>
                <div><span class="text-gray-400">Wilayah:</span> <span class="font-medium text-gray-800"><?= e($user['region_name'] ?? '-') ?></span></div>
                <div><span class="text-gray-400">Terdaftar:</span> <span class="font-medium text-gray-800"><?= formatDate($user['created_at'], 'd M Y') ?></span></div>
            </div>
        </div>

        <div class="md:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <h3 class="text-base font-bold text-gray-900 mb-4">Laporan oleh Pengguna Ini (<?= count($reports) ?>)</h3>
                <?php if (!empty($reports)): ?>
                    <div class="space-y-2 max-h-60 overflow-y-auto pr-1">
                        <?php foreach ($reports as $r): ?>
                            <div class="p-3 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-between">
                                <div>
                                    <h4 class="font-bold text-xs text-gray-900">
                                        <a href="<?= url('admin/reports/' . $r['id']) ?>" class="hover:text-[#1D4533]">
                                            <?= e($r['title']) ?>
                                        </a>
                                    </h4>
                                    <span class="text-[11px] text-gray-500"><?= formatDate($r['created_at']) ?></span>
                                </div>
                                <span class="badge badge-<?= e($r['status']) ?>">
                                    <?= statusLabel($r['status']) ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-xs text-gray-500">Pengguna belum membuat laporan.</p>
                <?php endif; ?>
            </div>
        </div>

    </div>

</div>
