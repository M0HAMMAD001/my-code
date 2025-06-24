<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define secure access
define('SECURE_ACCESS', true);

// Include database configuration
require_once 'db_config.php';

echo "<h2>Database Setup</h2>";

try {
    // Create connection without database selected
    $conn = new mysqli($db_host, $db_user, $db_pass);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    echo "✅ Connected to MySQL server<br>";
    
    // Create database if it doesn't exist
    $sql = "CREATE DATABASE IF NOT EXISTS " . $db_name;
    if ($conn->query($sql)) {
        echo "✅ Database '{$db_name}' created or already exists<br>";
    } else {
        throw new Exception("Error creating database: " . $conn->error);
    }
    
    // Select the database
    if ($conn->select_db($db_name)) {
        echo "✅ Database selected<br>";
    } else {
        throw new Exception("Error selecting database: " . $conn->error);
    }
    
    // Create users table
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        phone_number VARCHAR(15),
        region VARCHAR(100),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    if ($conn->query($sql)) {
        echo "✅ Users table created or already exists<br>";
    } else {
        throw new Exception("Error creating users table: " . $conn->error);
    }
    
    echo "<br>✅ Database setup completed successfully!";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?> 