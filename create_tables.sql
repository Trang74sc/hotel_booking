-- Tạo bảng rooms
CREATE TABLE IF NOT EXISTS rooms (
    id INT PRIMARY KEY AUTO_INCREMENT,
    room_number VARCHAR(10) NOT NULL,
    room_type VARCHAR(50) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    capacity INT NOT NULL,
    description TEXT,
    status VARCHAR(20) DEFAULT 'available'
);

-- Tạo bảng bookings
CREATE TABLE IF NOT EXISTS bookings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    room_id INT NOT NULL,
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    check_in_date DATE NOT NULL,
    check_out_date DATE NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(50) NOT NULL DEFAULT 'stripe',
    payment_status VARCHAR(50) NOT NULL DEFAULT 'pending',
    payment_id VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (room_id) REFERENCES rooms(id)
);

-- Thêm dữ liệu mẫu cho rooms
INSERT INTO rooms (room_number, room_type, price, capacity, description, status) VALUES
('101', 'Phòng đơn', 500000, 2, 'Phòng đơn tiêu chuẩn với đầy đủ tiện nghi', 'available'),
('201', 'Phòng VIP', 1200000, 2, 'Phòng VIP với dịch vụ cao cấp', 'available'),
('202', 'Phòng gia đình', 1500000, 6, 'Phòng rộng phù hợp cho gia đình', 'available'); 