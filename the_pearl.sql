CREATE DATABASE IF NOT EXISTS the_pearl;
USE the_pearl;

DROP TABLE IF EXISTS rooms;
CREATE TABLE rooms (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  beds VARCHAR(50) NOT NULL,
  size VARCHAR(50) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  image VARCHAR(255) NOT NULL
);

INSERT INTO rooms (name,beds,size,price,image) VALUES
('Deluxe King','1 King','28 m²',320.00,'https://images.unsplash.com/photo-1554995207-c18c203602cb?q=80&w=1600&auto=format&fit=crop'),
('Superior Twin','2 Single','24 m²',260.00,'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?q=80&w=1600&auto=format&fit=crop'),
('Executive Suite','1 King + Lounge','45 m²',520.00,'https://images.unsplash.com/photo-1600585154526-990dced4db0d?q=80&w=1600&auto=format&fit=crop');

DROP TABLE IF EXISTS contact;
CREATE TABLE contact (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  message TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS bookings;
CREATE TABLE bookings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  room_id INT NOT NULL,
  customer_name VARCHAR(120) NOT NULL,
  customer_email VARCHAR(120) NOT NULL,
  checkin_date DATE NOT NULL,
  checkout_date DATE NOT NULL,
  guests INT NOT NULL DEFAULT 1,
  nights INT NOT NULL,
  total_amount DECIMAL(10,2) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_room FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE
);
