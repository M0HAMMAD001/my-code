<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("HTTP/1.1 403 Forbidden");
    exit("Access denied");
}

$debug_log = 'registration_debug.log';

// Clear the log file
if (file_exists($debug_log)) {
    file_put_contents($debug_log, '');
    echo "Log cleared successfully";
} else {
    echo "No log file found";
}
?> 