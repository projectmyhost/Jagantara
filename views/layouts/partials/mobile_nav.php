
<div class="mobile-bottom-nav">
    <div class="nav-container">

        <div class="nav-group nav-group-left">

            <a href="<?= url('') ?>" class="nav-item <?= currentPath() === '/' ? 'active' : '' ?>" aria-label="Beranda">
                <div class="nav-icon-wrap">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <span>Home</span>
            </a>

            <a href="<?= url('reports') ?>" class="nav-item <?= str_starts_with(currentPath(), '/reports') && currentPath() !== '/reports/create' && currentPath() !== '/reports/status' ? 'active' : '' ?>" aria-label="Laporan">
                <div class="nav-icon-wrap">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <span>Lapor</span>
            </a>
        </div>

        <div class="nav-fab-wrap">
            <?php if (isLoggedIn()): ?>
                <a href="<?= url('reports/create') ?>" class="nav-fab" aria-label="Buat Laporan Baru">
                    <div class="nav-fab-btn">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                </a>
            <?php else: ?>
                <button type="button" onclick="openGuestAuthModal('lapor')" class="nav-fab" aria-label="Buat Laporan Baru">
                    <div class="nav-fab-btn">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                </button>
            <?php endif; ?>
        </div>

        <div class="nav-group nav-group-right">

            <a href="<?= url('activities') ?>" class="nav-item <?= str_starts_with(currentPath(), '/activities') ? 'active' : '' ?>" aria-label="Kegiatan">
                <div class="nav-icon-wrap">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <span>Kegiatan</span>
            </a>

            <?php if (isLoggedIn()): ?>
                <a href="<?= url('profile') ?>" class="nav-item <?= str_starts_with(currentPath(), '/profile') ? 'active' : '' ?>" aria-label="Profil Saya">
                    <div class="nav-icon-wrap">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <span>Profil</span>
                </a>
            <?php else: ?>
                <button type="button" onclick="openGuestAuthModal('profil')" class="nav-item" aria-label="Masuk ke Akun">
                    <div class="nav-icon-wrap">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                    </div>
                    <span>Masuk</span>
                </button>
            <?php endif; ?>
        </div>

    </div>
</div>
