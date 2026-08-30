<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-gray-200/80">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                    Modul Geospasial
                </span>
                <span class="text-xs text-gray-400">•</span>
                <span class="text-xs text-gray-500 font-medium">Total <?= count($locations) ?> Lokasi Terdata</span>
            </div>
            <h2 class="text-xl font-bold text-gray-900 tracking-tight">Manajemen Peta & Lokasi Pembersihan</h2>
            <p class="text-xs text-gray-500 mt-0.5">Kelola titik sebaran lokasi aksi lingkungan dan status penanganan sampah di wilayah JABODETABEK</p>
        </div>
        <div class="flex items-center gap-2.5 flex-shrink-0">
            <a href="<?= url('admin/map-management/create') ?>" class="btn btn-primary btn-sm py-2 px-4 text-xs font-semibold inline-flex items-center gap-2 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Titik Lokasi
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        <div class="bg-white p-5 rounded-2xl border border-gray-200/90 shadow-xs hover:shadow-sm transition-all duration-200 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-200/80 flex items-center justify-center text-amber-600 flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Menunggu</div>
                <div class="text-2xl font-extrabold text-gray-900 mt-0.5"><?= number_format($statistics['pending'] ?? 0) ?></div>
                <div class="text-[11px] text-amber-600 font-medium mt-0.5 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                    Perlu Verifikasi
                </div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-200/90 shadow-xs hover:shadow-sm transition-all duration-200 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 border border-blue-200/80 flex items-center justify-center text-blue-600 flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Terverifikasi</div>
                <div class="text-2xl font-extrabold text-gray-900 mt-0.5"><?= number_format($statistics['verified'] ?? 0) ?></div>
                <div class="text-[11px] text-blue-600 font-medium mt-0.5 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                    Siap Ditangani
                </div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-200/90 shadow-xs hover:shadow-sm transition-all duration-200 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-orange-50 border border-orange-200/80 flex items-center justify-center text-orange-600 flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Ditangani</div>
                <div class="text-2xl font-extrabold text-gray-900 mt-0.5"><?= number_format($statistics['in_progress'] ?? 0) ?></div>
                <div class="text-[11px] text-orange-600 font-medium mt-0.5 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                    Dalam Pengerjaan
                </div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-200/90 shadow-xs hover:shadow-sm transition-all duration-200 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-200/80 flex items-center justify-center text-emerald-600 flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Selesai</div>
                <div class="text-2xl font-extrabold text-gray-900 mt-0.5"><?= number_format($statistics['completed'] ?? 0) ?></div>
                <div class="text-[11px] text-emerald-600 font-medium mt-0.5 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Area Bersih
                </div>
            </div>
        </div>

    </div>

    <div class="bg-white rounded-2xl border border-gray-200/90 shadow-sm overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-gray-50/40">
            <div>
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                    </svg>
                    Peta Interaktif Sebaran Lokasi
                </h3>
                <p class="text-xs text-gray-500 mt-0.5">Visualisasi geospasial titik penanganan di seluruh wilayah JABODETABEK</p>
            </div>

            <div class="flex items-center gap-2">
                <button type="button" onclick="resetMapView()" class="btn btn-outline btn-sm py-1.5 px-3 text-xs inline-flex items-center gap-1.5 bg-white">
                    <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Pusatkan Peta
                </button>
            </div>
        </div>

        <div class="p-4 sm:p-5">
            <div id="admin-map" style="height: 440px; width: 100%; border-radius: 12px;" class="border border-gray-200/80 shadow-inner z-0"></div>

            <div class="mt-4 pt-3 border-t border-gray-100 grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                <div class="flex items-center gap-2.5 p-2 rounded-xl bg-amber-50/60 border border-amber-200/60">
                    <span class="w-3 h-3 rounded-full flex-shrink-0 bg-[#FFC107] ring-2 ring-amber-300/50"></span>
                    <div class="min-w-0">
                        <span class="font-bold text-gray-800 block leading-tight">Menunggu</span>
                        <span class="text-[10px] text-gray-500">Perlu Verifikasi</span>
                    </div>
                </div>

                <div class="flex items-center gap-2.5 p-2 rounded-xl bg-blue-50/60 border border-blue-200/60">
                    <span class="w-3 h-3 rounded-full flex-shrink-0 bg-[#2196F3] ring-2 ring-blue-300/50"></span>
                    <div class="min-w-0">
                        <span class="font-bold text-gray-800 block leading-tight">Terverifikasi</span>
                        <span class="text-[10px] text-gray-500">Siap Ditangani</span>
                    </div>
                </div>

                <div class="flex items-center gap-2.5 p-2 rounded-xl bg-orange-50/60 border border-orange-200/60">
                    <span class="w-3 h-3 rounded-full flex-shrink-0 bg-[#FF9800] ring-2 ring-orange-300/50"></span>
                    <div class="min-w-0">
                        <span class="font-bold text-gray-800 block leading-tight">Ditangani</span>
                        <span class="text-[10px] text-gray-500">Sedang Berlangsung</span>
                    </div>
                </div>

                <div class="flex items-center gap-2.5 p-2 rounded-xl bg-emerald-50/60 border border-emerald-200/60">
                    <span class="w-3 h-3 rounded-full flex-shrink-0 bg-[#4CAF50] ring-2 ring-emerald-300/50"></span>
                    <div class="min-w-0">
                        <span class="font-bold text-gray-800 block leading-tight">Selesai</span>
                        <span class="text-[10px] text-gray-500">Pembersihan Tuntas</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200/90 p-4 sm:p-5 shadow-sm">
        <form method="GET" action="<?= url('admin/map-management') ?>" class="flex flex-col md:flex-row items-stretch md:items-center gap-3">

            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" name="search" value="<?= e($filters['search']) ?>"
                       placeholder="Cari berdasarkan nama jalan, kota, atau kata kunci alamat..."
                       class="form-control text-xs pl-10 h-10 w-full bg-gray-50/50 border-gray-200 focus:bg-white transition">
            </div>

            <div class="w-full md:w-56">
                <select name="status" class="form-control text-xs h-10 w-full bg-gray-50/50 border-gray-200 focus:bg-white transition">
                    <option value="">Semua Status Penanganan</option>
                    <?php foreach ($statusOptions as $value => $label): ?>
                        <option value="<?= $value ?>" <?= ($filters['status'] === $value) ? 'selected' : '' ?>>
                            Status: <?= $label ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="btn btn-primary btn-sm h-10 px-4 text-xs font-semibold inline-flex items-center justify-center gap-1.5 shadow-sm flex-1 md:flex-none">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Terapkan
                </button>

                <?php if (!empty($filters['search']) || !empty($filters['status'])): ?>
                    <a href="<?= url('admin/map-management') ?>" class="btn btn-outline btn-sm h-10 px-3 text-xs inline-flex items-center justify-center gap-1 text-gray-500 hover:text-gray-700 bg-white" title="Reset Filter">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Reset
                    </a>
                <?php endif; ?>
            </div>

        </form>
    </div>

    <?php if (!empty($locations)): ?>
        <div class="bg-white rounded-2xl border border-gray-200/90 shadow-sm overflow-hidden">

            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/40">
                <div class="text-xs font-bold text-gray-700">
                    Daftar Titik Lokasi Pembersihan
                </div>
                <div class="text-xs text-gray-500 font-medium">
                    Menampilkan <span class="font-bold text-gray-800"><?= count($locations) ?></span> lokasi
                </div>
            </div>

            <div class="table-wrap border-0 rounded-none">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th class="w-12 text-center">
                            <th class="min-w-[280px]">Alamat & Koordinat GPS</th>
                            <th class="w-44">Status Penanganan</th>
                            <th class="w-36">Didaftarkan Oleh</th>
                            <th class="w-32">Tanggal Catat</th>
                            <th class="w-28 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach ($locations as $index => $location): ?>
                            <?php
                                $statusKey = $location['status'];
                                $badgeStyles = [
                                    'pending'     => 'bg-amber-50 text-amber-800 border-amber-200 focus:ring-amber-400',
                                    'verified'    => 'bg-blue-50 text-blue-800 border-blue-200 focus:ring-blue-400',
                                    'in_progress' => 'bg-orange-50 text-orange-800 border-orange-200 focus:ring-orange-400',
                                    'completed'   => 'bg-emerald-50 text-emerald-800 border-emerald-200 focus:ring-emerald-400',
                                ];
                                $dotColors = [
                                    'pending'     => 'bg-[#FFC107]',
                                    'verified'    => 'bg-[#2196F3]',
                                    'in_progress' => 'bg-[#FF9800]',
                                    'completed'   => 'bg-[#4CAF50]',
                                ];
                                $currentBadgeStyle = $badgeStyles[$statusKey] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                                $currentDotColor = $dotColors[$statusKey] ?? 'bg-gray-400';
                            ?>
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="text-center font-bold text-xs text-gray-400">
                                    <?= $location['id'] ?>
                                </td>

                                <td class="py-3.5">
                                    <div class="font-bold text-gray-900 text-sm leading-snug">
                                        <?= e($location['address']) ?>
                                    </div>

                                    <?php if (!empty($location['description'])): ?>
                                        <div class="text-xs text-gray-500 mt-1 flex items-start gap-1.5 line-clamp-2">
                                            <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                            </svg>
                                            <span><?= e($location['description']) ?></span>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($location['latitude']) && !empty($location['longitude'])): ?>
                                        <div class="mt-1.5 flex items-center gap-2">
                                            <span class="inline-flex items-center gap-1 font-mono text-[11px] text-gray-500 bg-gray-100 px-2 py-0.5 rounded-md border border-gray-200/60">
                                                <svg class="w-3 h-3 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                                <?= number_format($location['latitude'], 6) ?>, <?= number_format($location['longitude'], 6) ?>
                                            </span>
                                            <button type="button" onclick="focusMapMarker(<?= (float)$location['latitude'] ?>, <?= (float)$location['longitude'] ?>)"
                                                    class="text-[11px] text-emerald-700 hover:text-emerald-800 font-semibold hover:underline" title="Lihat posisi pada peta">
                                                Lihat di Peta
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </td>

                                <td>

                                    <div class="relative inline-block w-full">
                                        <select class="status-select text-xs font-semibold rounded-lg border py-1.5 pl-7 pr-7 w-full cursor-pointer transition focus:outline-none focus:ring-2 appearance-none <?= $currentBadgeStyle ?>"
                                                data-location-id="<?= $location['id'] ?>"
                                                title="Klik untuk mengubah status penanganan">
                                            <?php foreach ($statusOptions as $value => $label): ?>
                                                <option value="<?= $value ?>" <?= ($location['status'] === $value) ? 'selected' : '' ?>>
                                                    <?= $label ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>

                                        <span class="status-indicator-dot absolute left-2.5 top-1/2 -translate-y-1/2 w-2 h-2 rounded-full pointer-events-none <?= $currentDotColor ?>"></span>

                                        <div class="absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none text-gray-500">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 font-bold text-[10px] flex items-center justify-center flex-shrink-0">
                                            <?= strtoupper(substr($location['admin_name'] ?? 'A', 0, 1)) ?>
                                        </div>
                                        <span class="text-xs font-medium text-gray-700 truncate">
                                            <?= e($location['admin_name'] ?? 'Admin') ?>
                                        </span>
                                    </div>
                                </td>

                                <td>
                                    <div class="text-xs text-gray-700 font-medium whitespace-nowrap">
                                        <?= date('d/m/Y', strtotime($location['created_at'])) ?>
                                    </div>
                                    <div class="text-[10px] text-gray-400">
                                        <?= date('H:i', strtotime($location['created_at'])) ?> WIB
                                    </div>
                                </td>

                                <td>
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="<?= url('admin/map-management/edit/' . $location['id']) ?>"
                                           class="p-1.5 text-gray-600 hover:text-emerald-800 hover:bg-emerald-50 rounded-lg border border-gray-200 transition"
                                           title="Edit Data Lokasi">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>

                                        <form action="<?= url('admin/map-management/delete/' . $location['id']) ?>" method="POST" onsubmit="return confirmDeleteLocation(event);">
                                            <?= csrfField() ?>
                                            <button type="submit"
                                                    class="p-1.5 text-rose-600 hover:text-rose-700 hover:bg-rose-50 rounded-lg border border-rose-200 transition"
                                                    title="Hapus Lokasi">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    <?php else: ?>
        <div class="bg-white rounded-2xl border border-gray-200/90 p-12 text-center shadow-sm">
            <div class="w-16 h-16 rounded-2xl bg-gray-50 border border-gray-200 flex items-center justify-center text-gray-400 mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-1">Belum Ada Data Lokasi Pembersihan</h3>
            <p class="text-xs text-gray-500 max-w-sm mx-auto mb-5 leading-relaxed">
                Tambahkan titik lokasi pembersihan baru untuk mulai memantau perkembangan dan sebaran pembersihan lingkungan.
            </p>
            <a href="<?= url('admin/map-management/create') ?>" class="btn btn-primary btn-sm py-2 px-4 text-xs font-semibold inline-flex items-center gap-1.5 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Lokasi Pertama
            </a>
        </div>
    <?php endif; ?>

