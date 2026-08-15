<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Admin Panel - ' . APP_NAME) ?></title>
    <meta name="theme-color" content="<?= theme('color_primary', '#1D4533') ?>">

    <style>
        :root {
            --color-primary:         <?= theme('color_primary',    '#1D4533') ?>;
            --color-secondary:       <?= theme('color_secondary',  '#5E3122') ?>;
            --color-accent1:         <?= theme('color_accent1',    '#F7EAE0') ?>;
            --color-accent2:         <?= theme('color_accent2',    '#F9D2BA') ?>;
            --color-surface:         <?= theme('color_surface',    '#FAFAF8') ?>;
            --color-text:            <?= theme('color_text',       '#1A1A1A') ?>;
            --color-muted:           <?= theme('color_muted',      '#4B5563') ?>;
            --color-success:         <?= theme('color_success',    '#16A34A') ?>;
            --color-warning:         <?= theme('color_warning',    '#D97706') ?>;
            --color-danger:          <?= theme('color_danger',     '#DC2626') ?>;
            --color-info:            <?= theme('color_info',       '#0EA5E9') ?>;
            --color-border:          <?= theme('color_border',     '#E5E7EB') ?>;
            --color-bg:              <?= theme('color_background', '#F9FAFB') ?>;

            --color-primary-dark:    <?= theme('color_primary_dark',   '#14301F') ?>;
            --color-primary-light:   <?= theme('color_primary_light',  '#2A6349') ?>;
            --color-secondary-light: <?= theme('color_secondary_light','#7A4535') ?>;
            --color-text-muted:      <?= theme('color_muted',          '#4B5563') ?>;
            --color-white:           #FFFFFF;
        }
    </style>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        forest: {
                            DEFAULT: '<?= theme('color_primary', '#1D4533') ?>',
                            dark: '<?= theme('color_primary_dark', '#14301F') ?>',
                            light: '<?= theme('color_primary_light', '#2A6349') ?>',
                        },
                        earth: {
                            DEFAULT: '<?= theme('color_secondary', '#5E3122') ?>',
                            light: '<?= theme('color_secondary_light', '#7A4535') ?>',
                        },
                        cream: '<?= theme('color_accent1', '#F7EAE0') ?>',
                        peach: '<?= theme('color_accent2', '#F9D2BA') ?>',
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

    <?php
    $adminCurPath = currentPath();
    $adminBase = rtrim(parse_url(BASE_URL, PHP_URL_PATH) ?? '', '/');
    $adminCleanPath = str_replace($adminBase, '', $adminCurPath);
    $adminNeedsMap = ($needsMap ?? false) || (
        str_starts_with($adminCleanPath, '/admin/map') ||
        str_starts_with($adminCleanPath, '/admin/reports/') ||
        str_contains($adminCleanPath, 'map-management')
    );
    ?>
    <?php if ($adminNeedsMap): ?>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <?php endif; ?>
    <link rel="stylesheet" href="<?= url('public/css/app.css') ?>">
    <script src="<?= url('public/js/app.js') ?>" defer></script>
    <script src="<?= url('public/js/geolocation.js') ?>"></script>

