

USE `jagantara_v2`;

INSERT IGNORE INTO `categories` (`name`, `slug`, `description`, `icon`, `color`, `is_active`, `order`) VALUES
('Sampah',             'sampah',             'Laporan terkait sampah yang berserakan atau tidak terkelola.',                    'trash',           '#EF4444', 1, 1),
('Sampah Menumpuk',    'sampah-menumpuk',    'Laporan terkait penumpukan sampah dalam volume besar.',                          'archive',         '#F97316', 1, 2),
('Lingkungan Kotor',   'lingkungan-kotor',   'Laporan area atau lingkungan yang kotor dan membutuhkan pembersihan.',           'exclamation',     '#EAB308', 1, 3),
('Pencemaran',         'pencemaran',         'Laporan pencemaran air, udara, atau tanah oleh limbah atau polutan.',            'beaker',          '#8B5CF6', 1, 4),
('Kerja Bakti',        'kerja-bakti',        'Permintaan atau inisiasi kegiatan kerja bakti membersihkan lingkungan.',         'users',           '#10B981', 1, 5),
('Drainase',           'drainase',           'Laporan saluran air atau drainase yang tersumbat atau rusak.',                   'arrow-down',      '#3B82F6', 1, 6),
('Pohon dan Tanaman',  'pohon-dan-tanaman',  'Laporan terkait pohon tumbang, ranting berbahaya, atau kebutuhan penghijauan.', 'tree',            '#22C55E', 1, 7),
('Fasilitas Umum',     'fasilitas-umum',     'Laporan kerusakan fasilitas umum seperti bangku taman, lampu, atau pagar.',     'building',        '#64748B', 1, 8),
('Air dan Sungai',     'air-dan-sungai',     'Laporan kondisi sungai, danau, atau badan air yang tercemar atau berbahaya.',   'water',           '#0EA5E9', 1, 9),
('Lainnya',            'lainnya',            'Laporan lingkungan yang tidak termasuk kategori di atas.',                      'dots-horizontal', '#9CA3AF', 1, 10);
