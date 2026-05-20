-- ============================================
-- AlUla Vision 2030 - Database Schema
-- IS337 Group Project
-- Database: alula_db
-- ============================================

-- Create the database
CREATE DATABASE IF NOT EXISTS alula_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE alula_db;

-- ============================================
-- Users Table
-- ============================================
CREATE TABLE IF NOT EXISTS users (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  name         VARCHAR(100) NOT NULL,
  user_id      VARCHAR(20) NOT NULL UNIQUE,        -- National ID / Iqama
  dob          DATE NOT NULL,
  nationality  VARCHAR(50) NOT NULL,
  mobile       VARCHAR(15) NOT NULL,
  email        VARCHAR(150) NOT NULL UNIQUE,
  password     VARCHAR(255) NOT NULL,              -- hashed
  created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- Places Table (tourist destinations)
-- ============================================
CREATE TABLE IF NOT EXISTS places (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(100) NOT NULL,
  description TEXT,
  image       VARCHAR(255),
  duration    VARCHAR(50),
  price       DECIMAL(10,2) NOT NULL
);

-- Insert sample places
INSERT INTO places (name, description, image, duration, price) VALUES
('Hegra (Madain Saleh)', 'UNESCO World Heritage Site with over 110 Nabataean tombs.', 'images/places/hegra-tourists.jpg', '4 hours', 250.00),
('Qasr Al-Farid', 'The iconic standalone-rock tomb, symbol of AlUla.', 'images/places/al-farid.jpg', '3 hours', 200.00),
('AlUla Old Town', 'Maze of 900 mud-brick houses, centuries old.', 'images/gallery/clay-pool.jpg', '2 hours', 120.00),
('Wildlife Safari', 'Sharaan Nature Reserve — gazelles and Arabian leopard.', 'images/places/wildlife.jpg', '3 hours', 180.00),
('Skydiving', 'Tandem skydive over the AlUla desert and tombs.', 'images/places/skydiving.jpg', '2 hours', 900.00);

-- ============================================
-- Bookings Table
-- ============================================
CREATE TABLE IF NOT EXISTS bookings (
  id               INT AUTO_INCREMENT PRIMARY KEY,
  user_id          INT NOT NULL,
  place            VARCHAR(100) NOT NULL,
  date             DATE NOT NULL,
  time             VARCHAR(20) NOT NULL,
  guests           INT NOT NULL DEFAULT 1,
  special_requests TEXT,
  status           VARCHAR(20) DEFAULT 'confirmed',
  created_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================
-- Feedback Table
-- ============================================
CREATE TABLE IF NOT EXISTS feedback (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  name       VARCHAR(100) NOT NULL,
  email      VARCHAR(150) NOT NULL,
  rating     INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
  message    TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample feedback (for demo purposes)
INSERT INTO feedback (name, email, rating, message) VALUES
('Sara A.', 'sara@example.com', 5, 'AlUla is breathtaking! Hegra was the highlight of my trip — a true wonder of Saudi Arabia.'),
('Mohammed K.', 'mohammed@example.com', 4, 'The Old Town tour was amazing. The mud-brick architecture is beautifully preserved.');
