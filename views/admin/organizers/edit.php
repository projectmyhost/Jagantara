<div class="max-w-2xl">
    <div class="bg-white rounded-2xl border border-gray-200 p-6 sm:p-8 shadow-sm">

        <div class="mb-6 pb-4 border-b border-gray-100">
            <h2 class="text-base font-bold text-gray-900">Edit Organizer 
            <p class="text-xs text-gray-500">Perbarui profil komunitas organizer.</p>
        </div>

        <form action="<?= url('admin/organizers/' . $organizer['id'] . '/update') ?>" method="POST" enctype="multipart/form-data">
            <?= csrfField() ?>

            <div class="space-y-5">

                <div class="form-group mb-0">
                    <label for="name" class="form-label">Nama Organizer <span class="required">*</span></label>
                    <input type="text" id="name" name="name" required value="<?= e($organizer['name']) ?>" class="form-control">
                </div>

                <div class="form-group mb-0">
                    <label for="region_id" class="form-label">Wilayah Operasional</label>
                    <select id="region_id" name="region_id" class="form-control">
                        <option value="">-- Pilih Wilayah --</option>
                        <?php foreach ($regions as $r): ?>
                            <option value="<?= $r['id'] ?>" <?= ($organizer['region_id'] ?? '') == $r['id'] ? 'selected' : '' ?>>
                                <?= e($r['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group mb-0">
                    <label for="description" class="form-label">Deskripsi <span class="required">*</span></label>
                    <textarea id="description" name="description" rows="4" required class="form-control"><?= e($organizer['description'] ?? '') ?></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="form-group mb-0">
                        <label for="contact_email" class="form-label">Email Kontak</label>
                        <input type="email" id="contact_email" name="contact_email" value="<?= e($organizer['contact_email'] ?? '') ?>" class="form-control">
                    </div>

                    <div class="form-group mb-0">
                        <label for="contact_phone" class="form-label">Telepon Kontak</label>
                        <input type="text" id="contact_phone" name="contact_phone" value="<?= e($organizer['contact_phone'] ?? '') ?>" class="form-control">
                    </div>
                </div>

                <div>
                    <label class="form-label">Logo Organizer</label>
                    <?php if (!empty($organizer['logo_path']) && file_exists(UPLOAD_PATH . '/' . $organizer['logo_path'])): ?>
                        <img src="<?= uploadUrl($organizer['logo_path']) ?>" class="w-16 h-16 object-cover rounded-xl border border-gray-200 mb-2" alt="Logo">
                    <?php endif; ?>
                    <input type="file" id="logo" name="logo" accept="image/jpeg,image/png,image/webp" class="text-xs text-gray-600 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-100 file:text-[#1D4533]">
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <input type="checkbox" id="is_active" name="is_active" value="1" <?= $organizer['is_active'] ? 'checked' : '' ?> class="w-4 h-4 text-[#1D4533] rounded">
                    <label for="is_active" class="text-xs font-semibold text-gray-700">Aktifkan organizer</label>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                    <a href="<?= url('admin/organizers') ?>" class="btn btn-outline py-2 px-4 text-xs">Batal</a>
                    <button type="submit" class="btn btn-primary py-2 px-5 text-xs font-bold shadow-md">Simpan Perubahan</button>
                </div>

            </div>
        </form>
    </div>
</div>
