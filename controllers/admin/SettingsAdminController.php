<?php

class SettingsAdminController extends Controller {

    public static function getSettingDefinitions(): array {
        return [
            'general' => [
                'name'        => 'Umum & Website',
                'icon'        => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>',
                'description' => 'Identitas website, informasi kontak resmi, dan mode pemeliharaan sistem.',
                'items'       => [
                    'site_name' => [
                        'label'       => 'Nama Website / Platform',
                        'type'        => 'text',
                        'description' => 'Nama platform yang tampil pada judul halaman, navigasi, dan email.',
                        'placeholder' => 'Contoh: Jagantara',
                        'required'    => true,
                    ],
                    'site_tagline' => [
                        'label'       => 'Tagline & Slogan',
                        'type'        => 'text',
                        'description' => 'Kalimat slogan penjelas di bawah nama website.',
                        'placeholder' => 'Contoh: Platform Pelaporan dan Aksi Lingkungan',
                        'required'    => false,
                    ],
                    'site_description' => [
                        'label'       => 'Deskripsi Website',
                        'type'        => 'textarea',
                        'description' => 'Ringkasan platform untuk meta description SEO dan footer.',
                        'placeholder' => 'Tuliskan deskripsi platform...',
                        'required'    => false,
                    ],
                    'site_contact_email' => [
                        'label'       => 'Email Kontak Resmi',
                        'type'        => 'email',
                        'description' => 'Alamat email bantuan yang dapat dihubungi oleh masyarakat.',
                        'placeholder' => 'info@jagantara.id',
                        'required'    => true,
                    ],
                    'site_contact_phone' => [
                        'label'       => 'Nomor Telepon / WhatsApp',
                        'type'        => 'text',
                        'description' => 'Kontak telepon atau nomor layanan aduan publik.',
                        'placeholder' => '+62 21 0000 0000',
                        'required'    => false,
                    ],
                    'maintenance_mode' => [
                        'label'       => 'Mode Pemeliharaan (Maintenance)',
                        'type'        => 'toggle',
                        'description' => 'Jika diaktifkan, halaman publik akan menampilkan pesan pemeliharaan. Hanya Administrator yang dapat login dan mengakses dashboard.',
                        'activeLabel' => 'Sistem Aktif Normal',
                        'disabledLabel' => 'Sistem Dalam Maintenance',
                    ],
                ]
            ],
            'auth' => [
                'name'        => 'Autentikasi & Registrasi',
                'icon'        => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>',
                'description' => 'Pengaturan izin pendaftaran akun baru pengguna dan pendaftaran gabung ke organizer.',
                'items'       => [
                    'registration_enabled' => [
                        'label'         => 'Pendaftaran Akun Baru (User Registration)',
                        'type'          => 'toggle',
                        'description'   => 'Mengizinkan warga mendaftar akun baru. Jika dinonaktifkan, formulir pendaftaran ditutup dan hanya pengguna terdaftar yang dapat masuk.',
                        'activeLabel'   => 'Pendaftaran Terbuka',
                        'disabledLabel' => 'Pendaftaran Ditutup',
                    ],
                    'organizer_registration_enabled' => [
                        'label'         => 'Pendaftaran Gabung Organisasi (Join Organizer)',
                        'type'          => 'toggle',
                        'description'   => 'Mengizinkan anggota mengajukan pendaftaran bergabung ke komunitas/organizer. Jika dinonaktifkan, tombol pendaftaran dinonaktifkan.',
                        'activeLabel'   => 'Rekrutmen Dibuka',
                        'disabledLabel' => 'Rekrutmen Ditutup',
                    ],
                ]
            ],
            'reports' => [
                'name'        => 'Pelaporan Lingkungan',
                'icon'        => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>',
                'description' => 'Batasan jumlah unggahan foto bukti kondisi lingkungan dan paginasi laporan.',
                'items'       => [
                    'max_report_photos' => [
                        'label'       => 'Maksimal Foto per Laporan',
                        'type'        => 'number',
                        'unit'        => 'Foto',
                        'min'         => 1,
                        'max'         => 30,
                        'description' => 'Batas maksimal foto bukti yang dapat dilampirkan warga dalam satu pengaduan.',
                    ],
                    'max_photo_size_mb' => [
                        'label'       => 'Batas Ukuran Foto (MB)',
                        'type'        => 'number',
                        'unit'        => 'MB',
                        'min'         => 1,
                        'max'         => 50,
                        'description' => 'Ukuran file maksimal per gambar yang diizinkan untuk diunggah.',
                    ],
                    'reports_per_page' => [
                        'label'       => 'Jumlah Laporan per Halaman',
                        'type'        => 'number',
                        'unit'        => 'Laporan',
                        'min'         => 1,
                        'max'         => 100,
                        'description' => 'Banyaknya data laporan yang ditampilkan per halaman pada katalog publik.',
                    ],
                ]
            ],
            'security' => [
                'name'        => 'Keamanan & Rate Limiting',
                'icon'        => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>',
                'description' => 'Proteksi brute force login dan pembatasan pembuatan multi-akun per IP/perangkat.',
                'items'       => [
                    'login_rate_limit' => [
                        'label'       => 'Maks. Percobaan Gagal Login',
                        'type'        => 'number',
                        'unit'        => 'Kali',
                        'min'         => 1,
                        'max'         => 50,
                        'description' => 'Jumlah toleransi password salah sebelum akses IP/email diblokir sementara.',
                    ],
                    'login_rate_window_minutes' => [
                        'label'       => 'Jendela Waktu Blokir Login',
                        'type'        => 'number',
                        'unit'        => 'Menit',
                        'min'         => 1,
                        'max'         => 1440,
                        'description' => 'Durasi waktu pemblokiran sementara untuk percobaan login yang gagal.',
                    ],
                    'register_rate_limit' => [
                        'label'       => 'Maks. Akun per Perangkat/IP',
                        'type'        => 'number',
                        'unit'        => 'Akun',
                        'min'         => 1,
                        'max'         => 20,
                        'description' => 'Maksimal akun yang dapat dibuat dari satu perangkat atau jaringan dalam periode tertentu.',
                    ],
                    'register_rate_window_days' => [
                        'label'       => 'Jendela Waktu Rate Limit Register',
                        'type'        => 'number',
                        'unit'        => 'Hari',
                        'min'         => 1,
                        'max'         => 365,
                        'description' => 'Periode hitungan batasan pendaftaran akun per perangkat.',
                    ],
                ]
            ],
            'display' => [
                'name'        => 'Tampilan & Slider',
                'icon'        => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>',
                'description' => 'Parameter visual animasi carousel banner dan antarmuka beranda.',
                'items'       => [
                    'banner_autoplay_delay' => [
                        'label'       => 'Delay Transisi Slider Banner',
                        'type'        => 'number',
                        'unit'        => 'Milidetik (ms)',
                        'min'         => 1000,
                        'max'         => 30000,
                        'step'        => 500,
                        'description' => 'Kecepatan perpindahan slide otomatis pada banner beranda (5000 ms = 5 detik).',
                    ],
                ]
            ],
            'map' => [
                'name'        => 'Peta & Geografis',
                'icon'        => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>',
                'description' => 'Titik koordinat pusat dan kedalaman zoom default untuk peta interaktif.',
                'items'       => [
                    'map_default_lat' => [
                        'label'       => 'Latitude Default Peta',
                        'type'        => 'text',
                        'description' => 'Koordinat garis lintang default (Contoh: -6.2088 untuk Jakarta).',
                        'placeholder' => '-6.2088',
                    ],
                    'map_default_lng' => [
                        'label'       => 'Longitude Default Peta',
                        'type'        => 'text',
                        'description' => 'Koordinat garis bujur default (Contoh: 106.8456 untuk Jakarta).',
                        'placeholder' => '106.8456',
                    ],
                    'map_default_zoom' => [
                        'label'       => 'Zoom Default Peta',
                        'type'        => 'number',
                        'unit'        => 'Level Zoom',
                        'min'         => 1,
                        'max'         => 19,
                        'description' => 'Tingkat pembesaran awal peta (skala 1-19, standar wilayah perkotaan: 10-12).',
                    ],
                ]
            ]
        ];
    }

