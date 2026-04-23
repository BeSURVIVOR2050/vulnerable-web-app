-- =============================================
-- Secure Ping Assignment Database Setup
-- Filename: secure_ping_init.sql
-- Created: 2026
-- =============================================

-- Create database (if not exists)
CREATE DATABASE IF NOT EXISTS secure_db 
    CHARACTER SET utf8mb4 
    COLLATE utf8mb4_unicode_ci;

-- Use the database
USE secure_db;

-- ======================
-- Create users table
-- ======================  
CREATE TABLE IF NOT EXISTS users (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    username    VARCHAR(50)  NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,        -- Recommend using HASH in the future
    email       VARCHAR(100) NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ======================
-- Insert sample data (for demo)
-- ======================
-- Note: For actual projects, please use password hashing (password_hash), here we use plaintext for testing convenience
INSERT INTO users (username, password, email) VALUES
('admin',    'admin123',    'admin@hkii.edu.hk'),
('student',  'password',    'student@hkii.edu.hk'),
('user1',    'pass123',     'user1@example.com')
ON DUPLICATE KEY UPDATE 
    password = VALUES(password),
    email = VALUES(email);

-- ======================
-- Completion message
-- ======================
SELECT '✅ Database secure_db is created' AS message;
SELECT '📋 Table users is initialized' AS message;