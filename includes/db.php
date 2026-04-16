<?php
/**
 * MobileHub — Database Connection
 * Manages MySQL connection using mysqli
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); // Set to empty for standard XAMPP setup
define('DB_NAME', 'mobilehub_db');

// Site configuration
define('SITE_NAME', 'MobileHub');
define('SITE_URL', 'http://localhost/mobile-store');
define('UPLOAD_DIR', __DIR__ . '/../assets/images/products/');

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8mb4");

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
