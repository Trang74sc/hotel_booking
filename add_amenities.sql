-- Thêm cột amenities vào bảng rooms
ALTER TABLE rooms ADD COLUMN amenities TEXT AFTER max_guests;

-- Cập nhật tiện nghi cho các phòng
UPDATE rooms SET amenities = 'wifi,tv,air_con' WHERE id = 1; -- Phòng Đơn Tiêu Chuẩn
UPDATE rooms SET amenities = 'wifi,tv,air_con,minibar' WHERE id = 2; -- Phòng Đôi Gia Đình
UPDATE rooms SET amenities = 'wifi,tv,air_con,minibar,spa,bathtub' WHERE id = 3; -- Phòng Suite Hạng Sang
UPDATE rooms SET amenities = 'wifi,tv,air_con,minibar,sea_view' WHERE id = 4; -- Phòng Deluxe View Biển
UPDATE rooms SET amenities = 'wifi,tv,air_con' WHERE id = 5; -- Phòng Dorm Tập Thể
UPDATE rooms SET amenities = 'wifi,tv,air_con,minibar,spa' WHERE id = 6; -- Phòng Superior Cao Cấp 