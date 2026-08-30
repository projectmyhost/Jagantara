<?php

class ThemeAdminController extends Controller {

    private const DEFAULT_THEME = [

        'color_primary'          => '#1D4533',
        'color_secondary'        => '#5E3122',
        'color_accent1'          => '#F7EAE0',
        'color_accent2'          => '#F9D2BA',

        'color_primary_dark'     => '#14301F',
        'color_primary_light'    => '#2A6349',
        'color_secondary_light'  => '#7A4535',

        'color_surface'          => '#FAFAF8',
        'color_text'             => '#1A1A1A',
        'color_muted'            => '#6B7280',
        'color_border'           => '#E5E7EB',
        'color_background'       => '#F9FAFB',

        'color_success'          => '#16A34A',
        'color_warning'          => '#D97706',
        'color_danger'           => '#DC2626',
        'color_info'             => '#0EA5E9',
    ];

    public function index(): void {
        $this->requireRole(ROLE_ADMIN);

        $db = Database::getConnection();
        $stmt = $db->query('SELECT `key`, `value` FROM theme_settings');
        $currentTheme = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

        $mergedTheme = array_merge(self::DEFAULT_THEME, $currentTheme);

        $this->view('admin.theme.index', [
            'pageTitle'    => 'Kustomisasi Tema Website - ' . APP_NAME,
            'adminTitle'   => 'Kustomisasi Tema Website',
            'currentTheme' => $mergedTheme,
            'defaultTheme' => self::DEFAULT_THEME,
        ], 'admin');
    }

    public function update(): void {
        $this->requireRole(ROLE_ADMIN);
        $this->validateCsrf();

        $input = $this->allInput();
        $db = Database::getConnection();

        $allowedKeys = array_keys(self::DEFAULT_THEME);

        $stmt = $db->prepare(
            'INSERT INTO theme_settings (`key`, `value`) VALUES (?, ?)
             ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), `updated_at` = NOW()'
        );

        foreach ($allowedKeys as $key) {
            if (!empty($input[$key])) {
                $val = trim($input[$key]);

                if (preg_match('/^#[a-fA-F0-9]{6}$/', $val) || preg_match('/^#[a-fA-F0-9]{3}$/', $val)) {
                    $stmt->execute([$key, $val]);
                }
            }
        }

        clearThemeCache();

        $this->audit()->log('update_theme', 'theme_settings', null);

        setFlash('success', 'Tema website berhasil diperbarui. Perubahan warna aktif di seluruh halaman.');
        $this->redirect('admin/theme');
    }

    public function reset(): void {
        $this->requireRole(ROLE_ADMIN);
        $this->validateCsrf();

        $db = Database::getConnection();
        $stmt = $db->prepare(
            'INSERT INTO theme_settings (`key`, `value`) VALUES (?, ?)
             ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), `updated_at` = NOW()'
        );

        foreach (self::DEFAULT_THEME as $key => $val) {
            $stmt->execute([$key, $val]);
        }

        clearThemeCache();

        $this->audit()->log('reset_theme', 'theme_settings', null);

        setFlash('success', 'Tema berhasil direset ke warna standar Jagantara (Forest Green & Earthy Brown).');
        $this->redirect('admin/theme');
    }
}
