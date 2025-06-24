<?php
require_once 'db_connection.php';

// Admin credentials
$admin_name = "Admin";
$admin_email = "admin@segma.com";
$admin_password = "Admin@123"; // This will be hashed

// Hash the password
$hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);

// Check if admin already exists
$check_query = "SELECT * FROM admins WHERE email = ?";
$check_stmt = $conn->prepare($check_query);
$check_stmt->bind_param("s", $admin_email);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows > 0) {
    echo "Admin user already exists!";
} else {
    // Insert new admin
    $insert_query = "INSERT INTO admins (name, email, password) VALUES (?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param("sss", $admin_name, $admin_email, $hashed_password);
    
    if ($insert_stmt->execute()) {
        echo "Admin user created successfully!<br>";
        echo "Email: " . $admin_email . "<br>";
        echo "Password: " . $admin_password;
    } else {
        echo "Error creating admin user: " . $conn->error;
    }
    
    $insert_stmt->close();
}

$check_stmt->close();
$conn->close();
?> 