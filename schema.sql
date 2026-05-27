
-- PassGuard — Database Schema & Sample Data
-- A pedagogical password vault that teaches good security habits.
--
-- Server: MySQL 8.4  |  Charset: utf8mb4_unicode_ci


SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `passguard`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `passguard`;

--  Users 

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id`            INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `username`      VARCHAR(50)     COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` VARCHAR(255)    COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_admin`      TINYINT(1)      NOT NULL DEFAULT 0,
  `is_active`     TINYINT(1)      NOT NULL DEFAULT 1,
  `created_at`    DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login`    DATETIME        DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--  Categories 

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id`   TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50)      COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_category_name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--  Credentials 

DROP TABLE IF EXISTS `credentials`;
CREATE TABLE IF NOT EXISTS `credentials` (
  `id`              CHAR(20)         COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id`         INT UNSIGNED     NOT NULL,
  `site_name`       VARCHAR(150)     COLLATE utf8mb4_unicode_ci NOT NULL,
  `site_url`        VARCHAR(2083)    COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username`        VARCHAR(150)     COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_enc`    TEXT             COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id`     TINYINT UNSIGNED DEFAULT NULL,
  `notes`           TEXT             COLLATE utf8mb4_unicode_ci,
  `strength_score`  TINYINT UNSIGNED DEFAULT NULL,
  `created_at`      DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      DATETIME         DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_cred_user`     (`user_id`),
  KEY `idx_cred_category` (`category_id`),
  FULLTEXT KEY `ft_cred_search` (`site_name`,`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--  Sessions

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `session_token`  CHAR(64)     COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id`        INT UNSIGNED NOT NULL,
  `created_at`     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at`     DATETIME     NOT NULL,
  PRIMARY KEY (`session_token`),
  KEY `idx_session_user`    (`user_id`),
  KEY `idx_session_expires` (`expires_at`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--  Audit Log 

DROP TABLE IF EXISTS `audit_log`;
CREATE TABLE IF NOT EXISTS `audit_log` (
  `id`            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`       INT UNSIGNED    NOT NULL,
  `action`        VARCHAR(50)     COLLATE utf8mb4_unicode_ci NOT NULL,
  `credential_id` CHAR(20)        COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address`    VARCHAR(45)     COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at`    DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_audit_user`   (`user_id`),
  KEY `idx_audit_action` (`action`),
  KEY `idx_audit_date`   (`created_at`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- SAMPLE DATA


-- Default categories
INSERT INTO `categories` (`id`, `name`) VALUES
  (1, 'Social'),
  (2, 'Work'),
  (3, 'Finance'),
  (4, 'Other');

-- Sample users
-- admin    / Admin@1234  (admin role)
-- Romain   / (set during class)
-- test     / (test account)
-- testuser / (test account)
-- Leroy    / (set during class)
INSERT INTO `users` (`id`, `username`, `password_hash`, `is_admin`, `is_active`, `created_at`, `last_login`) VALUES
  (1, 'admin',                  '$2y$12$P6Pe9xreJkeCFk8VBBLPLeGtiXLVev96Fv5C9tQiyg.6g9YJaErpK', 1, 1, '2026-05-13 11:00:50', NULL),
  (2, 'Romain',                 '$2y$12$zhNj1uTI9JFwCM6eQrI6dOf09lABdW4W7LcugYGpJq0SdWfc3Gj7q', 0, 1, '2026-05-13 11:04:58', NULL),
  (3, 'test',                   '$2y$12$y2IsasBcWiKJgJPS0FQhweCLMQd9IxrFO5/f8sC3XAWHA5orZ/dyu', 0, 1, '2026-05-13 11:35:49', NULL),
  (4, 'testuser',               '$2y$12$7axPS.C.EyanMARDfi884OWbRWEYMStQhgZ6dSoKCdktGfpP6VkKS', 0, 1, '2026-05-13 11:56:34', '2026-05-13 12:07:48'),
  (5, 'Leroy',                  '$2y$12$EtsVk2hqlovkvFQEBZr9OuJs1CL7PKU5LtTEhsOW7s3yTStSsUvyq', 0, 1, '2026-05-13 12:13:20', NULL),
  (6, 'RomainTest',             '$2y$12$CzWKldYQ0w37SaNbzxhIPeqkRjViewLMKJTA/hGFU3FKPtzWciEzW', 0, 1, '2026-05-18 13:26:30', '2026-05-19 11:28:48'),
  (7, 'ngoyvictor17@gmail.com', '$2y$12$ZkefVGKQsxE9Vsnc0HzbwuP2lb24HqSO6jS/IoEgJdKvYwsXPbwLe', 0, 1, '2026-05-19 10:55:40', NULL),
  (8, 'user@gmail.com',         '$2y$12$jrrqOT2OF0rANpLVa4wkOO2.s.DyKhAzrG/nlOD1BKW/XZs5EHXDG', 0, 1, '2026-05-19 11:47:09', NULL);

-- Sample credentials
INSERT INTO `credentials` (`id`, `user_id`, `site_name`, `site_url`, `username`, `password_enc`, `category_id`, `notes`, `strength_score`, `created_at`, `updated_at`) VALUES
  ('dd1tjxfstf8d6x', 6, 'Google', 'https://www.google.com', 'test@gmail.com',  'WtJe6m579TgeN/OWANW19ow7bBXA+rmhjo7/Cs2DXNNF3RObhExlHMyZ27o=', 2, NULL, 86, '2026-05-18 13:28:10', NULL),
  ('a0m3fswutfa0vc', 7, 'Google', 'https://www.google.com', 'jeff',            'KDbhlR59cnstRBXQYXU6bHM8u8S5Qnl/8jiLSRvNRA==',                 2, NULL, 17, '2026-05-19 10:57:12', '2026-05-19 10:57:51'),
  ('ofgcpc5otfa38m', 8, 'Google', 'https://www.google.com', 'user@gmail.com',  'pcHTUuZFhaYEc04O+HiTOiSPtptL36k7GnJ334TaS5h/K6GDi+gYE29uXDiq6DGECRXVa1GuGHUtdkmaWS+FWjv1', 2, NULL, 95, '2026-05-19 11:48:22', '2026-05-19 11:49:23');

-- Sample audit log
INSERT INTO `audit_log` (`id`, `user_id`, `action`, `credential_id`, `ip_address`, `created_at`) VALUES
  (1,  1, 'SIGNUP',    NULL,             '::1', '2026-05-13 11:00:50'),
  (2,  2, 'SIGNUP',    NULL,             '::1', '2026-05-13 11:04:58'),
  (3,  3, 'SIGNUP',    NULL,             '::1', '2026-05-13 11:35:49'),
  (4,  4, 'SIGNUP',    NULL,             '::1', '2026-05-13 11:56:34'),
  (5,  4, 'LOGIN_OK',  NULL,             '::1', '2026-05-13 12:07:48'),
  (6,  5, 'SIGNUP',    NULL,             '::1', '2026-05-13 12:13:20'),
  (7,  6, 'SIGNUP',    NULL,             '::1', '2026-05-18 13:26:30'),
  (8,  6, 'CRED_ADD',  'dd1tjxfstf8d6x', '::1', '2026-05-18 13:28:10'),
  (9,  7, 'SIGNUP',    NULL,             '::1', '2026-05-19 10:55:40'),
  (10, 7, 'CRED_ADD',  'a0m3fswutfa0vc', '::1', '2026-05-19 10:57:12'),
  (11, 7, 'CRED_EDIT', 'a0m3fswutfa0vc', '::1', '2026-05-19 10:57:51'),
  (12, 6, 'LOGIN_OK',  NULL,             '::1', '2026-05-19 11:28:48'),
  (13, 6, 'LOGOUT',    NULL,             '::1', '2026-05-19 11:30:35'),
  (14, 8, 'SIGNUP',    NULL,             '::1', '2026-05-19 11:47:09'),
  (15, 8, 'CRED_ADD',  'ofgcpc5otfa38m', '::1', '2026-05-19 11:48:22'),
  (16, 8, 'CRED_EDIT', 'ofgcpc5otfa38m', '::1', '2026-05-19 11:49:23'),
  (17, 8, 'LOGOUT',    NULL,             '::1', '2026-05-19 11:49:48');

COMMIT;
