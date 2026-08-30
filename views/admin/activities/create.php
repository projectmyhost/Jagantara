<div class="max-w-2xl">
    <div class="bg-white rounded-2xl border border-gray-200 p-6 sm:p-8 shadow-sm">

        <div class="mb-6 pb-4 border-b border-gray-100">
            <h2 class="text-base font-bold text-gray-900">Tambah Kegiatan Baru</h2>
            <p class="text-xs text-gray-500">Buat jadwal kegiatan pembersihan / kerja bakti lingkungan.</p>
        </div>

        <form action="<?= url('admin/activities/store') ?>" method="POST">
            <?= csrfField() ?>

            <div class="space-y-5">

                <div class="form-group mb-0">
                    <label for="title" class="form-label">Judul Kegiatan <span class="required">*</span></label>
                    <input type="text" id="title" name="title" required class="form-control" placeholder="Contoh: Kerja Bakti Bersih Kali Ciliwung">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="form-group mb-0">
                        <label for="organizer_id" class="form-label">Penyelenggara (Organizer) <span class="required">*</span></label>
                        <select id="organizer_id" name="organizer_id" required class="form-control">
                            <option value="">-- Pilih Organizer --</option>
                            <?php foreach ($organizers as $o): ?>
                                <option value="<?= $o['id'] ?>"><?= e($o['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group mb-0">
                        <label for="region_id" class="form-label">Wilayah Pelaksanaan <span class="required">*</span></label>
                        <select id="region_id" name="region_id" required class="form-control">
                            <option value="">-- Pilih Wilayah --</option>
                            <?php foreach ($regions as $r): ?>
                                <option value="<?= $r['id'] ?>"><?= e($r['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="form-group mb-0">
                        <label for="scheduled_at" class="form-label">Jadwal Pelaksanaan <span class="required">*</span></label>
                        <input type="datetime-local" id="scheduled_at" name="scheduled_at" required class="form-control">
                    </div>

                    <div class="form-group mb-0">
                        <label for="max_participants" class="form-label">Kuota Maksimal Peserta (Opsional)</label>
                        <input type="number" id="max_participants" name="max_participants" min="1" class="form-control" placeholder="Contoh: 50">
                    </div>
                </div>

                <div class="form-group mb-0">
                    <label for="location_name" class="form-label">Nama / Patokan Lokasi</label>
                    <input type="text" id="location_name" name="location_name" class="form-control" placeholder="Contoh: Titik Kumpul Jembatan Merah">
                </div>

                <div class="form-group mb-0">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-1.5">
                        <label for="address" class="form-label mb-0">Alamat Lengkap</label>
                        <button type="button" id="geo-btn" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#1D4533] hover:bg-[#14301F] text-white text-xs font-semibold rounded-xl shadow-sm transition-all focus:ring-2 focus:ring-emerald-400 focus:outline-none cursor-pointer">
                            <svg class="w-3.5 h-3.5 text-[#F9D2BA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Gunakan Lokasi Terkini</span>
                        </button>
                    </div>
                    <textarea id="address" name="address" rows="2" class="form-control" placeholder="Alamat detail lokasi kegiatan"></textarea>
                    <div id="geo-status" class="mt-2 hidden"></div>
                    <p class="text-[11px] text-gray-400 mt-1">
                        Klik <strong>"Gunakan Lokasi Terkini"</strong> untuk mengisi alamat dan wilayah pelaksanaan secara otomatis melalui GPS perangkat.
                    </p>
                </div>

                <div class="form-group mb-0">
                    <label for="description" class="form-label">Deskripsi & Rencana Aksi <span class="required">*</span></label>
                    <textarea id="description" name="description" rows="4" required class="form-control" placeholder="Jelaskan perlengkapan yang perlu dibawa, titik kumpul, dan susunan kegiatan"></textarea>
                </div>

                <div class="form-group mb-0">
                    <label for="status" class="form-label">Status Publikasi</label>
                    <select id="status" name="status" class="form-control">
                        <option value="published">Publikasikan (Published)</option>
                        <option value="draft">Draf (Draft)</option>
                        <option value="ongoing">Sedang Berlangsung (Ongoing)</option>
                        <option value="completed">Selesai (Completed)</option>
                    </select>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                    <a href="<?= url('admin/activities') ?>" class="btn btn-outline py-2 px-4 text-xs">Batal</a>
                    <button type="submit" class="btn btn-primary py-2 px-5 text-xs font-bold shadow-md">Simpan Kegiatan</button>
                </div>

            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (window.JagantaraGeo) {
        JagantaraGeo.init({
            btn: '#geo-btn',
            address: '#address',
            regionId: '#region_id',
            locationName: '#location_name',
            status: '#geo-status'
        });
    }
});
</script>
