SET FOREIGN_KEY_CHECKS = 0;

-- Drop existing tables so we start fresh
DROP TABLE IF EXISTS `account_masters`;
DROP TABLE IF EXISTS `bill_payments`;
DROP TABLE IF EXISTS `budget_plans`;
DROP TABLE IF EXISTS `daily_expenses`;
DROP TABLE IF EXISTS `item_departures`;
DROP TABLE IF EXISTS `item_masters`;
DROP TABLE IF EXISTS `partner_collections`;
DROP TABLE IF EXISTS `partner_masters`;
DROP TABLE IF EXISTS `stock_entries`;
DROP TABLE IF EXISTS `stock_entry_details`;
DROP TABLE IF EXISTS `supplier_masters`;

-- 1. Account Masters
CREATE TABLE `account_masters` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `account_number` VARCHAR(255) NOT NULL,
    `description` TEXT NULL,
    `balance_amount` DECIMAL(15,2) DEFAULT 0.00,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
);

-- 2. Supplier Masters
CREATE TABLE `supplier_masters` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `supplier_name` VARCHAR(255) NOT NULL,
    `mobile` VARCHAR(50) NULL,
    `address` TEXT NULL,
    `due_balance` DECIMAL(15,2) DEFAULT 0.00,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
);

-- 3. Item Masters
CREATE TABLE `item_masters` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `item_name` VARCHAR(255) NOT NULL,
    `purchase_rate` DECIMAL(15,2) DEFAULT 0.00,
    `stock_balance` DECIMAL(15,2) DEFAULT 0.00,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
);

-- 4. Partner Masters
CREATE TABLE `partner_masters` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `partner_name` VARCHAR(255) NOT NULL,
    `mobile` VARCHAR(50) NULL,
    `address` TEXT NULL,
    `due_amount` DECIMAL(15,2) DEFAULT 0.00,
    `extra_amount` DECIMAL(15,2) DEFAULT 0.00,
    `paid_amount` DECIMAL(15,2) DEFAULT 0.00,
    `total_charges` DECIMAL(15,2) DEFAULT 0.00,
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
);

-- 5. Bill Payments
CREATE TABLE `bill_payments` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `date` DATE NULL,
    `supplier_name` VARCHAR(255) NULL,
    `account_master` VARCHAR(255) NULL,
    `last_balance` DECIMAL(15,2) DEFAULT 0.00,
    `paid_amount` DECIMAL(15,2) DEFAULT 0.00,
    `paid_by` VARCHAR(255) NULL,
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
);

-- 6. Budget Plans
CREATE TABLE `budget_plans` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `date` DATE NULL,
    `partner_master` VARCHAR(255) NULL,
    `budget_details` TEXT NULL,
    `charge_amount` DECIMAL(15,2) DEFAULT 0.00,
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
);

-- 7. Daily Expenses
CREATE TABLE `daily_expenses` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `expense_date` DATE NULL,
    `account_id` BIGINT UNSIGNED NULL,
    `expense_details` TEXT NULL,
    `expense_amount` DECIMAL(15,2) DEFAULT 0.00,
    `expense_by` VARCHAR(255) NULL,
    `voucher_no` VARCHAR(255) NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
);

-- 8. Item Departures
CREATE TABLE `item_departures` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `date` DATE NULL,
    `item_master` VARCHAR(255) NULL,
    `prev_stock` DECIMAL(15,2) DEFAULT 0.00,
    `departure_qnty` DECIMAL(15,2) DEFAULT 0.00,
    `balance_qnty` DECIMAL(15,2) DEFAULT 0.00,
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
);

-- 9. Partner Collections
CREATE TABLE `partner_collections` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `date` DATE NULL,
    `partners` VARCHAR(255) NULL,
    `budget_plan` VARCHAR(255) NULL,
    `account_master` VARCHAR(255) NULL,
    `total_due_balance` DECIMAL(15,2) DEFAULT 0.00,
    `total_paid_amount` DECIMAL(15,2) DEFAULT 0.00,
    `extra_charges` DECIMAL(15,2) DEFAULT 0.00,
    `net_amount` DECIMAL(15,2) DEFAULT 0.00,
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
);

-- 10. Stock Entries (Parent)
CREATE TABLE `stock_entries` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `date` DATE NULL,
    `supplier_master` VARCHAR(255) NULL,
    `ref_party_chalan` VARCHAR(255) NULL,
    `date_of_party_chalan` DATE NULL,
    `sub_total` DECIMAL(15,2) DEFAULT 0.00,
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
);

-- 11. Stock Entry Details (Child table for the [] arrays)
CREATE TABLE `stock_entry_details` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `stock_entry_id` BIGINT UNSIGNED NOT NULL,
    `item_master` VARCHAR(255) NULL,
    `old_stock` DECIMAL(15,2) DEFAULT 0.00,
    `qnty` DECIMAL(15,2) DEFAULT 0.00,
    `new_stock` DECIMAL(15,2) DEFAULT 0.00,
    `purchase_rate` DECIMAL(15,2) DEFAULT 0.00,
    `total_price` DECIMAL(15,2) DEFAULT 0.00,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    FOREIGN KEY (`stock_entry_id`) REFERENCES `stock_entries`(`id`) ON DELETE CASCADE
);

SET FOREIGN_KEY_CHECKS = 1;