</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
.custom-map-pin {
    background: transparent;
    border: none;
}
.map-pin-pulse {
    animation: pinDrop 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
@keyframes pinDrop {
    0% { transform: translateY(-16px) scale(0.85); opacity: 0; }
    100% { transform: translateY(0) scale(1); opacity: 1; }
}
</style>

<script>

function createLocationPinIcon(color) {
    return L.divIcon({
        className: 'custom-map-pin map-pin-pulse',
        html: `
            <div style="position: relative; width: 34px; height: 42px; display: flex; flex-direction: column; align-items: center; justify-content: center; filter: drop-shadow(0 3px 6px rgba(0,0,0,0.3)); cursor: pointer;">
                <svg width="34" height="42" viewBox="0 0 36 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18 0C8.05887 0 0 8.05887 0 18C0 28.5 15 42.5 18 44C21 42.5 36 28.5 36 18C36 8.05887 27.9411 0 18 0Z" fill="${color}"/>
                    <circle cx="18" cy="17" r="8" fill="white"/>
                    <path d="M18 11C14.6863 11 12 13.6863 12 17C12 20.3137 14.6863 23 18 23C21.3137 23 24 20.3137 24 17C24 13.6863 21.3137 11 18 11Z" fill="${color}"/>
                    <circle cx="18" cy="17" r="2.5" fill="white"/>
                </svg>
            </div>
        `,
        iconSize: [34, 42],
        iconAnchor: [17, 42],
        popupAnchor: [0, -40]
    });
}

const defaultMapLat = <?= (float)setting('map_default_lat', '-6.2088') ?>;
const defaultMapLng = <?= (float)setting('map_default_lng', '106.8456') ?>;
const defaultMapZoom = <?= (int)setting('map_default_zoom', '11') ?>;

let map = L.map('admin-map', {
    worldCopyJump: false,
    maxBounds: [[-90, -180], [90, 180]],
    maxBoundsViscosity: 1.0,
    minZoom: 2
}).setView([defaultMapLat, defaultMapLng], defaultMapZoom);
let allMarkerBounds = [];

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors',
    maxZoom: 19,
    noWrap: true
}).addTo(map);

