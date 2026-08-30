<?php $regActive = $registrationEnabled ?? isRegistrationEnabled(); ?>
<div class="min-h-[calc(100vh-200px)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-lg w-full space-y-8 bg-white p-8 sm:p-10 rounded-2xl shadow-md border border-gray-100">

        <?php if (!$regActive): ?>
            <div class="text-center py-4">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-amber-50 text-amber-600 border border-amber-200 shadow-sm mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Pendaftaran Ditutup</h2>
                <p class="mt-3 text-sm text-gray-600 leading-relaxed">
                    Mohon maaf, pendaftaran akun baru saat ini sedang dinonaktifkan sementara oleh administrator <?= e(setting('site_name', APP_NAME)) ?>.
                </p>
                <div class="mt-6 p-4 rounded-xl bg-gray-50 border border-gray-200 text-xs text-gray-500 text-left space-y-2">
                    <div class="flex items-center gap-2 text-gray-700 font-medium">
                        <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Sudah memiliki akun terdaftar?</span>
                    </div>
                    <p class="pl-6">Warga yang sudah memiliki akun tetap dapat masuk dan menggunakan seluruh fitur pelaporan seperti biasa.</p>
                </div>
                <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="<?= url('auth/login') ?>" class="btn btn-primary py-2.5 px-6 text-sm font-bold shadow-md">
                        Masuk ke Akun
                    </a>
                    <a href="<?= url('') ?>" class="btn btn-outline py-2.5 px-6 text-sm">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-[#1D4533] text-white shadow-sm mb-4">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Daftar Akun Baru</h2>
                <p class="mt-2 text-sm text-gray-600">
                    Sudah memiliki akun?
                    <a href="<?= url('auth/login') ?>" class="font-semibold text-[#1D4533] hover:underline">
                        Masuk di sini
                    </a>
                </p>
            </div>

            <form class="mt-8 space-y-4" action="<?= url('auth/register') ?>" method="POST" autocomplete="off">
                <?= csrfField() ?>

                <div class="form-group">
                    <label for="full_name" class="form-label">Nama Lengkap <span class="required">*</span></label>
                    <input id="full_name" name="full_name" type="text" required
                           value="<?= e($values['full_name'] ?? '') ?>"
                           class="form-control"
                           autocomplete="off"
                           placeholder="Contoh: Ahmad Hidayat">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label for="username" class="form-label">Username <span class="required">*</span></label>
                        <input id="username" name="username" type="text" required
                               value="<?= e($values['username'] ?? '') ?>"
                               class="form-control"
                               autocomplete="off"
                               placeholder="username_anda">
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Alamat Email <span class="required">*</span></label>
                        <input id="email" name="email" type="email" autocomplete="off" required
                               value="<?= e($values['email'] ?? '') ?>"
                               class="form-control"
                               placeholder="nama@email.com">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label for="password" class="form-label">Password <span class="required">*</span></label>
                        <div class="relative">
                            <input id="password" name="password" type="password" required
                                   autocomplete="new-password"
                                   class="form-control pr-11"
                                   placeholder="Min. 8 karakter">
                            <button type="button" onclick="togglePassword('password', 'eye-pass')" tabindex="-1"
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                                <svg id="eye-pass" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password_confirm" class="form-label">Ulangi Password <span class="required">*</span></label>
                        <div class="relative">
                            <input id="password_confirm" name="password_confirm" type="password" required
                                   autocomplete="new-password"
                                   class="form-control pr-11"
                                   placeholder="Konfirmasi password">
                            <button type="button" onclick="togglePassword('password_confirm', 'eye-confirm')" tabindex="-1"
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                                <svg id="eye-confirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50/80 p-4 rounded-xl border border-gray-200 transition-all hover:border-gray-300">
                    <div class="flex items-start gap-3">
                        <input type="checkbox" id="terms" name="terms" value="1" <?= !empty($values['terms']) ? 'checked' : '' ?> required
                               class="w-4 h-4 mt-0.5 text-[#1D4533] border-gray-300 rounded focus:ring-[#1D4533] cursor-pointer accent-[#1D4533]">
                        <label for="terms" class="text-xs text-gray-600 leading-relaxed cursor-pointer select-none">
                            Saya telah membaca dan menyetujui 
                            <button type="button" onclick="openTermsModal()" class="font-semibold text-[#1D4533] hover:underline inline-flex items-center gap-0.5">
                                Syarat & Ketentuan
                            </button> 
                            serta etika pelaporan lingkungan di Jagantara. <span class="text-red-500 font-bold">*</span>
                        </label>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" id="btn-register" class="w-full btn btn-primary py-3 font-semibold shadow-sm text-base transition-all duration-200 flex items-center justify-center gap-2">
                        <span>Daftar Sekarang</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>
<div id="terms-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4 overflow-y-auto" onclick="closeTermsModalOnBackdrop(event)">
    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-gray-100 p-6 sm:p-7 overflow-hidden my-8" onclick="event.stopPropagation()">
        
        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-[#F7EAE0] text-[#1D4533] flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Syarat & Ketentuan Jagantara</h3>
            </div>
            <button type="button" onclick="closeTermsModal()" class="text-gray-400 hover:text-gray-600 transition p-1 rounded-lg hover:bg-gray-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="py-4 space-y-4 text-xs text-gray-600 max-h-[60vh] overflow-y-auto pr-1 leading-relaxed">
            <div class="p-3 bg-[#F7EAE0]/50 rounded-xl border border-[#F9D2BA]/60 text-gray-800 font-medium">
                Selamat datang di platform Jagantara. Demi menjaga ketertiban, keamanan, dan keakuratan laporan aksi lingkungan, setiap pengguna wajib mematuhi ketentuan berikut:
            </div>

            <div class="space-y-2">
                <h4 class="font-bold text-gray-900 text-sm flex items-center gap-1.5">
                    <span class="w-5 h-5 rounded-full bg-[#1D4533] text-white flex items-center justify-center text-[10px]">1</span>
                    Keaslian & Validitas Laporan
                </h4>
                <p class="pl-6.5 text-gray-600">
                    Setiap laporan permasalahan lingkungan (sampah liar, polusi, banjir, dsb.) harus berdasarkan kejadian nyata, memiliki bukti foto asli, dan lokasi yang akurat. Laporan palsu, manipulatif, atau hoaks dilarang keras.
                </p>
            </div>

            <div class="space-y-2">
                <h4 class="font-bold text-gray-900 text-sm flex items-center gap-1.5">
                    <span class="w-5 h-5 rounded-full bg-[#1D4533] text-white flex items-center justify-center text-[10px]">2</span>
                    Etika & Kesantunan Komunitas
                </h4>
                <p class="pl-6.5 text-gray-600">
                    Pengguna dilarang menggunakan ujaran kebencian, kata-kata kasar, pelecehan, unsur SARA, pornografi, atau menyudutkan pihak manapun dalam deskripsi laporan, forum diskusi, maupun komentar.
                </p>
            </div>

            <div class="space-y-2">
                <h4 class="font-bold text-gray-900 text-sm flex items-center gap-1.5">
                    <span class="w-5 h-5 rounded-full bg-[#1D4533] text-white flex items-center justify-center text-[10px]">3</span>
                    Tanggung Jawab Akun
                </h4>
                <p class="pl-6.5 text-gray-600">
                    Anda bertanggung jawab penuh atas keamanan kredensial akun Anda serta seluruh aktivitas yang terjadi di bawah akun Anda.
                </p>
            </div>

            <div class="space-y-2">
                <h4 class="font-bold text-gray-900 text-sm flex items-center gap-1.5">
                    <span class="w-5 h-5 rounded-full bg-[#1D4533] text-white flex items-center justify-center text-[10px]">4</span>
                    Tindakan Pelanggaran
                </h4>
                <p class="pl-6.5 text-gray-600">
                    Jagantara berhak membatasi, menangguhkan (suspend), atau memblokir permanen (banned) akun yang melanggar ketentuan di atas tanpa pemberitahuan sebelumnya.
                </p>
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
            <button type="button" onclick="closeTermsModal()" class="btn btn-outline py-2 px-4 text-xs font-semibold">
                Tutup
            </button>
            <button type="button" onclick="acceptTermsFromModal()" class="btn btn-primary py-2 px-5 text-xs font-bold shadow-md">
                Saya Mengerti & Setuju
            </button>
        </div>
    </div>
</div>

<script>
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';
    if (isHidden) {
        icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>`;
    } else {
        icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
    }
}
const termsCheckbox = document.getElementById('terms');
const btnRegister = document.getElementById('btn-register');

function updateSubmitState() {
    if (!termsCheckbox || !btnRegister) return;
    if (termsCheckbox.checked) {
        btnRegister.removeAttribute('disabled');
        btnRegister.classList.remove('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
    } else {
        btnRegister.setAttribute('disabled', 'disabled');
        btnRegister.classList.add('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
    }
}

if (termsCheckbox) {
    termsCheckbox.addEventListener('change', updateSubmitState);
    updateSubmitState();
}

function openTermsModal() {
    const modal = document.getElementById('terms-modal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
}

function closeTermsModal() {
    const modal = document.getElementById('terms-modal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }
}

function closeTermsModalOnBackdrop(e) {
    if (e.target.id === 'terms-modal') {
        closeTermsModal();
    }
}

function acceptTermsFromModal() {
    if (termsCheckbox) {
        termsCheckbox.checked = true;
        updateSubmitState();
    }
    closeTermsModal();
}
</script>

