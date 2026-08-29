<?php

if (php_sapi_name() !== 'cli') {
    http_response_code(403);
    die('This script can only be run from the command line.');
}

require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/database.php';

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';charset=' . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',
        ]
    );
} catch (PDOException $e) {
    die("ERROR: Cannot connect to MySQL.\n" . $e->getMessage() . "\n");
}

$sqlFiles = [
    '001_create_tables.sql',
    '002_seed_categories.sql',
    '003_seed_regions.sql',
    '005_seed_defaults.sql',
    '006_add_custom_category.sql',
    '007_create_cleanup_locations.sql',
];

foreach ($sqlFiles as $file) {
    $path = __DIR__ . '/migrations/' . $file;
    if (!file_exists($path)) {
        continue;
    }

    try {
        $sql = file_get_contents($path);

        $lines = explode("\n", $sql);
        $cleanLines = [];
        foreach ($lines as $line) {
            $trimmed = trim($line);
            if (!str_starts_with($trimmed, '--') && !str_starts_with($trimmed, '/*') && !str_starts_with($trimmed, '#')) {
                $cleanLines[] = $line;
            }
        }
        $cleanSql = implode("\n", $cleanLines);

        $statements = array_filter(
            array_map('trim', explode(';', $cleanSql)),
            fn($s) => !empty($s)
        );
        foreach ($statements as $statement) {
            if (trim($statement)) {
                $pdo->exec($statement);
            }
        }
    } catch (PDOException $e) {

    }
}

$phpSeeders = [
    '004_seed_admin.php',
];

try {
    $db = new PDO(
        'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die("ERROR: Cannot connect to database '" . DB_NAME . "'.\n" . $e->getMessage() . "\n");
}

foreach ($phpSeeders as $file) {
    $path = __DIR__ . '/migrations/' . $file;
    if (!file_exists($path)) {
        continue;
    }

    try {
        $runFromMigrate = true;
        $seeder = require $path;
        if (is_callable($seeder)) {
            $seeder($db);
        }
    } catch (Exception $e) {

    }
}

$uploadDirs = [
    'reports',
    'profiles',
    'banners',
    'ktp',
    'organizers',
    'activities',
];

$uploadsBase = __DIR__ . '/public/uploads';
foreach ($uploadDirs as $dir) {
    $fullPath = $uploadsBase . '/' . $dir;
    if (!is_dir($fullPath)) {
        mkdir($fullPath, 0755, true);
    }
}

$htaccessContent = "Options -Indexes\n"
    . "<FilesMatch \"\.(php|php3|php4|php5|php7|php8|phtml|phar|asp|aspx|cgi|pl|py|sh|exe|bat|cmd)$\">\n"
    . "    Require all denied\n"
    . "</FilesMatch>\n";

$htaccessPath = $uploadsBase . '/.htaccess';
if (!file_exists($htaccessPath)) {
    file_put_contents($htaccessPath, $htaccessContent);
}
