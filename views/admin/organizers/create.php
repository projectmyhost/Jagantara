<div class="max-w-2xl">
    <div class="bg-white rounded-2xl border border-gray-200 p-6 sm:p-8 shadow-sm">

        <div class="mb-6 pb-4 border-b border-gray-100">
            <h2 class="text-base font-bold text-gray-900">Tambah Organizer Baru</h2>
            <p class="text-xs text-gray-500">Daftarkan komunitas penyelenggara aksi lingkungan.</p>
        </div>

        <form action="<?= url('admin/organizers/store') ?>" method="POST" enctype="multipart/form-data">
            <?= csrfField() ?>

            <div class="space-y-5">

                <div class="form-group mb-0">
                    <label for="name" class="form-label">Nama Organizer / Komunitas <span class="required">*</span></label>
                    <input type="text" id="name" name="name" required class="form-control" placeholder="Contoh: Jagantara Green Volunteers">
                </div>

                <div class="form-group mb-0">
                    <label for="region_id" class="form-label">Wilayah Operasional</label>
                    <select id="region_id" name="region_id" class="form-control">
                        <option value="">-- Pilih Wilayah (JABODETABEK) --</option>
                        <?php foreach ($regions as $r): ?>
                            <option value="<?= $r['id'] ?>"><?= e($r['name']) ?> <?= !empty($r['parent_name']) ? '(' . e($r['parent_name']) . ')' : '' ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group mb-0">
                    <label for="description" class="form-label">Deskripsi & Profil Organizer <span class="required">*</span></label>
                    <textarea id="description" name="description" rows="4" required class="form-control" placeholder="Jelaskan fokus aksi lingkungan, visi, dan kegiatan komunitas"></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="form-group mb-0">
                        <label for="contact_email" class="form-label">Email Kontak</label>
                        <input type="email" id="contact_email" name="contact_email" class="form-control" placeholder="kontak@organizer.id">
                    </div>

                    <div class="form-group mb-0">
                        <label for="contact_phone" class="form-label">Telepon Kontak</label>
                        <input type="text" id="contact_phone" name="contact_phone" class="form-control" placeholder="08123456789">
                    </div>
                </div>

                <div class="form-group mb-0">
                    <label for="logo" class="form-label">Logo Organizer</label>
                    <input type="file" id="logo" name="logo" accept="image/jpeg,image/png,image/webp" class="text-xs text-gray-600 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-100 file:text-[#1D4533]">
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <input type="checkbox" id="is_active" name="is_active" value="1" checked class="w-4 h-4 text-[#1D4533] rounded">
                    <label for="is_active" class="text-xs font-semibold text-gray-700">Aktifkan organizer</label>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                    <a href="<?= url('admin/organizers') ?>" class="btn btn-outline py-2 px-4 text-xs">Batal</a>
                    <button type="submit" class="btn btn-primary py-2 px-5 text-xs font-bold shadow-md">Simpan Organizer</button>
                </div>

            </div>
        </form>
    </div>
</div>
