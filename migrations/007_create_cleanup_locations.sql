
CREATE TABLE IF NOT EXISTS cleanup_locations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    address TEXT NOT NULL,
    latitude DECIMAL(10, 8) NULL,
    longitude DECIMAL(11, 8) NULL,
    status ENUM('pending', 'verified', 'in_progress', 'completed') NOT NULL DEFAULT 'pending',
    description TEXT NULL,
    admin_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_created_at (created_at),
    INDEX idx_admin_id (admin_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
