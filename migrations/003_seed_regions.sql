

USE `jagantara_v2`;

INSERT IGNORE INTO `regions` (`id`, `name`, `slug`, `parent_id`, `is_active`, `order`) VALUES
(1,  'Jakarta',   'jakarta',   NULL, 1, 1),
(2,  'Bogor',     'bogor',     NULL, 1, 2),
(3,  'Depok',     'depok',     NULL, 1, 3),
(4,  'Tangerang', 'tangerang', NULL, 1, 4),
(5,  'Bekasi',    'bekasi',    NULL, 1, 5);

INSERT IGNORE INTO `regions` (`name`, `slug`, `parent_id`, `is_active`, `order`) VALUES
('Jakarta Pusat',   'jakarta-pusat',   1, 1, 1),
('Jakarta Utara',   'jakarta-utara',   1, 1, 2),
('Jakarta Barat',   'jakarta-barat',   1, 1, 3),
('Jakarta Selatan', 'jakarta-selatan', 1, 1, 4),
('Jakarta Timur',   'jakarta-timur',   1, 1, 5);

INSERT IGNORE INTO `regions` (`name`, `slug`, `parent_id`, `is_active`, `order`) VALUES
('Bogor Kota',    'bogor-kota',    2, 1, 1),
('Bogor Utara',   'bogor-utara',   2, 1, 2),
('Bogor Selatan', 'bogor-selatan', 2, 1, 3),
('Bogor Timur',   'bogor-timur',   2, 1, 4),
('Bogor Barat',   'bogor-barat',   2, 1, 5),
('Bogor Tengah',  'bogor-tengah',  2, 1, 6),
('Cibinong',      'cibinong',      2, 1, 7),
('Citeureup',     'citeureup',     2, 1, 8),
('Jonggol',       'jonggol',       2, 1, 9),
('Leuwiliang',    'leuwiliang',    2, 1, 10);

INSERT IGNORE INTO `regions` (`name`, `slug`, `parent_id`, `is_active`, `order`) VALUES
('Beji',           'beji',           3, 1, 1),
('Bojongsari',     'bojongsari',     3, 1, 2),
('Cilodong',       'cilodong',       3, 1, 3),
('Cimanggis',      'cimanggis',      3, 1, 4),
('Cinere',         'cinere',         3, 1, 5),
('Cipayung',       'cipayung',       3, 1, 6),
('Limo',           'limo',           3, 1, 7),
('Pancoran Mas',   'pancoran-mas',   3, 1, 8),
('Sawangan',       'sawangan',       3, 1, 9),
('Sukmajaya',      'sukmajaya',      3, 1, 10),
('Tapos',          'tapos',          3, 1, 11);

INSERT IGNORE INTO `regions` (`name`, `slug`, `parent_id`, `is_active`, `order`) VALUES
('Tangerang Kota',   'tangerang-kota',   4, 1, 1),
('Tangerang Selatan','tangerang-selatan', 4, 1, 2),
('Tangerang Kab',    'tangerang-kab',    4, 1, 3),
('Ciledug',          'ciledug',          4, 1, 4),
('Ciputat',          'ciputat',          4, 1, 5),
('Serpong',          'serpong',          4, 1, 6),
('Pamulang',         'pamulang',         4, 1, 7),
('Balaraja',         'balaraja',         4, 1, 8),
('Tigaraksa',        'tigaraksa',        4, 1, 9);

INSERT IGNORE INTO `regions` (`name`, `slug`, `parent_id`, `is_active`, `order`) VALUES
('Bekasi Kota',   'bekasi-kota',   5, 1, 1),
('Bekasi Timur',  'bekasi-timur',  5, 1, 2),
('Bekasi Barat',  'bekasi-barat',  5, 1, 3),
('Bekasi Utara',  'bekasi-utara',  5, 1, 4),
('Bekasi Selatan','bekasi-selatan',5, 1, 5),
('Cikarang',      'cikarang',      5, 1, 6),
('Cibitung',      'cibitung',      5, 1, 7),
('Tarumajaya',    'tarumajaya',    5, 1, 8),
('Tambun',        'tambun',        5, 1, 9),
('Cibarusah',     'cibarusah',     5, 1, 10);
