<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-base font-bold text-gray-900">Daftar Pengguna Sistem</h2>
            <p class="text-xs text-gray-500">Kelola pengguna terdaftar, peran akses, dan status akun.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
        <form method="GET" action="<?= url('admin/users') ?>" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="sm:col-span-2">
                <input type="text" name="q" value="<?= e($search ?? '') ?>" placeholder="Cari berdasarkan email, nama lengkap, atau username..." class="form-control text-xs">
            </div>
            <div class="flex items-center gap-2">
                <select name="role" class="form-control text-xs">
                    <option value="">Semua Peran (Role)</option>
                    <option value="user" <?= ($role ?? '') === 'user' ? 'selected' : '' ?>>Masyarakat (User)</option>
                    <option value="organizer_member" <?= ($role ?? '') === 'organizer_member' ? 'selected' : '' ?>>Anggota Organizer</option>
                    <option value="admin" <?= ($role ?? '') === 'admin' ? 'selected' : '' ?>>Administrator</option>
                </select>
                <button type="submit" class="btn btn-primary btn-sm h-[38px] px-4 font-semibold">
                    Cari
                </button>
            </div>
        </form>
    </div>

    <?php if (!empty($users)): ?>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pengguna</th>
                        <th>Email</th>
                        <th>Wilayah</th>
                        <th>Peran (Role)</th>
                        <th>Status</th>
                        <th>Terdaftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td class="font-bold text-gray-500 text-xs">
                            <td>
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-[#1D4533] text-white flex items-center justify-center text-xs font-bold flex-shrink-0">
                                        <?= strtoupper(substr($u['username'] ?? $u['email'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900 text-xs"><?= e($u['full_name'] ?? $u['username']) ?></div>
                                        <div class="text-[11px] text-gray-400">@<?= e($u['username'] ?? '-') ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-xs text-gray-700 font-mono"><?= e($u['email']) ?></td>
                            <td class="text-xs text-gray-500"><?= e($u['region_name'] ?? '-') ?></td>
                            <td>
                                <span class="badge <?= $u['role'] === 'admin' ? 'badge-rejected' : ($u['role'] === 'organizer_member' ? 'badge-completed' : 'badge-verified') ?>">
                                    <?= e(str_replace('_', ' ', $u['role'])) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge <?= $u['status'] === 'active' ? 'badge-completed' : 'badge-rejected' ?>">
                                    <?= e($u['status']) ?>
                                </span>
                            </td>
                            <td class="text-xs text-gray-500 whitespace-nowrap">
                                <?= formatDate($u['created_at'], 'd M Y') ?>
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <a href="<?= url('admin/users/' . $u['id']) ?>" class="btn btn-outline btn-sm text-xs py-1 px-2">
                                        Detail
                                    </a>
                                    <a href="<?= url('admin/users/' . $u['id'] . '/edit') ?>" class="btn btn-primary btn-sm text-xs py-1 px-2">
                                        Edit
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if (($pagination['total_pages'] ?? 1) > 1): ?>
            <div class="flex items-center justify-center gap-2 pt-6">
                <?php if ($pagination['has_prev']): ?>
                    <a href="<?= url('admin/users?page=' . ($pagination['current_page'] - 1) . '&q=' . urlencode($search ?? '') . '&role=' . ($role ?? '')) ?>" class="btn btn-outline btn-sm">
                        Sebelumnya
                    </a>
                <?php endif; ?>
                <span class="text-xs text-gray-600 font-medium px-2">
                    Halaman <?= $pagination['current_page'] ?> dari <?= $pagination['total_pages'] ?>
                </span>
                <?php if ($pagination['has_next']): ?>
                    <a href="<?= url('admin/users?page=' . ($pagination['current_page'] + 1) . '&q=' . urlencode($search ?? '') . '&role=' . ($role ?? '')) ?>" class="btn btn-outline btn-sm">
                        Selanjutnya
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <div class="empty-state bg-white rounded-2xl border border-gray-200 p-12">
            <h3 class="text-base font-bold text-gray-800 mb-1">Pengguna Tidak Ditemukan</h3>
            <p class="text-xs text-gray-500">Tidak ada data pengguna yang cocok dengan kriteria pencarian.</p>
        </div>
    <?php endif; ?>

</div>
