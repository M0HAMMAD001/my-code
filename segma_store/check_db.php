<?php
$host = 'localhost';
$dbname = 'segma_store';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if users table exists
    $result = $pdo->query("SHOW TABLES LIKE 'users'");
    if($result->rowCount() > 0) {
        echo "Users table exists<br>";
        
        // Show all users
        $users = $pdo->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);
        echo "Found " . count($users) . " users:<br>";
        foreach($users as $user) {
            echo "Username: " . $user['username'] . "<br>";
        }
    } else {
        echo "Users table does not exist<br>";
        
        // Create users table
        $pdo->exec("CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        echo "Created users table<br>";
        
        // Insert test user
        $pdo->exec("INSERT INTO users (username, password) VALUES ('test', 'test123')");
        echo "Added test user<br>";
    }
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 