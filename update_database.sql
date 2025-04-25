-- Add new columns to rooms table
ALTER TABLE rooms
ADD COLUMN status ENUM('available', 'maintenance') DEFAULT 'available',
ADD COLUMN amenities TEXT,
ADD COLUMN capacity INT DEFAULT 2,
ADD COLUMN size INT,
ADD COLUMN image VARCHAR(255);

-- Update existing rooms with default values
UPDATE rooms SET
amenities = 'wifi,air_con,tv',
capacity = 2,
size = 30,
image = 'default.jpg'
WHERE amenities IS NULL; 