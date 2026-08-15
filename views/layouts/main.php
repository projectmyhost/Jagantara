<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? APP_NAME . ' - ' . APP_TAGLINE) ?></title>
    <meta name="description" content="<?= e($metaDescription ?? 'Platform pelaporan dan aksi lingkungan berbasis masyarakat di JABODETABEK.') ?>">
    <meta name="theme-color" content="<?= theme('color_primary', '#1D4533') ?>">
    <link rel="canonical" href="<?= e(url(ltrim(currentPath(), '/'))) ?>">

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
    $curPath = currentPath();
    $pageNeedsMap = $needsMap ?? (
        $curPath === '/map' ||
        (str_starts_with($curPath, '/reports/') && !in_array($curPath, ['/reports', '/reports/create', '/reports/status'], true)) ||
        str_starts_with($curPath, '/admin/map')
    );
    ?>
    <?php if ($pageNeedsMap): ?>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <?php endif; ?>

    <link rel="stylesheet" href="<?= url('public/css/app.css') ?>">
</head>
<body class="min-h-screen flex flex-col bg-[#F9FAFB] text-[#1A1A1A]"<?php if (isLoggedIn()): ?> data-user-id="<?= e((int)currentUserId()) ?>" data-check-status-url="<?= url('auth/check-status') ?>" data-logout-url="<?= url('auth/logout') ?>" data-login-url="<?= url('auth/login') ?>" data-contact-email="<?= e(setting('site_contact_email', 'info@jagantara.id')) ?>"<?php endif; ?>>

    <?php require __DIR__ . '/partials/navbar.php'; ?>

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

    <main class="flex-grow" id="main-content">
        <?= $content ?>
    </main>

    <?php require __DIR__ . '/partials/footer.php'; ?>

    <?php require __DIR__ . '/partials/modals.php'; ?>

    <?php if ($pageNeedsMap): ?>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <?php endif; ?>
    <script src="<?= url('public/js/app.js') ?>" defer></script>
    <script src="<?= url('public/js/geolocation.js') ?>"></script>

    <?php if (isLoggedIn()): ?>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const badge = document.getElementById('navbar-notif-badge');
        const notifBtn = document.getElementById('navbar-notif-btn');
        if (!badge) return;

        function updateNotifBadge() {
            fetch('<?= url('api/notifications/count') ?>', { credentials: 'same-origin' })
                .then(r => r.json())
                .then(data => {
                    if (!data.success) return;
                    const count = data.count || 0;
                    if (count > 0) {
                        badge.textContent = count > 99 ? '99+' : count;
                        badge.style.display = 'flex';
                    } else {
                        badge.style.display = 'none';
                    }
                })
                .catch(() => {
                    badge.style.display = 'none';
                });
        }

        updateNotifBadge();
        setInterval(updateNotifBadge, 60000);

        if (notifBtn) {
            notifBtn.addEventListener('click', function () {
                badge.style.display = 'none';
            });
        }
    });
    </script>
    <?php endif; ?>
</body>
</html>
