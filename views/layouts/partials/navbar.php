<nav class="navbar">
    <div class="container flex items-center justify-between w-full">

        <div class="flex items-center gap-2 md:hidden">
            <button type="button" class="mobile-menu-btn" onclick="toggleMobileSidebar()" aria-label="Buka Menu Navigasi">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        <a href="<?= url('') ?>" class="navbar-brand" aria-label="<?= APP_NAME ?> Beranda">
            <div class="logo-icon">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="font-extrabold tracking-tight text-white"><?= APP_NAME ?></span>
        </a>

        <div class="navbar-nav hidden md:flex">
            <a href="<?= url('') ?>" class="<?= currentPath() === '/' ? 'active' : '' ?>">Beranda</a>
            <a href="<?= url('reports') ?>" class="<?= str_starts_with(currentPath(), '/reports') && currentPath() !== '/reports/create' && currentPath() !== '/reports/status' ? 'active' : '' ?>">Laporan</a>
            <a href="<?= url('map') ?>" class="<?= currentPath() === '/map' ? 'active' : '' ?>">Peta</a>
            <a href="<?= url('activities') ?>" class="<?= str_starts_with(currentPath(), '/activities') ? 'active' : '' ?>">Kegiatan</a>
            <a href="<?= url('forum') ?>" class="<?= str_starts_with(currentPath(), '/forum') && currentPath() !== '/forum/create' ? 'active' : '' ?>">Forum Laporan</a>
            <a href="<?= url('organizers') ?>" class="<?= str_starts_with(currentPath(), '/organizers') ? 'active' : '' ?>">Gabung</a>

            <?php if (isLoggedIn()): ?>
                <a href="<?= url('reports/status') ?>" class="<?= currentPath() === '/reports/status' ? 'active' : '' ?>">Status Laporan</a>
            <?php endif; ?>
        </div>

        <div class="navbar-actions flex items-center gap-2 sm:gap-3">

            <?php
                $userObj = $currentUser ?? currentUser() ?? [];
                $displayUsername = $userObj['username'] ?? (!empty($userObj['email']) ? explode('@', $userObj['email'])[0] : (hasRole(ROLE_ADMIN) ? 'Admin' : 'User'));
            ?>

            <a href="<?= isLoggedIn() ? url('reports/status') : url('auth/login') ?>"
               id="navbar-notif-btn"
               class="relative w-9 h-9 rounded-xl bg-white/10 hover:bg-white/15 border border-white/15 flex items-center justify-center text-white/90 hover:text-white transition"
               aria-label="Notifikasi Laporan Aktif"
               title="Notifikasi Laporan Aktif">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <?php if (isLoggedIn()): ?>
                    <span id="navbar-notif-badge"
                          class="absolute -top-1 -right-1 min-w-[18px] h-[18px] rounded-full bg-red-500 ring-2 ring-[var(--color-primary-dark)] text-white text-[10px] font-bold flex items-center justify-center px-1 leading-none"
                          style="display:none;">0</span>
                <?php else: ?>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-emerald-400 ring-2 ring-[var(--color-primary-dark)]"></span>
                <?php endif; ?>
            </a>

            <?php if (isLoggedIn()): ?>

                <?php if (hasRole(ROLE_ADMIN)): ?>
                    <a href="<?= url('admin/dashboard') ?>" class="hidden md:inline-flex btn btn-sm btn-outline-white text-xs py-1.5 px-3">
                        Admin Panel
                    </a>
                <?php endif; ?>

                <a href="<?= url('profile') ?>" class="flex items-center gap-2 group" title="Profil Saya" aria-label="Profil Saya">
                    <div class="w-9 h-9 rounded-xl border border-white/25 flex items-center justify-center text-xs text-white font-bold overflow-hidden shadow-sm transition group-hover:scale-105" style="background: var(--color-primary);">
                        <?php if (!empty($userObj['avatar_path'])): ?>
                            <img src="<?= uploadUrl($userObj['avatar_path']) ?>" class="w-full h-full object-cover" alt="Profil <?= e($displayUsername) ?>" width="36" height="36">
                        <?php else: ?>
                            <?= strtoupper(substr($displayUsername, 0, 1)) ?>
                        <?php endif; ?>
                    </div>
                    <div class="hidden lg:flex flex-col text-left leading-tight">
                        <span class="text-[10px] text-[var(--color-accent2)] font-normal">Halo,</span>
                        <span class="text-xs font-semibold max-w-[110px] truncate text-white">
                            <?= e($displayUsername) ?>
                        </span>
                    </div>
                </a>

                <button type="button" onclick="openLogoutModal()" class="hidden md:inline-flex btn btn-sm btn-outline-white text-xs px-2.5 py-1.5" title="Keluar" aria-label="Keluar dari Akun">
                    Keluar
                </button>

            <?php else: ?>

                <div class="hidden sm:flex items-center gap-2">
                    <a href="<?= url('auth/login') ?>" class="btn btn-sm btn-outline-white text-xs py-1.5 px-3">
                        Masuk
                    </a>
                    <?php if (isRegistrationEnabled()): ?>
                        <a href="<?= url('auth/register') ?>" class="btn btn-sm font-bold text-xs py-1.5 px-3.5 shadow-sm" style="background: var(--color-accent2); color: var(--color-primary-dark);">
                            Daftar
                        </a>
                    <?php endif; ?>
                </div>

                <a href="<?= url('auth/login') ?>" class="sm:hidden w-9 h-9 rounded-xl flex items-center justify-center font-bold shadow-sm transition active:scale-95" style="background: var(--color-accent2); color: var(--color-primary-dark);" title="Masuk" aria-label="Masuk ke Akun">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </a>
            <?php endif; ?>

        </div>

    </div>