</head>
<body class="bg-gray-100 min-h-screen"<?php if (isLoggedIn()): ?> data-user-id="<?= e((int)currentUserId()) ?>" data-check-status-url="<?= url('auth/check-status') ?>" data-logout-url="<?= url('auth/logout') ?>" data-login-url="<?= url('auth/login') ?>" data-contact-email="<?= e(setting('site_contact_email', 'info@jagantara.id')) ?>"<?php endif; ?>>

    <div class="admin-layout flex min-h-screen">

        <div id="admin-sidebar-overlay" class="admin-sidebar-overlay" onclick="closeAdminSidebar()"></div>

        <aside id="admin-sidebar" class="admin-sidebar">
            <div class="admin-sidebar-header flex items-center justify-between">
                <a href="<?= url('admin/dashboard') ?>" class="flex items-center gap-2 text-white font-bold text-base">
                    <div class="w-8 h-8 rounded-lg bg-white/15 flex items-center justify-center">
                        <svg class="w-5 h-5 text-[#F9D2BA]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <span><?= APP_NAME ?> Admin</span>
                </a>
                <button type="button" class="lg:hidden text-white/70 hover:text-white" onclick="closeAdminSidebar()" aria-label="Tutup Menu Sidebar">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <nav class="admin-sidebar-nav">
                <div class="admin-nav-section">Utama</div>

                <a href="<?= url('admin/dashboard') ?>" class="admin-nav-item <?= currentPath() === '/admin' || currentPath() === '/admin/dashboard' ? 'active' : '' ?>">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <a href="<?= url('admin/reports') ?>" class="admin-nav-item <?= str_starts_with(currentPath(), '/admin/reports') ? 'active' : '' ?>">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    <span>Laporan Masyarakat</span>
                </a>

                <a href="<?= url('admin/banners') ?>" class="admin-nav-item <?= str_starts_with(currentPath(), '/admin/banners') ? 'active' : '' ?>">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Banner & Slider</span>
                </a>

                <div class="admin-nav-section">Organizer & Aksi</div>

                <a href="<?= url('admin/organizers') ?>" class="admin-nav-item <?= str_starts_with(currentPath(), '/admin/organizers') ? 'active' : '' ?>">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span>Organizer & Pendaftaran</span>
                </a>

                <a href="<?= url('admin/activities') ?>" class="admin-nav-item <?= str_starts_with(currentPath(), '/admin/activities') ? 'active' : '' ?>">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Kegiatan Lingkungan</span>
                </a>

                <div class="admin-nav-section">Data Master</div>

                <a href="<?= url('admin/categories') ?>" class="admin-nav-item <?= str_starts_with(currentPath(), '/admin/categories') ? 'active' : '' ?>">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    <span>Kategori Lingkungan</span>
                </a>

                <a href="<?= url('admin/regions') ?>" class="admin-nav-item <?= str_starts_with(currentPath(), '/admin/regions') ? 'active' : '' ?>">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>Wilayah (Jabodetabek)</span>
                </a>

                <a href="<?= url('admin/map-management') ?>" class="admin-nav-item <?= str_starts_with(currentPath(), '/admin/map-management') ? 'active' : '' ?>">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                    </svg>
                    <span>Manajemen Peta Lokasi</span>
                </a>

                <a href="<?= url('admin/users') ?>" class="admin-nav-item <?= str_starts_with(currentPath(), '/admin/users') ? 'active' : '' ?>">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span>Manajemen Pengguna</span>
                </a>

                <div class="admin-nav-section">Sistem & Keamanan</div>

                <a href="<?= url('admin/audit-logs') ?>" class="admin-nav-item <?= currentPath() === '/admin/audit-logs' ? 'active' : '' ?>">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Audit Logs</span>
                </a>

                <a href="<?= url('admin/security-logs') ?>" class="admin-nav-item <?= currentPath() === '/admin/security-logs' ? 'active' : '' ?>">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <span>Security Logs</span>
                </a>

                <a href="<?= url('admin/theme') ?>" class="admin-nav-item <?= str_starts_with(currentPath(), '/admin/theme') ? 'active' : '' ?>">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4 5 5 0 015-5c1 0 2 .5 2 1.5S11 15 12 15s1-.5 2-1.5 1-1.5 2-1.5 5 2.239 5 5a4 4 0 01-4 4H7z"/>
                    </svg>
                    <span>Tema Website</span>
                </a>

                <a href="<?= url('admin/settings') ?>" class="admin-nav-item <?= str_starts_with(currentPath(), '/admin/settings') ? 'active' : '' ?>">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>Pengaturan Sistem</span>
                </a>

                <div class="px-4 py-4 mt-4 border-t border-white/10">
                    <a href="<?= url('') ?>" class="btn btn-outline-white w-full text-xs py-2">
                        Lihat Website
                    </a>
                </div>
            </nav>
        </aside>

        <div class="admin-content flex flex-col flex-1 min-w-0">

            <header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-4 sm:px-6 sticky top-0 z-30 shadow-sm">
                <div class="flex items-center gap-3">
                    <button type="button" class="lg:hidden text-gray-600 hover:text-gray-900 p-1.5 rounded-lg border border-gray-200" onclick="openAdminSidebar()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <h1 class="text-lg font-bold text-gray-800 hidden sm:block">
                        <?= e($adminTitle ?? 'Admin Panel') ?>
                    </h1>
                </div>

                <div class="flex items-center gap-3">
                    <span class="text-xs bg-emerald-100 text-emerald-800 px-2.5 py-1 rounded-full font-semibold">
                        Admin Mode
                    </span>
                    <button type="button" onclick="openLogoutModal()" class="btn btn-sm btn-outline text-xs px-3 py-1">
                        Keluar
                    </button>
                </div>
            </header>

            <div id="toast-container" aria-live="polite" aria-atomic="false"></div>

            <?php if (!empty($flashMessages)): ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        <?php foreach ($flashMessages as $flash): ?>
                            showToast(
                                <?= json_encode($flash['type'] ?? 'info') ?>,
                                <?= json_encode($flash['message']) ?>
                            );
                        <?php endforeach; ?>
                    });
                </script>
            <?php endif; ?>

            <main class="p-4 sm:p-6 flex-1 min-w-0">
                <?= $content ?>
            </main>

        </div>

    </div>

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
                    Apakah Anda yakin ingin keluar dari Admin Panel?
                </p>
            </div>

            <div class="logout-modal-body">
                <div class="logout-info-box">
                    <svg class="logout-info-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="logout-info-text">
                        Anda akan keluar dari sesi Admin saat ini. Silakan login kembali untuk mengakses Admin Panel.
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

    <script src="<?= url('public/js/app.js') ?>"></script>
    <script>

        let _sidebarScrollY = 0;

        function _lockBodyScroll() {
            _sidebarScrollY = window.scrollY || document.documentElement.scrollTop;
            document.body.style.top = '-' + _sidebarScrollY + 'px';
            document.body.classList.add('sidebar-open');
        }

        function _unlockBodyScroll() {
            document.body.classList.remove('sidebar-open');
            document.body.style.top = '';

            window.scrollTo({ top: _sidebarScrollY, behavior: 'instant' });
        }

        function openAdminSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const overlay = document.getElementById('admin-sidebar-overlay');
            if (!sidebar || !overlay) return;
            sidebar.classList.add('open');
            overlay.classList.add('visible');
            _lockBodyScroll();
        }

        function closeAdminSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const overlay = document.getElementById('admin-sidebar-overlay');
            if (!sidebar || !overlay) return;
            sidebar.classList.remove('open');
            overlay.classList.remove('visible');
            _unlockBodyScroll();
        }

        function toggleAdminSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            if (!sidebar) return;
            if (sidebar.classList.contains('open')) {
                closeAdminSidebar();
            } else {
                openAdminSidebar();
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const overlay = document.getElementById('admin-sidebar-overlay');
            if (overlay) {

                overlay.addEventListener('touchmove', function (e) {
                    e.preventDefault();
                }, { passive: false });
            }

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    const sidebar = document.getElementById('admin-sidebar');
                    if (sidebar && sidebar.classList.contains('open')) {
                        closeAdminSidebar();
                    }
                }
            });

            window.addEventListener('resize', function () {
                if (window.innerWidth >= 1024) {
                    const sidebar = document.getElementById('admin-sidebar');
                    if (sidebar) {
                        sidebar.classList.remove('open');
                        document.getElementById('admin-sidebar-overlay')?.classList.remove('visible');
                    }

                    if (document.body.classList.contains('sidebar-open')) {
                        _unlockBodyScroll();
                    }
                }
            });
        });
    </script>
</body>
</html>
