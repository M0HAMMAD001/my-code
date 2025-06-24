<?php
// Database configuration
$db_host = 'localhost';      // Database host
$db_user = 'root';          // Default phpMyAdmin username
$db_pass = '';              // Default phpMyAdmin password (usually blank on XAMPP)
$db_name = 'segma_store';   // Your database name

// Prevent direct access to this file
if (!defined('SECURE_ACCESS')) {
    header("HTTP/1.0 403 Forbidden");
    exit;
}
?> 