</nav>

<div id="mobile-sidebar-overlay" class="mobile-sidebar-overlay" onclick="closeMobileSidebar()"></div>

<aside id="mobile-sidebar" class="mobile-sidebar" aria-label="Menu Navigasi Mobile">

    <div class="mobile-sidebar-header">
        <div class="flex items-center gap-2.5">
            <div class="logo-icon w-8 h-8 rounded-lg bg-white/15 border border-white/20 flex items-center justify-center">
                <svg class="w-5 h-5 text-[var(--color-accent2)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="flex flex-col">
                <span class="text-white font-extrabold text-base tracking-tight"><?= e(setting('site_name', APP_NAME)) ?></span>
                <span class="text-[10px] text-[var(--color-accent2)] font-medium">Aksi Lingkungan JABODETABEK</span>
            </div>
        </div>
        <button type="button" class="mobile-sidebar-close" onclick="closeMobileSidebar()" aria-label="Tutup Menu Navigasi">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <?php if (isLoggedIn()): ?>
        <div class="mobile-sidebar-user">
            <a href="<?= url('profile') ?>" class="flex items-center gap-3 group" onclick="closeMobileSidebar()" aria-label="Pengaturan Profil">
                <div class="w-12 h-12 rounded-2xl border-2 border-white/20 flex items-center justify-center text-sm text-white font-bold overflow-hidden flex-shrink-0 shadow-md" style="background: var(--color-primary);">
                    <?php if (!empty($userObj['avatar_path'])): ?>
                        <img src="<?= uploadUrl($userObj['avatar_path']) ?>" class="w-full h-full object-cover" alt="Profil <?= e($displayUsername) ?>" width="48" height="48">
                    <?php else: ?>
                        <?= strtoupper(substr($displayUsername, 0, 1)) ?>
                    <?php endif; ?>
                </div>
                <div class="flex flex-col min-w-0 flex-1">
                    <span class="text-[11px] text-[var(--color-accent2)] font-medium leading-none mb-1">Masuk sebagai</span>
                    <span class="text-sm font-bold text-white truncate"><?= e($displayUsername) ?></span>
                    <div class="flex items-center gap-1.5 mt-1.5">
                        <?php if (hasRole(ROLE_ADMIN)): ?>
                            <span class="text-[10px] bg-white/15 border border-white/25 text-white font-semibold px-2 py-0.5 rounded-full">
                                Administrator
                            </span>
                        <?php else: ?>
                            <span class="text-[10px] bg-white/10 border border-white/15 text-gray-200 font-semibold px-2 py-0.5 rounded-full">
                                Warga Peduli
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <svg class="w-5 h-5 text-white/40 group-hover:text-white transition group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    <?php else: ?>
        <div class="mobile-sidebar-guest-auth p-4 border-b border-white/10 bg-white/[0.04]">
            <p class="text-xs text-white/80 mb-3 leading-relaxed">
                Bergabung bersama ribuan warga JABODETABEK untuk menjaga kebersihan lingkungan.
            </p>
            <div class="grid <?= isRegistrationEnabled() ? 'grid-cols-2' : 'grid-cols-1' ?> gap-2">
                <a href="<?= url('auth/login') ?>" class="btn btn-sm bg-white hover:bg-gray-100 font-bold text-xs py-2 rounded-xl text-center shadow-sm" style="color: var(--color-primary-dark);" onclick="closeMobileSidebar()">
                    Masuk
                </a>
                <?php if (isRegistrationEnabled()): ?>
                    <a href="<?= url('auth/register') ?>" class="btn btn-sm font-bold text-xs py-2 rounded-xl text-center shadow-sm" style="background: var(--color-accent2); color: var(--color-primary-dark);" onclick="closeMobileSidebar()">
                        Daftar Akun
                    </a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <nav class="mobile-sidebar-nav">

        <div class="mobile-sidebar-section">Menu Utama</div>

        <a href="<?= url('') ?>" class="mobile-sidebar-item <?= currentPath() === '/' ? 'active' : '' ?>" onclick="closeMobileSidebar()">
            <div class="menu-icon-wrap">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </div>
            <span>Beranda</span>
        </a>

        <a href="<?= url('reports') ?>" class="mobile-sidebar-item <?= str_starts_with(currentPath(), '/reports') && currentPath() !== '/reports/create' && currentPath() !== '/reports/status' ? 'active' : '' ?>" onclick="closeMobileSidebar()">
            <div class="menu-icon-wrap">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <span>Laporan Masyarakat</span>
        </a>

        <a href="<?= url('map') ?>" class="mobile-sidebar-item <?= currentPath() === '/map' ? 'active' : '' ?>" onclick="closeMobileSidebar()">
            <div class="menu-icon-wrap">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                </svg>
            </div>
            <span>Peta Titik Masalah</span>
        </a>

        <a href="<?= url('activities') ?>" class="mobile-sidebar-item <?= str_starts_with(currentPath(), '/activities') ? 'active' : '' ?>" onclick="closeMobileSidebar()">
            <div class="menu-icon-wrap">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <span>Kegiatan Lingkungan</span>
        </a>

        <a href="<?= url('forum') ?>" class="mobile-sidebar-item <?= str_starts_with(currentPath(), '/forum') && currentPath() !== '/forum/create' ? 'active' : '' ?>" onclick="closeMobileSidebar()">
            <div class="menu-icon-wrap">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                </svg>
            </div>
            <span>Forum Diskusi</span>
        </a>

        <a href="<?= url('organizers') ?>" class="mobile-sidebar-item <?= str_starts_with(currentPath(), '/organizers') ? 'active' : '' ?>" onclick="closeMobileSidebar()">
            <div class="menu-icon-wrap">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <span>Gabung Organizer</span>
        </a>

        <div class="mobile-sidebar-section">Aksi & Partisipasi</div>

        <?php if (isLoggedIn()): ?>
            <a href="<?= url('reports/create') ?>" class="mobile-sidebar-item highlight-cta" onclick="closeMobileSidebar()">
                <div class="menu-icon-wrap">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <span>Buat Laporan Baru</span>
                <span class="ml-auto text-[10px] font-bold px-2 py-0.5 rounded-full" style="background: var(--color-accent2); color: var(--color-primary-dark);">Baru</span>
            </a>

            <a href="<?= url('reports/status') ?>" class="mobile-sidebar-item <?= currentPath() === '/reports/status' ? 'active' : '' ?>" onclick="closeMobileSidebar()">
                <div class="menu-icon-wrap">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <span>Status Laporan Saya</span>
            </a>

            <a href="<?= url('profile') ?>" class="mobile-sidebar-item <?= str_starts_with(currentPath(), '/profile') ? 'active' : '' ?>" onclick="closeMobileSidebar()">
                <div class="menu-icon-wrap">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <span>Pengaturan Profil</span>
            </a>

        <?php else: ?>
            <button type="button" onclick="closeMobileSidebar(); openGuestAuthModal('lapor');" class="mobile-sidebar-item highlight-cta w-full text-left">
                <div class="menu-icon-wrap">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <span>Buat Laporan Baru</span>
            </button>
        <?php endif; ?>

        <?php if (hasRole(ROLE_ADMIN)): ?>
            <div class="mobile-sidebar-section">Administrasi</div>
            <a href="<?= url('admin/dashboard') ?>" class="mobile-sidebar-item" onclick="closeMobileSidebar()">
                <div class="menu-icon-wrap">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <span>Admin Panel</span>
            </a>
        <?php endif; ?>

    </nav>

    <div class="mobile-sidebar-footer">
        <?php if (isLoggedIn()): ?>
            <button type="button" onclick="closeMobileSidebar(); openLogoutModal();" class="mobile-sidebar-logout-btn">
                <svg class="w-4.5 h-4.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span>Keluar dari Akun</span>
            </button>
        <?php else: ?>
            <div class="text-center">
                <span class="text-[11px] text-white/50 font-medium">&copy; <?= date('Y') ?> <?= APP_NAME ?> &bull; Platform Aksi Bersih</span>
            </div>
        <?php endif; ?>
    </div>

</aside>
