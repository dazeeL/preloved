<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'preloved_db');

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Base URL
define('BASE_URL', '/preloved');

// Upload directory
define('UPLOAD_DIR', __DIR__ . '/../uploads/products/');

// Try database connection (graceful fail)
mysqli_report(MYSQLI_REPORT_OFF); // matikan exception bawaan mysqli
$conn = null;

try {
    $conn = @new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        $conn = null; // Will use dummy data
    } else {
        $conn->set_charset("utf8mb4");
    }
} catch (Throwable $e) {
    $conn = null; // Will use dummy data
}