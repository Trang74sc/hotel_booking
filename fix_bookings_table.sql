-- Sửa lại cấu trúc bảng bookings
ALTER TABLE bookings
    MODIFY COLUMN room_id INT NOT NULL,
    MODIFY COLUMN customer_name VARCHAR(255) NOT NULL,
    MODIFY COLUMN customer_email VARCHAR(255) NOT NULL,
    MODIFY COLUMN customer_phone VARCHAR(20) NOT NULL,
    MODIFY COLUMN check_in DATE NOT NULL,
    MODIFY COLUMN check_out DATE NOT NULL,
    MODIFY COLUMN total_price DECIMAL(10,2) NOT NULL,
    MODIFY COLUMN payment_method VARCHAR(50) NOT NULL,
    MODIFY COLUMN status VARCHAR(20) NOT NULL DEFAULT 'pending';

-- Thêm các ràng buộc khóa ngoại
ALTER TABLE bookings
    ADD CONSTRAINT fk_bookings_room
    FOREIGN KEY (room_id) REFERENCES rooms(id); 