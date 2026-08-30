<div class="max-w-xl">
    <div class="bg-white rounded-2xl border border-gray-200 p-6 sm:p-8 shadow-sm">

        <div class="mb-6 pb-4 border-b border-gray-100">
            <h2 class="text-base font-bold text-gray-900">Edit Peran & Status Pengguna 
            <p class="text-xs text-gray-500">Pengguna: <strong><?= e($user['email']) ?></strong></p>
        </div>

        <form action="<?= url('admin/users/' . $user['id'] . '/update') ?>" method="POST">
            <?= csrfField() ?>

            <div class="space-y-5">

                <div class="form-group mb-0">
                    <label for="role" class="form-label">Peran Akses (Role) <span class="required">*</span></label>
                    <select id="role" name="role" required class="form-control">
                        <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>Masyarakat (User)</option>
                        <option value="organizer_member" <?= $user['role'] === 'organizer_member' ? 'selected' : '' ?>>Anggota Organizer</option>
                        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Administrator</option>
                    </select>
                </div>

                <div class="form-group mb-0">
                    <label for="status" class="form-label">Status Akun <span class="required">*</span></label>
                    <select id="status" name="status" required class="form-control">
                        <option value="active" <?= $user['status'] === 'active' ? 'selected' : '' ?>>Aktif (Active)</option>
                        <option value="suspended" <?= $user['status'] === 'suspended' ? 'selected' : '' ?>>Ditangguhkan (Suspended)</option>
                        <option value="banned" <?= $user['status'] === 'banned' ? 'selected' : '' ?>>Diblokir (Banned)</option>
                    </select>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                    <a href="<?= url('admin/users/' . $user['id']) ?>" class="btn btn-outline py-2 px-4 text-xs">Batal</a>
                    <button type="submit" class="btn btn-primary py-2 px-5 text-xs font-bold shadow-md">Simpan Perubahan</button>
                </div>

            </div>
        </form>
    </div>
</div>
