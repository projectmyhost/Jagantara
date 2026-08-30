<div class="container py-8">

    <div class="flex items-center gap-2 text-xs text-gray-500 mb-6">
        <a href="<?= url('') ?>" class="hover:text-[#1D4533]">Beranda</a>
        <span>/</span>
        <a href="<?= url('reports') ?>" class="hover:text-[#1D4533]">Laporan</a>
        <span>/</span>
        <span class="text-gray-800 font-medium truncate max-w-xs"><?= e($report['title']) ?></span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-2 space-y-6">

            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
                    <div class="flex items-center gap-2">
                        <span class="badge badge-<?= e($report['status']) ?>">
                            <?= statusLabel($report['status']) ?>
                        </span>
                        <span class="text-xs bg-[#F7EAE0] text-[#5E3122] px-2.5 py-1 rounded-full font-semibold">
                            <?= e($report['category_name'] ?? 'Kategori Umum') ?>
                        </span>
                    </div>
                    <span class="text-xs text-gray-500">
                        Dilaporkan: <?= formatDate($report['created_at'], 'd M Y H:i') ?>
                    </span>
                </div>

                <h1 class="text-xl sm:text-2xl font-extrabold text-gray-900 leading-snug">
                    <?= e($report['title']) ?>
                </h1>
            </div>

            <?php if (!empty($photos)): ?>
                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
                    <h3 class="text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#1D4533]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Foto Bukti Kondisi Lingkungan (<?= count($photos) ?> Foto)
                    </h3>

                    <div class="relative overflow-hidden rounded-xl bg-gray-100 mb-3">
                        <img id="gallery-main-img"
                             src="<?= uploadUrl($photos[0]['file_path']) ?>"
                             alt="<?= e($report['title']) ?>"
                             class="gallery-main"
                             onclick="openLightbox(this.src)">
                    </div>

                    <?php if (count($photos) > 1): ?>
                        <div class="gallery-thumbs">
                            <?php foreach ($photos as $idx => $p): ?>
                                <img src="<?= uploadUrl($p['file_path']) ?>"
                                     alt="Foto <?= $idx + 1 ?>"
                                     class="gallery-thumb <?= $idx === 0 ? 'active' : '' ?>"
                                     onclick="switchGalleryImage(this, '<?= uploadUrl($p['file_path']) ?>')">
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                <h2 class="text-base font-bold text-gray-900 mb-3">Deskripsi Permasalahan</h2>
                <div class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">
                    <?= e($report['description']) ?>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                <h2 class="text-base font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#1D4533]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Informasi Lokasi Permasalahan
                </h2>

                <div class="space-y-3 text-sm">
                    <div>
                        <span class="text-xs font-semibold text-gray-500 block">Wilayah / Daerah:</span>
                        <span class="font-medium text-gray-900"><?= e($report['region_name'] ?? 'JABODETABEK') ?></span>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-gray-500 block">Nama / Patokan Lokasi:</span>
                        <span class="font-medium text-gray-900"><?= e($report['location_name'] ?? '-') ?></span>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-gray-500 block">Alamat Lengkap:</span>
                        <span class="font-medium text-gray-900"><?= e($report['address'] ?? '-') ?></span>
                    </div>

                    <?php if (!empty($report['latitude']) && !empty($report['longitude'])): ?>
                        <div class="pt-3">
                            <span class="text-xs font-semibold text-gray-500 block mb-2">Peta Titik Permasalahan:</span>
                            <div id="detail-map" class="h-48 rounded-xl border border-gray-200 overflow-hidden"></div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>

        <div class="space-y-6">

            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                <h3 class="text-base font-bold text-gray-900 mb-1 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#1D4533]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Progres Penanganan
                </h3>
                <p class="text-[11px] text-gray-400 mb-5">Riwayat perubahan status laporan secara kronologis.</p>

                <?php if (!empty($statusHistory)): ?>
                    <?php
                        $total = count($statusHistory);
                        $statusColors = [
                            'pending'     => ['bg' => 'bg-amber-400',  'ring' => 'ring-amber-200',  'text' => 'text-amber-700',  'badge' => 'bg-amber-50 text-amber-700 border-amber-200'],
                            'verified'    => ['bg' => 'bg-blue-500',   'ring' => 'ring-blue-200',   'text' => 'text-blue-700',   'badge' => 'bg-blue-50 text-blue-700 border-blue-200'],
                            'in_progress' => ['bg' => 'bg-purple-500', 'ring' => 'ring-purple-200', 'text' => 'text-purple-700', 'badge' => 'bg-purple-50 text-purple-700 border-purple-200'],
                            'completed'   => ['bg' => 'bg-emerald-500','ring' => 'ring-emerald-200','text' => 'text-emerald-700','badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
                            'rejected'    => ['bg' => 'bg-red-500',    'ring' => 'ring-red-200',    'text' => 'text-red-700',    'badge' => 'bg-red-50 text-red-700 border-red-200'],
                        ];
                        $statusIcons = [
                            'pending'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                            'verified'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>',
                            'in_progress' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M13 10V3L4 14h7v7l9-11h-7z"/>',
                            'completed'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>',
                            'rejected'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12"/>',
                        ];
                    ?>
                    <div class="relative">
                        <?php foreach ($statusHistory as $idx => $h): ?>
                            <?php
                                $st = $h['status'];
                                $col = $statusColors[$st] ?? $statusColors['pending'];
                                $icon = $statusIcons[$st] ?? $statusIcons['pending'];
                                $isFirst = ($idx === 0);
                                $isLast = ($idx === $total - 1);
                                $isLatest = $isFirst;
                            ?>
                            <div class="relative flex gap-4 <?= !$isLast ? 'pb-6' : '' ?>">

                                <div class="flex flex-col items-center flex-shrink-0">
                                    <div class="w-9 h-9 rounded-full <?= $col['bg'] ?> ring-4 <?= $col['ring'] ?> flex items-center justify-center shadow-sm z-10 flex-shrink-0">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <?= $icon ?>
                                        </svg>
                                    </div>
                                    <?php if (!$isLast): ?>
                                        <div class="w-0.5 flex-1 mt-1 <?= $col['bg'] ?> opacity-30 min-h-[24px]"></div>
                                    <?php endif; ?>
                                </div>

                                <div class="flex-1 min-w-0 pt-1 <?= $isLatest ? 'pb-2' : '' ?>">
                                    <?php if ($isLatest): ?>
                                        <div class="rounded-xl border <?= str_replace('bg-', 'border-', explode(' ', $col['badge'])[2] ?? 'border-gray-200') ?> p-3 mb-1" style="background: linear-gradient(135deg, <?php
                                            $bgMap = ['pending'=>'#FFF9EB','verified'=>'#EFF6FF','in_progress'=>'#F5F3FF','completed'=>'#ECFDF5','rejected'=>'#FEF2F2'];
                                            echo $bgMap[$st] ?? '#F9FAFB';
                                        ?>, white);">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <span class="text-xs font-bold <?= $col['text'] ?>">
                                                    <?= statusLabel($h['status']) ?>
                                                </span>
                                                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full border <?= $col['badge'] ?>">Status Terkini</span>
                                            </div>
                                            <div class="text-[11px] text-gray-400 mt-0.5"><?= formatDate($h['created_at'], 'd M Y H:i') ?></div>
                                            <?php if (!empty($h['notes'])): ?>
                                                <div class="text-xs text-gray-600 mt-2 pt-2 border-t border-gray-100 leading-relaxed"><?= e($h['notes']) ?></div>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="mb-0.5">
                                            <span class="text-xs font-semibold text-gray-700"><?= statusLabel($h['status']) ?></span>
                                        </div>
                                        <div class="text-[11px] text-gray-400"><?= formatDate($h['created_at'], 'd M Y H:i') ?></div>
                                        <?php if (!empty($h['notes'])): ?>
                                            <div class="text-xs text-gray-500 bg-gray-50 border border-gray-100 p-2 rounded-lg mt-1.5 leading-relaxed"><?= e($h['notes']) ?></div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>

                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-xs text-gray-500">Belum ada riwayat perubahan status.</p>
                <?php endif; ?>
            </div>


            <div class="bg-gray-50 p-5 rounded-2xl border border-gray-200 text-xs text-gray-600 space-y-2">
                <div class="font-bold text-gray-800">Privasi & Keamanan Pelapor</div>
                <p>
                    Data pribadi sensitif pelapor (seperti nomor telepon, KTP, dan alamat rumah pelapor) dilindungi dan tidak ditampilkan kepada publik. Laporan ini dipublikasikan untuk transparansi penanganan lingkungan bersama komunitas.
                </p>
            </div>

            <div class="space-y-2">
                <a href="<?= url('reports') ?>" class="btn btn-outline w-full py-2.5 text-xs">
                    &larr; Kembali ke Daftar Laporan
                </a>
            </div>

        </div>

    </div>

