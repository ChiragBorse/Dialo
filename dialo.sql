-- Run this once in phpMyAdmin (or `mysql -u root < opal_mind.sql`)
-- It creates the database and the users table Opal's signup/login pages use.

CREATE DATABASE IF NOT EXISTS opal_mind CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE dialo;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
