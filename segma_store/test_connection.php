<?php
require_once 'db_connection.php';

try {
    // Test the connection
    if ($conn->ping()) {
        echo "Database connection successful!";
        
        // Test query to get admin count
        $result = $conn->query("SELECT COUNT(*) as admin_count FROM admins");
        $row = $result->fetch_assoc();
        echo "<br>Number of admin users: " . $row['admin_count'];
        
        // Test query to get table names
        $result = $conn->query("SHOW TABLES");
        echo "<br><br>Database tables:";
        echo "<ul>";
        while ($row = $result->fetch_array()) {
            echo "<li>" . $row[0] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "Database connection failed!";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?> 