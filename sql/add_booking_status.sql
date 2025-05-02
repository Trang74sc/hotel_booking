-- Thêm cột status vào bảng bookings
ALTER TABLE bookings ADD COLUMN status VARCHAR(20) NOT NULL DEFAULT 'pending';

-- Cập nhật các đơn đặt phòng hiện có (nếu có) thành 'confirmed'
UPDATE bookings SET status = 'confirmed' WHERE status = 'pending'; 