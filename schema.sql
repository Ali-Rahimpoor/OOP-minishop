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

-- ---------------------------------------------
-- carts
-- هر کاربر می‌تونه یک سبد خرید «فعال» (status = active) داشته باشه.
-- وقتی سفارش نهایی میشه وضعیتش به ordered تغییر می‌کنه و سبد جدیدی
-- برای خریدهای بعدی کاربر ساخته می‌شود.
-- Matches app/Models/Cart.php + CartRepository
-- ---------------------------------------------
CREATE TABLE IF NOT EXISTS `carts` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`    INT UNSIGNED NOT NULL,
  `status`     VARCHAR(40) NOT NULL DEFAULT 'active', -- active | ordered | abandoned
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_carts_user_id` (`user_id`),
  CONSTRAINT `fk_carts_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`ID`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------
-- cart_items
-- unit_price لحظه‌ی افزودن به سبد ذخیره می‌شود (نه لزوما قیمت فعلی محصول)
-- تا اگر قیمت محصول عوض شد، سبد خرید کاربر دستخوش تغییر نشه.
-- Matches app/Models/CartItem.php + CartRepository
-- ---------------------------------------------
CREATE TABLE IF NOT EXISTS `cart_items` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `cart_id`    INT UNSIGNED NOT NULL,
  `product_id` INT UNSIGNED NOT NULL,
  `quantity`   INT UNSIGNED NOT NULL,
  `unit_price` INT UNSIGNED NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_cart_items_product_id` (`product_id`),
  KEY `idx_cart_items_cart_id` (`cart_id`),
  CONSTRAINT `fk_cart_items_cart`
    FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `fk_cart_items_product`
    FOREIGN KEY (`product_id`) REFERENCES `products` (`ID`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------
-- orders
-- سفارش نهایی که از سبد خرید ساخته می‌شود.
-- subtotal   = جمع (unit_price * quantity) همه آیتم‌ها قبل از تخفیف
-- total_price = subtotal - discount + shipping_cost
-- ---------------------------------------------
CREATE TABLE IF NOT EXISTS `orders` (
  `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_number`    INT UNSIGNED NOT NULL,
  `user_id`         INT UNSIGNED NOT NULL,
  `subtotal`        INT UNSIGNED NOT NULL,
  `discount`        INT UNSIGNED NULL DEFAULT NULL,
  `shipping_cost`   INT UNSIGNED NULL DEFAULT NULL,
  `total_price`     INT UNSIGNED NOT NULL,
  `payment_status`  VARCHAR(40) NOT NULL DEFAULT 'pending',   -- pending | paid | failed
  `order_status`    VARCHAR(40) NOT NULL DEFAULT 'processing', -- processing | shipped | delivered | canceled
  `address`         VARCHAR(250) NOT NULL,
  `receiver_name`   VARCHAR(120) NOT NULL,
  `receiver_mobile` VARCHAR(11) NOT NULL,
  `description`     TEXT NULL,
  `created_at`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_orders_order_number` (`order_number`),
  KEY `idx_orders_user_id` (`user_id`),
  CONSTRAINT `fk_orders_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`ID`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------
-- order_items
-- کپی «فریز شده» از اطلاعات محصول لحظه‌ی خرید (product_title, unit_price)
-- تا اگر بعدا محصول ادیت/حذف شد، تاریخچه سفارش دست‌نخورده بمونه.
-- ---------------------------------------------
CREATE TABLE IF NOT EXISTS `order_items` (
  `id`             INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id`       INT UNSIGNED NOT NULL,
  `product_id`     INT UNSIGNED NOT NULL,
  `product_title`  VARCHAR(255) NOT NULL,
  `quantity`       INT UNSIGNED NOT NULL,
  `unit_price`     INT UNSIGNED NOT NULL,
  `total_price`    INT UNSIGNED NOT NULL,
  `created_at`     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_order_items_order_id` (`order_id`),
  KEY `idx_order_items_product_id` (`product_id`),
  CONSTRAINT `fk_order_items_order`
    FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------
-- order_status_log
-- تاریخچه‌ی تغییر وضعیت سفارش (برای پیگیری سفارش توسط کاربر/ادمین)
-- changed_by = ID کاربری که وضعیت رو تغییر داده (ادمین یا سیستم)
-- ---------------------------------------------
CREATE TABLE IF NOT EXISTS `order_status_log` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id`    INT UNSIGNED NOT NULL,
  `status`      VARCHAR(40) NOT NULL,
  `description` VARCHAR(250) NOT NULL,
  `changed_by`  INT UNSIGNED NOT NULL,
  `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_order_status_log_order_id` (`order_id`),
  CONSTRAINT `fk_order_status_log_order`
    FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------
-- payments
-- هر رکورد یک تلاش پرداخت برای یک سفارش است (یک سفارش می‌تونه چند بار
-- تلاش پرداخت ناموفق داشته باشه قبل از موفقیت).
-- gateway = نام درگاه (مثلا zarinpal) | authority = کد پیگیری درگاه قبل از پرداخت
-- ref_id  = کد پیگیری نهایی بعد از پرداخت موفق
-- ---------------------------------------------
CREATE TABLE IF NOT EXISTS `payments` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id`   INT UNSIGNED NOT NULL,
  `gateway`    VARCHAR(250) NOT NULL,
  `authority`  VARCHAR(250) NOT NULL,
  `ref_id`     INT UNSIGNED NULL DEFAULT NULL,
  `amount`     INT UNSIGNED NOT NULL,
  `status`     VARCHAR(40) NOT NULL DEFAULT 'pending', -- pending | success | failed
  `card_pan`   VARCHAR(30) NULL DEFAULT NULL,
  `paid_at`    DATETIME NULL DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_payments_order_id` (`order_id`),
  KEY `idx_payments_authority` (`authority`),
  CONSTRAINT `fk_payments_order`
    FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
    ON DELETE CASCADE
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