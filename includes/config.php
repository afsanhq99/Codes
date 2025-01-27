<?php

// Database Credentials
define('DB_HOST', 'localhost'); // e.g., 'localhost' or '127.0.0.1'
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'event_management');
define('BASE_URL', 'http://localhost/Event%20Management/Codes/');
// Attempt to connect to the database
try {
    $db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set error mode to exception
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}