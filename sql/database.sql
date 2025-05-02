CREATE DATABASE IF NOT EXISTS hotel_booking;
USE hotel_booking;

CREATE TABLE rooms (
    id INT PRIMARY KEY AUTO_INCREMENT,
    room_number VARCHAR(10) NOT NULL,
    room_type VARCHAR(50) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    capacity INT NOT NULL,
    description TEXT,
    status ENUM('available', 'booked') DEFAULT 'available'
);

CREATE TABLE bookings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    room_id INT,
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    check_in_date DATE NOT NULL,
    check_out_date DATE NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (room_id) REFERENCES rooms(id)
);

-- Thêm một số phòng mẫu
INSERT INTO rooms (room_number, room_type, price, capacity, description) VALUES
('101', 'Phòng đơn', 500000, 2, 'Phòng đơn tiêu chuẩn với đầy đủ tiện nghi'),
('102', 'Phòng đôi', 800000, 4, 'Phòng đôi rộng rãi với view đẹp'),
('201', 'Phòng VIP', 1200000, 2, 'Phòng VIP với dịch vụ cao cấp'),
('202', 'Phòng gia đình', 1500000, 6, 'Phòng rộng phù hợp cho gia đình'); 