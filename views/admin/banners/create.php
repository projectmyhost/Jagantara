<div class="max-w-2xl">
    <div class="bg-white rounded-2xl border border-gray-200 p-6 sm:p-8 shadow-sm">

        <div class="mb-6 pb-4 border-b border-gray-100">
            <h2 class="text-base font-bold text-gray-900">Formulir Tambah Banner</h2>
            <p class="text-xs text-gray-500">Unggah foto banner berkualitas tinggi untuk slider beranda.</p>
        </div>

        <form action="<?= url('admin/banners/store') ?>" method="POST" enctype="multipart/form-data">
            <?= csrfField() ?>

            <div class="space-y-5">

                <div class="form-group mb-0">
                    <label for="title" class="form-label">Judul Banner <span class="required">*</span></label>
                    <input type="text" id="title" name="title" required class="form-control" placeholder="Contoh: Aksi Bersih Sungai Jabodetabek">
                </div>

                <div class="form-group mb-0">
                    <label for="subtitle" class="form-label">Subjudul / Deskripsi Singkat</label>
                    <textarea id="subtitle" name="subtitle" rows="2" class="form-control" placeholder="Teks keterangan pendukung banner"></textarea>
                </div>

                <div class="form-group mb-0">
                    <label for="image" class="form-label">File Gambar Banner <span class="required">*</span></label>
                    <input type="file" id="image" name="image" required accept="image/jpeg,image/png,image/webp" class="text-xs text-gray-600 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-100 file:text-[#1D4533]">
                    <p class="text-[11px] text-gray-400 mt-1">Rekomendasi rasio landscape 16:9 atau 21:9. Format: JPG, PNG, WebP. Maks 5MB.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="form-group mb-0">
                        <label for="link_url" class="form-label">Tautan / Link Tujuan (Opsional)</label>
                        <input type="text" id="link_url" name="link_url" class="form-control" placeholder="/reports atau https://...">
                    </div>

                    <div class="form-group mb-0">
                        <label for="order" class="form-label">Urutan Tampil</label>
                        <input type="number" id="order" name="order" value="1" min="0" class="form-control">
                    </div>
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <input type="checkbox" id="is_active" name="is_active" value="1" checked class="w-4 h-4 text-[#1D4533] rounded">
                    <label for="is_active" class="text-xs font-semibold text-gray-700">Aktifkan banner langsung di halaman beranda</label>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                    <a href="<?= url('admin/banners') ?>" class="btn btn-outline py-2 px-4 text-xs">Batal</a>
                    <button type="submit" class="btn btn-primary py-2 px-5 text-xs font-bold shadow-md">Simpan Banner</button>
                </div>

            </div>
        </form>
    </div>
</div>