</div>

<div id="lightbox" class="lightbox" onclick="closeLightbox()">
    <button type="button" class="lightbox-close" onclick="closeLightbox()">&times;</button>
    <img id="lightbox-img" src="" alt="Zoomed Photo" onclick="event.stopPropagation()">
</div>

<script>
function switchGalleryImage(thumb, src) {
    document.getElementById('gallery-main-img').src = src;
    document.querySelectorAll('.gallery-thumb').forEach(t => t.classList.remove('active'));
    thumb.classList.add('active');
}

function openLightbox(src) {
    const box = document.getElementById('lightbox');
    document.getElementById('lightbox-img').src = src;
    box.classList.add('show');
}

function closeLightbox() {
    document.getElementById('lightbox').classList.remove('show');
}

<?php if (!empty($report['latitude']) && !empty($report['longitude'])): ?>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof L !== 'undefined') {
        const lat = <?= (float)$report['latitude'] ?>;
        const lng = <?= (float)$report['longitude'] ?>;
        const map = L.map('detail-map', {
            worldCopyJump: false,
            maxBounds: [[-90, -180], [90, 180]],
            maxBoundsViscosity: 1.0,
            minZoom: 2
        }).setView([lat, lng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors',
            noWrap: true
        }).addTo(map);

        L.marker([lat, lng]).addTo(map)
            .bindPopup('<b><?= e(addslashes($report['location_name'] ?? 'Lokasi Masalah')) ?></b>')
            .openPopup();
    }
});
<?php endif; ?>
</script>
