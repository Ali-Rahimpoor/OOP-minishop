-- =============================================
-- DigiShop / OOP-minishop — Database Schema
-- Charset/collation matches config/database.php (utf8mb4)
-- =============================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS `digishop`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `digishop`;

-- ---------------------------------------------
-- users
-- Matches app/Models/User.php + AdminAuthController / User::findByUsername
-- ---------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `ID`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username`   VARCHAR(100)  NOT NULL,
  `password`   VARCHAR(255)  NOT NULL,        -- bcrypt hash (password_hash output)
  `role`       ENUM('user', 'admin') NOT NULL DEFAULT 'user',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `uq_users_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------
-- products
-- Matches app/Models/Product.php + ProductsRepo (ID, title, description,
-- thumbnail, price, sale_price, stock, status, created_at, updated_at)
-- ---------------------------------------------
CREATE TABLE IF NOT EXISTS `products` (
  `ID`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title`       VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  `thumbnail`   VARCHAR(255) NULL,
  `price`       INT UNSIGNED NOT NULL DEFAULT 0,
  `sale_price`  INT UNSIGNED NOT NULL DEFAULT 0,
  `stock`       INT UNSIGNED NOT NULL DEFAULT 0,
  `status`      ENUM('publish', 'draft', 'presale', 'expire') NOT NULL DEFAULT 'draft',
  `created_at`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  KEY `idx_products_status` (`status`),
  KEY `idx_products_title` (`title`),
  KEY `idx_products_price` (`price`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- ---------------------------------------------
-- Seed: one admin account
-- Username: admin | Password: admin123
-- IMPORTANT: this hash is only for local/dev setup — generate your own with
-- PHP: password_hash('your-real-password', PASSWORD_DEFAULT)
-- and replace the value below before using in anything beyond localhost.
-- ---------------------------------------------
INSERT INTO `users` (`username`, `password`, `role`)
VALUES ('admin', '$2b$10$VgPLp0C9O0EzobH.DP0kH.L4PgHBGkzED.SVFYg73nA87wpSdr7xW', 'admin')
ON DUPLICATE KEY UPDATE `username` = `username`;