    public function index(): void {
        $this->requireRole(ROLE_ADMIN);

        $db = Database::getConnection();
        $stmt = $db->query('SELECT * FROM system_settings ORDER BY id ASC');
        $rawSettings = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $settingsMap = [];
        foreach ($rawSettings as $s) {
            $settingsMap[$s['key']] = $s;
        }

        $definitions = self::getSettingDefinitions();
        $activeTab = trim((string)$this->input('tab', 'all'));

        $this->view('admin.settings.index', [
            'pageTitle'    => 'Pengaturan Sistem - ' . setting('site_name', APP_NAME),
            'adminTitle'   => 'Pengaturan Sistem & Parameter Global',
            'definitions'  => $definitions,
            'settingsMap'  => $settingsMap,
            'activeTab'    => $activeTab,
        ], 'admin');
    }

    public function update(): void {
        $this->requireRole(ROLE_ADMIN);
        $this->validateCsrf();

        $input = $this->allInput();
        $db = Database::getConnection();
        $definitions = self::getSettingDefinitions();
        $targetCategory = trim((string)$this->input('_target_category', ''));

        $allDefinedKeys = [];
        $toggleKeys = [];
        foreach ($definitions as $groupKey => $groupData) {
            if (!empty($targetCategory) && $targetCategory !== 'all' && $targetCategory !== $groupKey) {
                continue;
            }
            foreach ($groupData['items'] as $itemKey => $itemDef) {
                $allDefinedKeys[$itemKey] = $itemDef;
                if ($itemDef['type'] === 'toggle') {
                    $toggleKeys[] = $itemKey;
                }
            }
        }

        foreach ($toggleKeys as $tKey) {
            if (!isset($input[$tKey])) {
                $input[$tKey] = '0';
            }
        }

        $stmt = $db->prepare('UPDATE system_settings SET `value` = ? WHERE `key` = ?');
        $insertStmt = $db->prepare('INSERT INTO system_settings (`key`, `value`, `label`, `group`) VALUES (?, ?, ?, ?)');
        $checkStmt = $db->prepare('SELECT COUNT(*) FROM system_settings WHERE `key` = ?');

        $updatedCount = 0;

        foreach ($allDefinedKeys as $key => $def) {
            if (!array_key_exists($key, $input)) continue;

            $val = trim((string)$input[$key]);

            if ($def['type'] === 'toggle') {
                $val = ($val === '1' || $val === 'true' || $val === 'on') ? '1' : '0';
            } elseif ($def['type'] === 'number') {
                $num = (int)$val;
                if (isset($def['min']) && $num < $def['min']) $num = $def['min'];
                if (isset($def['max']) && $num > $def['max']) $num = $def['max'];
                $val = (string)$num;
            } elseif ($def['type'] === 'email') {
                if (!filter_var($val, FILTER_VALIDATE_EMAIL)) {
                    $val = setting($key, 'info@jagantara.id');
                }
            }

            $checkStmt->execute([$key]);
            if ((int)$checkStmt->fetchColumn() > 0) {
                $stmt->execute([$val, $key]);
            } else {
                $insertStmt->execute([$key, $val, $def['label'], $def['group'] ?? 'general']);
            }
            $updatedCount++;
        }

        clearSettingCache();

        $this->audit()->log('update_system_settings', 'system_settings', null, [
            'category' => $targetCategory ?: 'all',
            'updated_count' => $updatedCount
        ]);

        setFlash('success', 'Seluruh pengaturan sistem berhasil disimpan dan langsung diterapkan.');

        $redirectTab = $targetCategory ? '?tab=' . urlencode($targetCategory) : '';
        $this->redirect('admin/settings' . $redirectTab);
    }
}
