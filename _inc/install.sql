-- DGT Market — Database Schema
-- Run once on digitwac_dgtmint database

CREATE TABLE IF NOT EXISTS mkt_users (
    id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name          VARCHAR(120)     NOT NULL,
    email         VARCHAR(200)     NOT NULL UNIQUE,
    mobile        VARCHAR(15)      DEFAULT NULL,
    password_hash VARCHAR(255)     NOT NULL,
    avatar        VARCHAR(255)     DEFAULT NULL,
    status        ENUM('active','banned','unverified') NOT NULL DEFAULT 'active',
    last_login    DATETIME         DEFAULT NULL,
    created_at    DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS mkt_orders (
    id                BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id           BIGINT UNSIGNED NOT NULL,
    product_id        VARCHAR(80)     NOT NULL,
    product_name      VARCHAR(255)    NOT NULL,
    tier              ENUM('free','pro') NOT NULL DEFAULT 'pro',
    amount_rupees     INT UNSIGNED    NOT NULL DEFAULT 0,
    currency          CHAR(3)         NOT NULL DEFAULT 'INR',
    rzp_order_id      VARCHAR(80)     DEFAULT NULL,
    rzp_payment_id    VARCHAR(80)     DEFAULT NULL,
    rzp_signature     VARCHAR(255)    DEFAULT NULL,
    status            ENUM('pending','paid','failed','refunded') NOT NULL DEFAULT 'pending',
    download_token    VARCHAR(64)     DEFAULT NULL,
    download_expires  DATETIME        DEFAULT NULL,
    ip_address        VARCHAR(45)     DEFAULT NULL,
    created_at        DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at        DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES mkt_users(id) ON DELETE CASCADE,
    INDEX idx_user    (user_id),
    INDEX idx_product (product_id),
    INDEX idx_rzp     (rzp_order_id),
    INDEX idx_status  (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS mkt_access (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id     BIGINT UNSIGNED NOT NULL,
    product_id  VARCHAR(80)     NOT NULL,
    order_id    BIGINT UNSIGNED NOT NULL,
    granted_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    expires_at  DATETIME        DEFAULT NULL,
    UNIQUE KEY uq_user_product (user_id, product_id),
    FOREIGN KEY (user_id)  REFERENCES mkt_users(id)  ON DELETE CASCADE,
    FOREIGN KEY (order_id) REFERENCES mkt_orders(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
