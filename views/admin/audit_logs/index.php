<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-base font-bold text-gray-900">Audit Logs Aktivitas Sistem</h2>
            <p class="text-xs text-gray-500">Catatan kronologis seluruh aktivitas penting pengguna dan administrator (Read-Only).</p>
        </div>
        <a href="<?= url('admin/security-logs') ?>" class="btn btn-outline btn-sm">
            Lihat Security Logs
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 p-4 shadow-sm">
        <form method="GET" action="<?= url('admin/audit-logs') ?>" class="flex items-center gap-3">
            <input type="text" name="action" value="<?= e($action ?? '') ?>" placeholder="Cari nama aksi (misal: login, create_report)..." class="form-control text-xs flex-1 max-w-sm">
            <button type="submit" class="btn btn-primary btn-sm h-[38px] px-4 font-semibold">
                Cari Log
            </button>
        </form>
    </div>

    <?php if (!empty($logs)): ?>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Waktu</th>
                        <th>Aksi (Action)</th>
                        <th>Pengguna</th>
                        <th>Entitas</th>
                        <th>IP Address</th>
                        <th>Detail Aman</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $l): ?>
                        <tr>
                            <td class="font-bold text-gray-500 text-xs w-12 text-center">
                            <td class="text-xs text-gray-500 whitespace-nowrap">
                                <?= formatDate($l['created_at'], 'd M Y H:i:s') ?>
                            </td>
                            <td>
                                <span class="badge badge-verified uppercase text-[10px] font-bold">
                                    <?= e($l['action']) ?>
                                </span>
                            </td>
                            <td class="text-xs text-gray-700">
                                <?php if (!empty($l['email'])): ?>
                                    <div class="font-bold text-gray-900"><?= e($l['full_name'] ?? $l['username']) ?></div>
                                    <div class="text-[11px] text-gray-400 font-mono"><?= e($l['email']) ?></div>
                                <?php else: ?>
                                    <span class="text-gray-400 italic">Tamu / Sistem</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-xs text-gray-600">
                                <?= e($l['entity_type'] ?? '-') ?> <?= !empty($l['entity_id']) ? '#' . (int)$l['entity_id'] : '' ?>
                            </td>
                            <td class="text-xs font-mono text-gray-500"><?= e($l['ip_address'] ?? '-') ?></td>
                            <td class="text-xs text-gray-600 max-w-xs truncate font-mono">
                                <?= e($l['details'] ?? '-') ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if (($pagination['total_pages'] ?? 1) > 1): ?>
            <div class="flex items-center justify-center gap-2 pt-6">
                <?php if ($pagination['has_prev']): ?>
                    <a href="<?= url('admin/audit-logs?page=' . ($pagination['current_page'] - 1) . '&action=' . urlencode($action ?? '')) ?>" class="btn btn-outline btn-sm">
                        Sebelumnya
                    </a>
                <?php endif; ?>
                <span class="text-xs text-gray-600 font-medium px-2">
                    Halaman <?= $pagination['current_page'] ?> dari <?= $pagination['total_pages'] ?>
                </span>
                <?php if ($pagination['has_next']): ?>
                    <a href="<?= url('admin/audit-logs?page=' . ($pagination['current_page'] + 1) . '&action=' . urlencode($action ?? '')) ?>" class="btn btn-outline btn-sm">
                        Selanjutnya
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <div class="empty-state bg-white rounded-2xl border border-gray-200 p-12">
            <h3 class="text-base font-bold text-gray-800 mb-1">Tidak Ada Log Aktivitas</h3>
            <p class="text-xs text-gray-500">Belum ada catatan aktivitas yang sesuai dengan filter pencarian.</p>
        </div>
    <?php endif; ?>

</div>
