-- Database untuk Eksperimen SQL Injection
-- Tugas UTS Pemrograman Web
-- Amelia Nurmala Dewi

CREATE DATABASE IF NOT EXISTS latihan_sql;
USE latihan_sql;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  password VARCHAR(50) NOT NULL
);

INSERT INTO users (username, password) VALUES 
('admin', 'admin123'),
('amelia', 'password456');
