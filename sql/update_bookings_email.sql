-- Đổi tên cột email thành customer_email nếu cột email đã tồn tại
ALTER TABLE bookings CHANGE COLUMN IF EXISTS email customer_email VARCHAR(255);

-- Thêm cột customer_email nếu chưa tồn tại
ALTER TABLE bookings ADD COLUMN IF NOT EXISTS customer_email VARCHAR(255) NOT NULL; 