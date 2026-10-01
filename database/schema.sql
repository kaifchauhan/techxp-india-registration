-- ====================================================
-- TechXP India Summit 2026 - Database Schema & Initial Data
-- CodeIgniter 4 Application Database
-- Compatible with MySQL 5.7+ / 8.0+ / MariaDB 10.3+
-- ====================================================

CREATE DATABASE IF NOT EXISTS `techxp_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `techxp_db`;

-- CodeIgniter sessions use the file handler (writable/session), so no session table is required.

SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------------------------------
-- Table structure for table `registrations`
-- ----------------------------------------------------
DROP TABLE IF EXISTS `registrations`;
CREATE TABLE `registrations` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `registration_id` VARCHAR(32) NOT NULL,
  `full_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(120) NOT NULL,
  `mobile` CHAR(10) NOT NULL COMMENT 'Application validates that all 10 characters are digits',
  `company` VARCHAR(100) DEFAULT NULL,
  `designation` VARCHAR(100) DEFAULT NULL,
  `city` VARCHAR(100) NOT NULL,
  `country` VARCHAR(100) NOT NULL,
  `session_preference` ENUM('Main Session','Workshop','Both') DEFAULT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `consent` TINYINT(1) NOT NULL DEFAULT 0,
  `status` ENUM('confirmed','attended','cancelled') NOT NULL DEFAULT 'confirmed',
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `check_in_time` DATETIME DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_registration_id` (`registration_id`),
  UNIQUE KEY `idx_email` (`email`),
  UNIQUE KEY `idx_mobile` (`mobile`),
  KEY `idx_status_created_at` (`status`, `created_at`),
  KEY `idx_country` (`country`),
  KEY `idx_session_country` (`session_preference`, `country`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------
-- Table structure for table `admins`
-- ----------------------------------------------------
DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `full_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(120) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `role` VARCHAR(30) NOT NULL DEFAULT 'admin',
  `last_login` DATETIME DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_username` (`username`),
  UNIQUE KEY `idx_admin_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------
-- Table structure for table `chat_messages`
-- ----------------------------------------------------
DROP TABLE IF EXISTS `chat_messages`;
CREATE TABLE `chat_messages` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `registration_id` INT UNSIGNED DEFAULT NULL,
  `sender_name` VARCHAR(100) NOT NULL,
  `sender_role` VARCHAR(30) NOT NULL DEFAULT 'attendee',
  `message` TEXT NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_chat_created_at` (`created_at`),
  KEY `idx_chat_registration_id` (`registration_id`),
  CONSTRAINT `fk_chat_registration`
    FOREIGN KEY (`registration_id`) REFERENCES `registrations` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- ----------------------------------------------------
-- Seed Data for Default Admin
-- Default Login: Username: admin | Password: AdminPassword123!
-- ----------------------------------------------------
INSERT INTO `admins` (`username`, `full_name`, `email`, `password_hash`, `role`, `created_at`, `updated_at`)
VALUES (
  'admin',
  'TechXP Super Administrator',
  'admin@techxp.in',
  '$2y$10$w3U6U88k5e9pXl7m.K7U6.V5zO.o1QzK3jQeXU6iZ6rG9c8w8y0ye', -- AdminPassword123!
  'superadmin',
  NOW(),
  NOW()
) ON DUPLICATE KEY UPDATE `username`=`username`;

-- ----------------------------------------------------
-- Seed Data for Sample Registrations
-- Default Password for sample attendees: Attendee@2026
-- ----------------------------------------------------
INSERT INTO `registrations` (`registration_id`, `full_name`, `email`, `mobile`, `company`, `designation`, `city`, `country`, `session_preference`, `password_hash`, `consent`, `status`, `ip_address`, `check_in_time`, `created_at`, `updated_at`)
VALUES
('TXP-2026-1001', 'Aarav Sharma', 'aarav.sharma@techcorp.in', '9876543210', 'TechCorp Solutions', 'VP of Engineering', 'Mumbai', 'India', 'Main Session', '$2y$10$bkW63b/6KqA7s4w1eFf3n.G54qP0h7N9yvXo6u4YyI2yA4D6o9G6e', 1, 'confirmed', '127.0.0.1', NULL, NOW() - INTERVAL 2 DAY, NOW() - INTERVAL 2 DAY),
('TXP-2026-1002', 'Priya Patel', 'priya.patel@innovate.co', '9812345678', 'Innovate Labs India', 'Lead Cloud Architect', 'Bengaluru', 'India', 'Workshop', '$2y$10$bkW63b/6KqA7s4w1eFf3n.G54qP0h7N9yvXo6u4YyI2yA4D6o9G6e', 1, 'attended', '127.0.0.1', NOW() - INTERVAL 1 HOUR, NOW() - INTERVAL 1 DAY, NOW()),
('TXP-2026-1003', 'Rohan Verma', 'rohan.verma@cloudscale.io', '9988776655', 'CloudScale Systems', 'Senior Software Engineer', 'Pune', 'India', 'Both', '$2y$10$bkW63b/6KqA7s4w1eFf3n.G54qP0h7N9yvXo6u4YyI2yA4D6o9G6e', 1, 'confirmed', '127.0.0.1', NULL, NOW(), NOW()),
('TXP-2026-1004', 'Ananya Iyer', 'ananya.iyer@fintechhub.in', '9123456780', 'FinTech Nexus', 'Security Specialist', 'London', 'United Kingdom', 'Main Session', '$2y$10$bkW63b/6KqA7s4w1eFf3n.G54qP0h7N9yvXo6u4YyI2yA4D6o9G6e', 1, 'confirmed', '127.0.0.1', NULL, NOW() - INTERVAL 3 HOUR, NOW() - INTERVAL 3 HOUR),
('TXP-2026-1005', 'Kabir Das', 'kabir.das@iitb.ac.in', '9876012345', 'IIT Bombay Research', 'Graduate Researcher', 'Toronto', 'Canada', 'Workshop', '$2y$10$bkW63b/6KqA7s4w1eFf3n.G54qP0h7N9yvXo6u4YyI2yA4D6o9G6e', 1, 'confirmed', '127.0.0.1', NULL, NOW() - INTERVAL 5 HOUR, NOW() - INTERVAL 5 HOUR);

-- ----------------------------------------------------
-- Seed Data for Event Chat
-- ----------------------------------------------------
INSERT INTO `chat_messages` (`registration_id`, `sender_name`, `sender_role`, `message`, `created_at`)
VALUES
(NULL, 'TechXP Bot', 'bot', 'Welcome to TechXP India 2026 Summit! The Opening Keynote by Dr. Rajesh Sharma starts at 10:00 AM IST on the Main Stage.', NOW() - INTERVAL 45 MINUTE),
(NULL, 'Aditi Rao (Summit Host)', 'admin', 'Hello everyone! Don\'t forget to check out the Expo Booths in Hall 2 during the networking coffee break at 11:30 AM.', NOW() - INTERVAL 30 MINUTE),
(1, 'Aarav Sharma', 'vip', 'Super excited for the Cloud Architecture and AI integration sessions today. Looking forward to connecting with fellow engineering leads!', NOW() - INTERVAL 15 MINUTE),
(2, 'Priya Patel', 'speaker', 'Looking forward to the panel discussion on Large Scale Distributed Systems at 2:00 PM!', NOW() - INTERVAL 5 MINUTE);
