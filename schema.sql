-- Smart Logistics Tracking Web Application
-- Database schema

CREATE DATABASE IF NOT EXISTS smart_logistics;
USE smart_logistics;

-- Admin / staff accounts (Authentication Module)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Shipments (Shipment Module)
CREATE TABLE IF NOT EXISTS shipments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tracking_id VARCHAR(20) NOT NULL UNIQUE,
    sender_name VARCHAR(100) NOT NULL,
    sender_phone VARCHAR(20),
    receiver_name VARCHAR(100) NOT NULL,
    receiver_phone VARCHAR(20),
    origin VARCHAR(100) NOT NULL,
    destination VARCHAR(100) NOT NULL,
    package_details VARCHAR(255),
    status VARCHAR(30) NOT NULL DEFAULT 'Pending',
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Status history (Status Update / Tracking Module)
-- Note: when status = 'Delayed', the remarks column stores the delay
-- reason (e.g. "Reason: Traffic congestion - left 30 mins late") so
-- customers see WHY it's delayed, not just that it is.
CREATE TABLE IF NOT EXISTS status_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    shipment_id INT NOT NULL,
    status VARCHAR(30) NOT NULL,
    remarks VARCHAR(255),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (shipment_id) REFERENCES shipments(id) ON DELETE CASCADE
);

-- No default admin is inserted here because the password must be hashed
-- by PHP (password_hash), not typed as plain text into SQL.
-- Run setup/create_admin.php once after importing this schema to create
-- your first admin login.
