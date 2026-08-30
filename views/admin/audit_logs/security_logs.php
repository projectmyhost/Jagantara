<div class="space-y-6 max-w-7xl pb-12">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-5 sm:p-6 rounded-2xl border border-gray-200 shadow-sm">
        <div class="flex items-start gap-4">
            <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm bg-rose-50 text-rose-600 border border-rose-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <div>
                <h1 class="text-base sm:text-lg font-extrabold text-gray-900 tracking-tight">Security Events & Incident Logs</h1>
                <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">Pencatatan insiden keamanan real-time, percobaan brute-force, deteksi IDOR, validasi CSRF, dan pelanggaran rate limit.</p>
            </div>
        </div>

        <div class="flex-shrink-0 flex items-center gap-2">
            <a href="<?= url('admin/audit-logs') ?>" class="btn btn-outline py-2.5 px-4 text-xs font-bold shadow-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Audit Logs Sistem
            </a>
        </div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between gap-3">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-10 h-10 rounded-xl bg-gray-100 text-gray-700 flex items-center justify-center flex-shrink-0 font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="text-[10px] font-semibold uppercase text-gray-400 tracking-wider">Total Insiden</div>
                    <div class="text-lg font-extrabold text-gray-900 leading-tight"><?= number_format($stats['total'] ?? 0) ?></div>
                </div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-rose-100 shadow-sm flex items-center justify-between gap-3">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center flex-shrink-0 font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="text-[10px] font-semibold uppercase text-rose-400 tracking-wider">Brute Force / Login</div>
                    <div class="text-lg font-extrabold text-rose-600 leading-tight"><?= number_format($stats['brute'] ?? 0) ?></div>
                </div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-amber-100 shadow-sm flex items-center justify-between gap-3">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0 font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="text-[10px] font-semibold uppercase text-amber-500 tracking-wider">Deteksi IDOR</div>
                    <div class="text-lg font-extrabold text-amber-700 leading-tight"><?= number_format($stats['idor'] ?? 0) ?></div>
                </div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-purple-100 shadow-sm flex items-center justify-between gap-3">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center flex-shrink-0 font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="text-[10px] font-semibold uppercase text-purple-400 tracking-wider">Rate Limit / CSRF</div>
                    <div class="text-lg font-extrabold text-purple-700 leading-tight"><?= number_format($stats['other'] ?? 0) ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-200 shadow-sm">
        <form method="GET" action="<?= url('admin/security-logs') ?>" class="flex flex-col sm:flex-row items-center gap-3">
            <div class="relative flex-1 w-full">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="q" value="<?= e($search ?? '') ?>" placeholder="Cari IP Address, email, path, atau keterangan insiden..." class="form-control text-xs pl-9 w-full">
            </div>

            <div class="w-full sm:w-auto">
                <select name="type" class="form-control text-xs font-semibold w-full sm:w-56" onchange="this.form.submit()">
                    <option value="">-- Semua Jenis Insiden --</option>
                    <option value="brute_force_detected" <?= ($type ?? '') === 'brute_force_detected' ? 'selected' : '' ?>>🚨 Brute Force Detected</option>
                    <option value="failed_login_attempt" <?= ($type ?? '') === 'failed_login_attempt' ? 'selected' : '' ?>>⚠️ Percobaan Login Gagal</option>
                    <option value="idor_attempt" <?= ($type ?? '') === 'idor_attempt' ? 'selected' : '' ?>>🛡️ Percobaan IDOR (Akses Ilegal)</option>
                    <option value="invalid_csrf" <?= ($type ?? '') === 'invalid_csrf' ? 'selected' : '' ?>>⛔ Token CSRF Tidak Valid</option>
                    <option value="unauthorized_access" <?= ($type ?? '') === 'unauthorized_access' ? 'selected' : '' ?>>🔒 Akses Terlarang (403 Forbidden)</option>
                    <option value="rate_limit_exceeded" <?= ($type ?? '') === 'rate_limit_exceeded' ? 'selected' : '' ?>>⏳ Pelanggaran Rate Limit</option>
                    <option value="suspicious_registration" <?= ($type ?? '') === 'suspicious_registration' ? 'selected' : '' ?>>👥 Registrasi Mencurigakan</option>
                    <option value="suspicious_upload" <?= ($type ?? '') === 'suspicious_upload' ? 'selected' : '' ?>>📁 Upload File Berbahaya</option>
                </select>
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button type="submit" class="btn btn-primary py-2 px-4 text-xs font-bold w-full sm:w-auto">
                    Filter
                </button>
                <?php if (!empty($search) || !empty($type)): ?>
                    <a href="<?= url('admin/security-logs') ?>" class="btn btn-outline py-2 px-3 text-xs font-semibold whitespace-nowrap">
                        Reset
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <?php if (!empty($logs)): ?>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="data-table w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/80 border-b border-gray-200">
                            <th class="py-3 px-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider w-16 text-center">ID</th>
                            <th class="py-3 px-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider whitespace-nowrap">Waktu Kejadian</th>
                            <th class="py-3 px-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Tipe Insiden</th>
                            <th class="py-3 px-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider">IP & User Terkait</th>
                            <th class="py-3 px-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Rincian / Context Insiden</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach ($logs as $l): ?>
                            <?php
                                $actionName = $l['action'] ?? '';
                                $badgeStyle = match($actionName) {
                                    'brute_force_detected' => 'bg-red-100 text-red-800 border-red-200',
                                    'failed_login_attempt' => 'bg-amber-100 text-amber-800 border-amber-200',
                                    'idor_attempt'         => 'bg-rose-100 text-rose-900 border-rose-300 font-extrabold',
                                    'invalid_csrf'         => 'bg-orange-100 text-orange-800 border-orange-200',
                                    'unauthorized_access'  => 'bg-amber-50 text-amber-800 border-amber-200',
                                    'rate_limit_exceeded'  => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                    'suspicious_upload'    => 'bg-red-50 text-red-700 border-red-200',
                                    'suspicious_registration' => 'bg-purple-100 text-purple-800 border-purple-200',
                                    default                => 'bg-gray-100 text-gray-800 border-gray-200',
                                };

                                $actionLabel = match($actionName) {
                                    'brute_force_detected' => 'Brute Force',
                                    'failed_login_attempt' => 'Login Gagal',
                                    'idor_attempt'         => 'IDOR Attack',
                                    'invalid_csrf'         => 'Invalid CSRF',
                                    'unauthorized_access'  => '403 Forbidden',
                                    'rate_limit_exceeded'  => 'Rate Limit',
                                    'suspicious_upload'    => 'Malicious File',
                                    'suspicious_registration' => 'Suspicious Reg',
                                    default                => str_replace('_', ' ', $actionName),
                                };

                                $detailsJson = json_decode($l['details'] ?? '', true);
                            ?>
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-3 px-4 font-mono font-bold text-gray-400 text-xs text-center">
                                    #<?= e($l['id']) ?>
                                </td>
                                <td class="py-3 px-4 text-xs text-gray-500 whitespace-nowrap">
                                    <div class="font-semibold text-gray-800"><?= formatDate($l['created_at'], 'd M Y') ?></div>
                                    <div class="text-[11px] text-gray-400 font-mono"><?= formatDate($l['created_at'], 'H:i:s') ?> WIB</div>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-bold border uppercase tracking-wider <?= $badgeStyle ?>">
                                        <?= e($actionLabel) ?>
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-mono text-xs font-bold text-gray-900"><?= e($l['ip_address'] ?? '127.0.0.1') ?></div>
                                    <div class="text-xs text-gray-500 mt-0.5">
                                        <?php if (!empty($l['email'])): ?>
                                            <span class="font-medium text-emerald-700"><?= e($l['email']) ?></span>
                                            <?php if (!empty($l['username'])): ?>
                                                <span class="text-gray-400">(<?= e($l['username']) ?>)</span>
                                            <?php endif; ?>
                                        <?php elseif (is_array($detailsJson) && !empty($detailsJson['email'])): ?>
                                            <span class="font-medium text-gray-700"><?= e($detailsJson['email']) ?></span>
                                        <?php else: ?>
                                            <span class="text-gray-400 italic">Guest / Anonim</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-xs text-gray-700">
                                    <?php if (is_array($detailsJson)): ?>
                                        <div class="space-y-1">
                                            <?php if (!empty($detailsJson['reason'])): ?>
                                                <div class="font-semibold text-rose-700 flex items-center gap-1.5">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                    <?= e($detailsJson['reason']) ?>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (!empty($detailsJson['resource'])): ?>
                                                <div class="text-gray-600 font-mono text-[11px]">
                                                    Resource: <strong class="text-gray-900"><?= e($detailsJson['resource']) ?></strong> (Target ID: <?= e($detailsJson['target_id'] ?? '-') ?>)
                                                </div>
                                            <?php endif; ?>
                                            <?php if (!empty($detailsJson['path'])): ?>
                                                <div class="text-gray-500 font-mono text-[11px]">
                                                    Path: <?= e($detailsJson['path']) ?>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (isset($detailsJson['remaining_attempts'])): ?>
                                                <div class="text-amber-700 text-[11px] font-semibold">
                                                    Sisa percobaan: <?= (int)$detailsJson['remaining_attempts'] ?> kali
                                                </div>
                                            <?php endif; ?>
                                            <?php if (!empty($detailsJson['user_agent'])): ?>
                                                <div class="text-[10px] text-gray-400 font-mono max-w-sm truncate" title="<?= e($detailsJson['user_agent']) ?>">
                                                    UA: <?= e($detailsJson['user_agent']) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="font-mono text-xs text-gray-600">
                                            <?= e($l['details'] ?? '-') ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if (($pagination['total_pages'] ?? 1) > 1): ?>
                <div class="p-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3 bg-gray-50/50">
                    <span class="text-xs text-gray-500">
                        Menampilkan <?= count($logs) ?> dari total <?= number_format($pagination['total']) ?> insiden
                    </span>

                    <div class="flex items-center gap-2">
                        <?php if ($pagination['has_prev']): ?>
                            <a href="<?= url('admin/security-logs?page=' . ($pagination['current_page'] - 1) . (!empty($type) ? '&type=' . urlencode($type) : '') . (!empty($search) ? '&q=' . urlencode($search) : '')) ?>" class="btn btn-outline py-1.5 px-3 text-xs">
                                Sebelumnya
                            </a>
                        <?php endif; ?>

                        <span class="text-xs font-bold text-gray-700 px-2">
                            Hal. <?= $pagination['current_page'] ?> / <?= $pagination['total_pages'] ?>
                        </span>

                        <?php if ($pagination['has_next']): ?>
                            <a href="<?= url('admin/security-logs?page=' . ($pagination['current_page'] + 1) . (!empty($type) ? '&type=' . urlencode($type) : '') . (!empty($search) ? '&q=' . urlencode($search) : '')) ?>" class="btn btn-outline py-1.5 px-3 text-xs">
                                Selanjutnya
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

    <?php else: ?>
        <div class="empty-state bg-white rounded-2xl border border-gray-200 p-12 text-center shadow-sm">
            <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 mx-auto flex items-center justify-center mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <h3 class="text-base font-bold text-gray-800 mb-1">Tidak Ada Insiden Keamanan Tercatat</h3>
            <p class="text-xs text-gray-500 max-w-md mx-auto">
                <?= !empty($search) || !empty($type) ? 'Tidak ditemukan log yang sesuai dengan filter pencarian.' : 'Seluruh sistem Jagantara berjalan normal, terlindungi, dan tidak ada ancaman keamanan saat ini.' ?>
            </p>
            <?php if (!empty($search) || !empty($type)): ?>
                <a href="<?= url('admin/security-logs') ?>" class="btn btn-outline btn-sm mt-4 inline-flex items-center gap-1">
                    Hapus Filter
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

</div>
