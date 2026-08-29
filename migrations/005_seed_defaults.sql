

USE `jagantara_v2`;

INSERT IGNORE INTO `theme_settings` (`key`, `value`) VALUES
('color_primary',    '#1D4533'),
('color_secondary',  '#5E3122'),
('color_accent1',    '#F7EAE0'),
('color_accent2',    '#F9D2BA'),
('color_surface',    '#FAFAF8'),
('color_text',       '#1A1A1A'),
('color_muted',      '#6B7280'),
('color_success',    '#16A34A'),
('color_warning',    '#D97706'),
('color_danger',     '#DC2626'),
('color_info',       '#0EA5E9'),
('color_border',     '#E5E7EB'),
('color_background', '#F9FAFB'),
('font_family',      'Inter, sans-serif');

INSERT IGNORE INTO `system_settings` (`key`, `value`, `label`, `group`) VALUES
('site_name',                  'Jagantara',                                        'Nama Website',                  'general'),
('site_tagline',               'Platform Pelaporan dan Aksi Lingkungan',           'Tagline Website',               'general'),
('site_description',           'Platform digital berbasis komunitas untuk pelaporan permasalahan lingkungan dan pengorganisasian aksi lingkungan di JABODETABEK.', 'Deskripsi Website', 'general'),
('site_contact_email',         'info@jagantara.id',                               'Email Kontak',                  'general'),
('site_contact_phone',         '+62 21 0000 0000',                               'Telepon Kontak',                'general'),
('max_report_photos',          '10',                                              'Maksimal Foto per Laporan',     'reports'),
('max_photo_size_mb',          '5',                                               'Ukuran Maksimal Foto (MB)',     'reports'),
('reports_per_page',           '12',                                              'Laporan per Halaman',           'reports'),
('registration_enabled',           '1',                                               'Pendaftaran Akun Baru (User)',  'auth'),
('organizer_registration_enabled', '1',                                               'Pendaftaran Gabung Organisasi', 'auth'),
('login_rate_limit',           '5',                                               'Maks. Percobaan Login',         'security'),
('login_rate_window_minutes',  '15',                                              'Jendela Rate Limit Login (menit)', 'security'),
('register_rate_limit',        '2',                                               'Maks. Akun per Perangkat',      'security'),
('register_rate_window_days',  '7',                                               'Jendela Rate Limit Register (hari)', 'security'),
('maintenance_mode',           '0',                                               'Mode Maintenance',              'general'),
('banner_autoplay_delay',      '5000',                                            'Delay Banner (ms)',             'display'),
('map_default_lat',            '-6.2088',                                         'Latitude Default Peta',         'map'),
('map_default_lng',            '106.8456',                                        'Longitude Default Peta',        'map'),
('map_default_zoom',           '11',                                              'Zoom Default Peta',             'map');

INSERT IGNORE INTO `organizers` (`id`, `name`, `slug`, `description`, `region_id`, `is_active`) VALUES
(1, 'Jagantara Community', 'jagantara-community', 'Komunitas utama Jagantara untuk aksi lingkungan di JABODETABEK.', 1, 1);

INSERT IGNORE INTO `banners` (`title`, `subtitle`, `image_path`, `link_url`, `is_active`, `order`) VALUES
('Laporkan Masalah Lingkungan', 'Bersama kita jaga lingkungan JABODETABEK. Laporkan masalah di sekitar Anda sekarang.', 'banners/default_banner_1.jpg', NULL, 1, 1),
('Bergabung dengan Organizer', 'Jadilah bagian dari gerakan lingkungan. Bergabung dengan organizer terdekat Anda.', 'banners/default_banner_2.jpg', '/organizers', 1, 2),
('Lihat Peta Laporan', 'Temukan masalah lingkungan di sekitar Anda melalui peta interaktif.', 'banners/default_banner_3.jpg', '/map', 1, 3);
