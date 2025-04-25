<?php
require_once 'config.php';

try {
    // Add new columns
    $pdo->exec("
        ALTER TABLE rooms
        ADD COLUMN IF NOT EXISTS status ENUM('available', 'maintenance') DEFAULT 'available',
        ADD COLUMN IF NOT EXISTS amenities TEXT,
        ADD COLUMN IF NOT EXISTS capacity INT DEFAULT 2,
        ADD COLUMN IF NOT EXISTS size INT,
        ADD COLUMN IF NOT EXISTS image VARCHAR(255)
    ");

    // Update existing rooms
    $pdo->exec("
        UPDATE rooms SET
        amenities = 'wifi,air_con,tv',
        capacity = 2,
        size = 30,
        image = 'default.jpg'
        WHERE amenities IS NULL
    ");

    echo "Database updated successfully!";
} catch (PDOException $e) {
    echo "Error updating database: " . $e->getMessage();
} 