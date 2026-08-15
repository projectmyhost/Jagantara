
<div id="guest-auth-modal" class="modal-overlay" onclick="closeGuestAuthModalOnBackdrop(event)">
    <div class="modal max-w-md w-full" onclick="event.stopPropagation()">

        <div class="text-center">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-[#F7EAE0] text-[#1D4533] mb-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>

            <h3 class="text-xl font-bold text-gray-900 mb-2">Akses Terbatas</h3>

            <p class="text-sm text-gray-600 mb-6 leading-relaxed">
                Silakan login atau daftar terlebih dahulu untuk menggunakan fitur ini.
            </p>

            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="<?= url('auth/login') ?>" class="btn btn-primary flex-1 py-2.5">
                    Login
                </a>
                <a href="<?= url('auth/register') ?>" class="btn btn-outline flex-1 py-2.5">
                    Daftar
                </a>
            </div>

            <button type="button" onclick="closeGuestAuthModal()" class="mt-4 text-xs text-gray-400 hover:text-gray-600 transition">
                Tutup
            </button>
        </div>

    </div>
</div>

<script>
function openGuestAuthModal(actionName) {
    const modal = document.getElementById('guest-auth-modal');
    if (modal) {
        modal.classList.add('show');
    }
}

function closeGuestAuthModal() {
    const modal = document.getElementById('guest-auth-modal');
    if (modal) {
        modal.classList.remove('show');
    }
}

function closeGuestAuthModalOnBackdrop(e) {
    if (e.target.id === 'guest-auth-modal') {
        closeGuestAuthModal();
    }
}
</script>

<div id="logout-modal-overlay" class="logout-modal-overlay" onclick="closeLogoutModalOnBackdrop(event)">
    <div class="logout-modal" onclick="event.stopPropagation()">

        <button type="button" class="logout-modal-close" onclick="closeLogoutModal()" aria-label="Tutup">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <div class="logout-modal-header">
            <div class="logout-modal-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h3 class="logout-modal-title">Konfirmasi Keluar</h3>
            <p class="logout-modal-message">
                Apakah Anda yakin ingin keluar dari akun Anda?
            </p>
        </div>

        <div class="logout-modal-body">
            <div class="logout-info-box">
                <svg class="logout-info-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="logout-info-text">
                    Anda akan keluar dari sesi saat ini. Silakan login kembali jika ingin menggunakan fitur yang memerlukan autentikasi.
                </p>
            </div>
        </div>

        <div class="logout-modal-footer">
            <button type="button" class="logout-btn logout-btn-cancel" onclick="closeLogoutModal()">
                Batal
            </button>
            <button type="button" class="logout-btn logout-btn-confirm" onclick="confirmLogout()">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Ya, Keluar
            </button>
        </div>

    </div>
</div>
