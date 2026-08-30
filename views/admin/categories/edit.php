<div class="max-w-xl">
    <div class="bg-white rounded-2xl border border-gray-200 p-6 sm:p-8 shadow-sm">

        <div class="mb-6 pb-4 border-b border-gray-100">
            <h2 class="text-base font-bold text-gray-900">Edit Kategori 
            <p class="text-xs text-gray-500">Perbarui data kategori lingkungan.</p>
        </div>

        <form action="<?= url('admin/categories/' . $category['id'] . '/update') ?>" method="POST">
            <?= csrfField() ?>

            <div class="space-y-5">

                <div class="form-group mb-0">
                    <label for="name" class="form-label">Nama Kategori <span class="required">*</span></label>
                    <input type="text" id="name" name="name" required value="<?= e($category['name']) ?>" class="form-control">
                </div>

                <div class="form-group mb-0">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea id="description" name="description" rows="2" class="form-control"><?= e($category['description'] ?? '') ?></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="form-group mb-0">
                        <label for="color" class="form-label">Warna Penanda (Hex)</label>
                        <input type="text" id="color" name="color" value="<?= e($category['color'] ?? '#1D4533') ?>" class="form-control">
                    </div>

                    <div class="form-group mb-0">
                        <label for="order" class="form-label">Urutan</label>
                        <input type="number" id="order" name="order" value="<?= (int)$category['order'] ?>" min="0" class="form-control">
                    </div>
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <input type="checkbox" id="is_active" name="is_active" value="1" <?= $category['is_active'] ? 'checked' : '' ?> class="w-4 h-4 text-[#1D4533] rounded">
                    <label for="is_active" class="text-xs font-semibold text-gray-700">Aktifkan kategori ini</label>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                    <a href="<?= url('admin/categories') ?>" class="btn btn-outline py-2 px-4 text-xs">Batal</a>
                    <button type="submit" class="btn btn-primary py-2 px-5 text-xs font-bold shadow-md">Simpan Perubahan</button>
                </div>

            </div>
        </form>
    </div>
</div>