function loadMapMarkers() {
    fetch('<?= url('admin/map-management/locations-json') ?>')
        .then(response => response.json())
        .then(locations => {
            allMarkerBounds = [];

            if (Array.isArray(locations)) {
                locations.forEach(loc => {
                    const lat = parseFloat(loc.latitude);
                    const lng = parseFloat(loc.longitude);

                    if (!isNaN(lat) && !isNaN(lng)) {
                        allMarkerBounds.push([lat, lng]);

                        const marker = L.marker([lat, lng], {
                            icon: createLocationPinIcon(loc.statusColor)
                        }).addTo(map);

                        marker.bindPopup(`
                            <div style="font-family: inherit; min-width: 220px; padding: 4px;">
                                <div style="font-weight: 700; color: #111827; font-size: 13px; margin-bottom: 4px; line-height: 1.3;">
                                    ${loc.address}
                                </div>
                                <div style="margin: 6px 0;">
                                    <span style="font-size: 11px; font-weight: 700; background: ${loc.statusColor}; color: white; padding: 2px 8px; border-radius: 6px; display: inline-block;">
                                        ${loc.statusLabel}
                                    </span>
                                </div>
                                ${loc.description ? '<p style="color: #4B5563; font-size: 11px; margin: 4px 0; line-height: 1.4;">' + loc.description + '</p>' : ''}
                                <div style="color: #9CA3AF; font-size: 10px; margin-top: 6px; padding-top: 4px; border-top: 1px solid #F3F4F6;">
                                    Dicatat pada: ${new Date(loc.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })}
                                </div>
                            </div>
                        `);
                    }
                });

                if (allMarkerBounds.length > 0) {
                    map.fitBounds(allMarkerBounds, { padding: [50, 50] });
                }
            }
        })
        .catch(err => {
            console.error('Failed to load map markers:', err);
        });
}

