<?php
// Define secure access
define('SECURE_ACCESS', true);

// Database configuration
$db_host = 'localhost';
$db_user = 'root';
$db_pass = ''; // Add your database password here
$db_name = 'segma_store';

// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8mb4
$conn->set_charset("utf8mb4");

// Function to get database connection
function getDBConnection() {
    global $conn;
    return $conn;
}

// Function to safely close the database connection
function closeDBConnection() {
    global $conn;
    if ($conn) {
        $conn->close();
    }
}
?>