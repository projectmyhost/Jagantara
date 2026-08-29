<?php

if (php_sapi_name() !== 'cli' && !isset($runFromMigrate)) {
    http_response_code(403);
    die('Access denied.');
}

return function(PDO $db): void {

    $stmt = $db->prepare('SELECT COUNT(*) FROM users WHERE email = ?');
    $stmt->execute(['diazrazirafi@organizer.com']);
    if ((int)$stmt->fetchColumn() > 0) {
        return;
    }

    $passwordHash = password_hash('BlueeDRYT123', PASSWORD_BCRYPT, ['cost' => 12]);

    $stmt = $db->prepare(
        'INSERT INTO users (email, password_hash, role, status, email_verified_at, created_at)
         VALUES (?, ?, ?, ?, NOW(), NOW())'
    );
    $stmt->execute(['diazrazirafi@organizer.com', $passwordHash, 'admin', 'active']);
    $adminId = (int)$db->lastInsertId();

    $stmt = $db->prepare(
        'INSERT INTO profiles (user_id, username, full_name, is_complete, created_at)
         VALUES (?, ?, ?, ?, NOW())'
    );
    $stmt->execute([$adminId, 'admin_jagantara', 'Admin Jagantara', 0]);
};
