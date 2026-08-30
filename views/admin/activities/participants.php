<div class="space-y-6 max-w-4xl">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-base font-bold text-gray-900">Peserta Kegiatan: <?= e($activity['title']) ?></h2>
            <p class="text-xs text-gray-500">Jadwal: <?= formatDate($activity['scheduled_at'] ?? date('Y-m-d'), 'd M Y H:i') ?> &bull; Lokasi: <?= e($activity['location_name'] ?? '-') ?></p>
        </div>
        <a href="<?= url('admin/activities') ?>" class="btn btn-outline btn-sm">
            &larr; Kembali ke Kegiatan
        </a>
    </div>

    <?php if (!empty($participants)): ?>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Peserta</th>
                        <th>Email</th>
                        <th>No. Handphone</th>
                        <th>Tanggal Mendaftar</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($participants as $idx => $p): ?>
                        <tr>
                            <td class="font-bold text-gray-500 text-xs w-10 text-center"><?= $idx + 1 ?></td>
                            <td class="font-bold text-gray-900 text-xs"><?= e($p['full_name'] ?? 'Warga') ?></td>
                            <td class="text-xs text-gray-600 font-mono"><?= e($p['email'] ?? '-') ?></td>
                            <td class="text-xs text-gray-600"><?= e($p['phone'] ?? '-') ?></td>
                            <td class="text-xs text-gray-500 whitespace-nowrap"><?= formatDate($p['joined_at'], 'd M Y H:i') ?></td>
                            <td>
                                <span class="badge badge-completed">Terdaftar</span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-state bg-white rounded-2xl border border-gray-200 p-12">
            <h3 class="text-base font-bold text-gray-800 mb-1">Belum Ada Peserta</h3>
            <p class="text-xs text-gray-500">Belum ada anggota atau warga yang mendaftar pada kegiatan ini.</p>
        </div>
    <?php endif; ?>

</div>