loadMapMarkers();

function resetMapView() {
    if (allMarkerBounds.length > 0) {
        map.fitBounds(allMarkerBounds, { padding: [50, 50] });
    } else {
        map.setView([-6.200000, 106.816666], 11);
    }
}

function focusMapMarker(lat, lng) {
    map.flyTo([lat, lng], 16, { animate: true, duration: 1.2 });
    document.getElementById('admin-map').scrollIntoView({ behavior: 'smooth', block: 'center' });
}

async function confirmDeleteLocation(event) {
    event.preventDefault();
    const form = event.target;

    const confirmed = typeof customConfirm === 'function'
        ? await customConfirm('Apakah Anda yakin ingin menghapus data titik lokasi ini? Tindakan ini tidak dapat dibatalkan.', {
            title: 'Hapus Lokasi Pembersihan',
            confirmText: 'Ya, Hapus',
            cancelText: 'Batal',
            danger: true
        })
        : confirm('Apakah Anda yakin ingin menghapus data titik lokasi ini?');

    if (confirmed) {
        form.submit();
    }
}

document.querySelectorAll('.status-select').forEach(select => {
    select.addEventListener('change', async function() {
        const locationId = this.dataset.locationId;
        const newStatus = this.value;
        const selectElement = this;

        const isConfirmed = typeof customConfirm === 'function'
            ? await customConfirm('Apakah Anda yakin ingin memperbarui status penanganan lokasi ini?', {
                title: 'Konfirmasi Perubahan Status',
                confirmText: 'Ya, Perbarui'
            })
            : confirm('Apakah Anda yakin ingin memperbarui status penanganan lokasi ini?');

        if (isConfirmed) {
            fetch(`<?= url('admin/map-management/update-status/') ?>${locationId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ status: newStatus })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (window.showToast) {
                        showToast('success', 'Status lokasi berhasil diperbarui.');
                    }
                    setTimeout(() => location.reload(), 600);
                } else {
                    if (typeof alertError === 'function') {
                        alertError('Gagal memperbarui status: ' + data.message);
                    } else {
                        alert('Gagal memperbarui status: ' + data.message);
                    }
                }
            })
            .catch(error => {
                if (typeof alertError === 'function') {
                    alertError('Terjadi gangguan jaringan saat memperbarui status.');
                } else {
                    alert('Terjadi kesalahan jaringan.');
                }
            });
        } else {
            location.reload();
        }
    });
});
</script>
