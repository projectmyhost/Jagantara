

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO,STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION';

CREATE DATABASE IF NOT EXISTS `jagantara_v2`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `jagantara_v2`;

CREATE TABLE IF NOT EXISTS `users` (
    `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `email`         VARCHAR(255) NOT NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `role`          ENUM('user','organizer_member','admin') NOT NULL DEFAULT 'user',
    `status`        ENUM('active','suspended','banned') NOT NULL DEFAULT 'active',
    `email_verified_at` DATETIME DEFAULT NULL,
    `created_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_users_email` (`email`),
    KEY `idx_users_role` (`role`),
    KEY `idx_users_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `profiles` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`     INT UNSIGNED NOT NULL,
    `username`    VARCHAR(50) DEFAULT NULL,
    `full_name`   VARCHAR(150) DEFAULT NULL,
    `phone`       VARCHAR(20) DEFAULT NULL,
    `address`     TEXT DEFAULT NULL,
    `region_id`   INT UNSIGNED DEFAULT NULL,
    `birth_date`  DATE DEFAULT NULL,
    `gender`      ENUM('male','female','other') DEFAULT NULL,
    `avatar_path` VARCHAR(500) DEFAULT NULL,
    `is_complete` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_profiles_user_id` (`user_id`),
    UNIQUE KEY `uq_profiles_username` (`username`),
    KEY `idx_profiles_region` (`region_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `regions` (
    `id`        INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`      VARCHAR(100) NOT NULL,
    `slug`      VARCHAR(100) NOT NULL,
    `parent_id` INT UNSIGNED DEFAULT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `order`     INT UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_regions_slug` (`slug`),
    KEY `idx_regions_parent` (`parent_id`),
    KEY `idx_regions_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `categories` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`        VARCHAR(100) NOT NULL,
    `slug`        VARCHAR(100) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `icon`        VARCHAR(100) DEFAULT NULL,
    `color`       VARCHAR(20) DEFAULT NULL,
    `is_active`   TINYINT(1) NOT NULL DEFAULT 1,
    `order`       INT UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_categories_slug` (`slug`),
    KEY `idx_categories_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `reports` (
    `id`               INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`          INT UNSIGNED NOT NULL,
    `title`            VARCHAR(255) NOT NULL,
    `category_id`      INT UNSIGNED NOT NULL,
    `description`      TEXT NOT NULL,
    `region_id`        INT UNSIGNED NOT NULL,
    `location_name`    VARCHAR(255) DEFAULT NULL,
    `address`          TEXT DEFAULT NULL,
    `latitude`         DECIMAL(10,8) DEFAULT NULL,
    `longitude`        DECIMAL(11,8) DEFAULT NULL,
    `status`           ENUM('pending','verified','rejected','planning','scheduled','in_progress','completed')
                       NOT NULL DEFAULT 'pending',
    `rejection_reason` TEXT DEFAULT NULL,
    `is_public`        TINYINT(1) NOT NULL DEFAULT 1,
    `created_at`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at`       DATETIME DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_reports_user` (`user_id`),
    KEY `idx_reports_category` (`category_id`),
    KEY `idx_reports_region` (`region_id`),
    KEY `idx_reports_status` (`status`),
    KEY `idx_reports_deleted` (`deleted_at`),
    KEY `idx_reports_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `report_photos` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `report_id`  INT UNSIGNED NOT NULL,
    `file_path`  VARCHAR(500) NOT NULL,
    `is_primary` TINYINT(1) NOT NULL DEFAULT 0,
    `order`      INT UNSIGNED NOT NULL DEFAULT 0,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_report_photos_report` (`report_id`),
    KEY `idx_report_photos_primary` (`is_primary`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `report_status_history` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `report_id`  INT UNSIGNED NOT NULL,
    `status`     VARCHAR(50) NOT NULL,
    `notes`      TEXT DEFAULT NULL,
    `changed_by` INT UNSIGNED DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_rsh_report` (`report_id`),
    KEY `idx_rsh_changed_by` (`changed_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `organizers` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`        VARCHAR(150) NOT NULL,
    `slug`        VARCHAR(150) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `region_id`   INT UNSIGNED DEFAULT NULL,
    `logo_path`   VARCHAR(500) DEFAULT NULL,
    `address`     TEXT DEFAULT NULL,
    `contact_email` VARCHAR(255) DEFAULT NULL,
    `contact_phone` VARCHAR(20) DEFAULT NULL,
    `is_active`   TINYINT(1) NOT NULL DEFAULT 1,
    `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_organizers_slug` (`slug`),
    KEY `idx_organizers_region` (`region_id`),
    KEY `idx_organizers_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `organizer_applications` (
    `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`         INT UNSIGNED NOT NULL,
    `organizer_id`    INT UNSIGNED NOT NULL,
    `full_name`       VARCHAR(150) NOT NULL,
    `phone`           VARCHAR(20) NOT NULL,
    `address`         TEXT NOT NULL,
    `region_id`       INT UNSIGNED NOT NULL,
    `birth_date`      DATE DEFAULT NULL,
    `gender`          ENUM('male','female','other') DEFAULT NULL,
    `ktp_path`        VARCHAR(500) DEFAULT NULL,
    `face_photo_path` VARCHAR(500) DEFAULT NULL,
    `motivation`      TEXT DEFAULT NULL,
    `status`          ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
    `rejection_reason` TEXT DEFAULT NULL,
    `reviewed_by`     INT UNSIGNED DEFAULT NULL,
    `reviewed_at`     DATETIME DEFAULT NULL,
    `created_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_oa_user` (`user_id`),
    KEY `idx_oa_organizer` (`organizer_id`),
    KEY `idx_oa_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `organizer_members` (
    `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`      INT UNSIGNED NOT NULL,
    `organizer_id` INT UNSIGNED NOT NULL,
    `role`         ENUM('member','coordinator','admin') NOT NULL DEFAULT 'member',
    `joined_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `is_active`    TINYINT(1) NOT NULL DEFAULT 1,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_om_user_org` (`user_id`, `organizer_id`),
    KEY `idx_om_organizer` (`organizer_id`),
    KEY `idx_om_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `activities` (
    `id`               INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organizer_id`     INT UNSIGNED NOT NULL,
    `title`            VARCHAR(255) NOT NULL,
    `description`      TEXT NOT NULL,
    `region_id`        INT UNSIGNED NOT NULL,
    `location_name`    VARCHAR(255) DEFAULT NULL,
    `address`          TEXT DEFAULT NULL,
    `latitude`         DECIMAL(10,8) DEFAULT NULL,
    `longitude`        DECIMAL(11,8) DEFAULT NULL,
    `scheduled_at`     DATETIME DEFAULT NULL,
    `ends_at`          DATETIME DEFAULT NULL,
    `status`           ENUM('draft','published','ongoing','completed','cancelled') NOT NULL DEFAULT 'draft',
    `max_participants` INT UNSIGNED DEFAULT NULL,
    `cover_image`      VARCHAR(500) DEFAULT NULL,
    `created_at`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at`       DATETIME DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_activities_organizer` (`organizer_id`),
    KEY `idx_activities_region` (`region_id`),
    KEY `idx_activities_status` (`status`),
    KEY `idx_activities_scheduled` (`scheduled_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `activity_participants` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `activity_id` INT UNSIGNED NOT NULL,
    `user_id`     INT UNSIGNED NOT NULL,
    `status`      ENUM('registered','attended','absent') NOT NULL DEFAULT 'registered',
    `joined_at`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_ap_activity_user` (`activity_id`, `user_id`),
    KEY `idx_ap_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `forum_posts` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`     INT UNSIGNED NOT NULL,
    `title`       VARCHAR(255) NOT NULL,
    `content`     TEXT NOT NULL,
    `category_id` INT UNSIGNED DEFAULT NULL,
    `view_count`  INT UNSIGNED NOT NULL DEFAULT 0,
    `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at`  DATETIME DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_fp_user` (`user_id`),
    KEY `idx_fp_category` (`category_id`),
    KEY `idx_fp_deleted` (`deleted_at`),
    KEY `idx_fp_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `forum_comments` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `post_id`    INT UNSIGNED NOT NULL,
    `user_id`    INT UNSIGNED NOT NULL,
    `content`    TEXT NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `deleted_at` DATETIME DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_fc_post` (`post_id`),
    KEY `idx_fc_user` (`user_id`),
    KEY `idx_fc_deleted` (`deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `banners` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title`      VARCHAR(255) NOT NULL,
    `subtitle`   VARCHAR(500) DEFAULT NULL,
    `image_path` VARCHAR(500) NOT NULL,
    `link_url`   VARCHAR(500) DEFAULT NULL,
    `is_active`  TINYINT(1) NOT NULL DEFAULT 1,
    `order`      INT UNSIGNED NOT NULL DEFAULT 0,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_banners_active` (`is_active`),
    KEY `idx_banners_order` (`order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `notifications` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`    INT UNSIGNED NOT NULL,
    `type`       VARCHAR(50) NOT NULL,
    `title`      VARCHAR(255) NOT NULL,
    `message`    TEXT NOT NULL,
    `link`       VARCHAR(500) DEFAULT NULL,
    `is_read`    TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_notifications_user` (`user_id`),
    KEY `idx_notifications_read` (`is_read`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `audit_logs` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`     INT UNSIGNED DEFAULT NULL,
    `action`      VARCHAR(100) NOT NULL,
    `entity_type` VARCHAR(50) DEFAULT NULL,
    `entity_id`   INT UNSIGNED DEFAULT NULL,
    `details`     JSON DEFAULT NULL,
    `ip_address`  VARCHAR(45) DEFAULT NULL,
    `user_agent`  VARCHAR(500) DEFAULT NULL,
    `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_al_user` (`user_id`),
    KEY `idx_al_action` (`action`),
    KEY `idx_al_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `security_logs` (
    `id`                 INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`            INT UNSIGNED DEFAULT NULL,
    `ip_address`         VARCHAR(45) DEFAULT NULL,
    `device_fingerprint` VARCHAR(64) DEFAULT NULL,
    `action`             VARCHAR(100) NOT NULL,
    `details`            TEXT DEFAULT NULL,
    `created_at`         DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_sl_ip` (`ip_address`),
    KEY `idx_sl_action` (`action`),
    KEY `idx_sl_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `login_attempts` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `ip_address` VARCHAR(45) NOT NULL,
    `email`      VARCHAR(255) NOT NULL,
    `success`    TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_la_ip_email` (`ip_address`, `email`),
    KEY `idx_la_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `registration_attempts` (
    `id`                 INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `ip_address`         VARCHAR(45) NOT NULL,
    `device_fingerprint` VARCHAR(64) NOT NULL,
    `user_id`            INT UNSIGNED DEFAULT NULL,
    `created_at`         DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_ra_ip_device` (`ip_address`, `device_fingerprint`),
    KEY `idx_ra_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `theme_settings` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `key`        VARCHAR(100) NOT NULL,
    `value`      VARCHAR(500) NOT NULL,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_theme_key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `system_settings` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `key`        VARCHAR(100) NOT NULL,
    `value`      TEXT NOT NULL,
    `label`      VARCHAR(150) DEFAULT NULL,
    `group`      VARCHAR(50) DEFAULT NULL,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_ss_key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `profiles`
    ADD CONSTRAINT `fk_profiles_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `fk_profiles_region` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE SET NULL;

ALTER TABLE `reports`
    ADD CONSTRAINT `fk_reports_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `fk_reports_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT,
    ADD CONSTRAINT `fk_reports_region` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE RESTRICT;

ALTER TABLE `report_photos`
    ADD CONSTRAINT `fk_rp_report` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE;

ALTER TABLE `report_status_history`
    ADD CONSTRAINT `fk_rsh_report` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `fk_rsh_user` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

ALTER TABLE `organizers`
    ADD CONSTRAINT `fk_org_region` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE SET NULL;

ALTER TABLE `organizer_applications`
    ADD CONSTRAINT `fk_oa_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `fk_oa_organizer` FOREIGN KEY (`organizer_id`) REFERENCES `organizers` (`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `fk_oa_region` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE RESTRICT,
    ADD CONSTRAINT `fk_oa_reviewer` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

ALTER TABLE `organizer_members`
    ADD CONSTRAINT `fk_om_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `fk_om_organizer` FOREIGN KEY (`organizer_id`) REFERENCES `organizers` (`id`) ON DELETE CASCADE;

ALTER TABLE `activities`
    ADD CONSTRAINT `fk_act_organizer` FOREIGN KEY (`organizer_id`) REFERENCES `organizers` (`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `fk_act_region` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE RESTRICT;

ALTER TABLE `activity_participants`
    ADD CONSTRAINT `fk_ap_activity` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `fk_ap_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `forum_posts`
    ADD CONSTRAINT `fk_fp_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `fk_fp_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

ALTER TABLE `forum_comments`
    ADD CONSTRAINT `fk_fc_post` FOREIGN KEY (`post_id`) REFERENCES `forum_posts` (`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `fk_fc_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `notifications`
    ADD CONSTRAINT `fk_notif_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

SET FOREIGN_KEY_CHECKS = 1;
