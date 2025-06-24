<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'db_connection.php';

echo "<h2>Registration Process Test</h2>";

// Test user data
$test_user = [
    'full_name' => 'Test User',
    'email' => 'test_' . time() . '@test.com', // Unique email using timestamp
    'password' => 'TestPass123',
    'phone_number' => '1234567890',
    'region' => 'Test Region'
];

echo "<h3>1. Testing Database Connection</h3>";
if (!$conn || $conn->connect_error) {
    die("❌ Database connection failed: " . ($conn ? $conn->connect_error : "Connection is null"));
}
echo "✅ Database connection successful<br>";

// Function to clean up test data
function cleanup_test_user($email) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->close();
}

echo "<h3>2. Testing User Registration</h3>";
try {
    // Hash password
    $hashed_password = password_hash($test_user['password'], PASSWORD_DEFAULT);
    
    // Prepare insert statement
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone_number, region) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        throw new Exception("Failed to prepare statement: " . $conn->error);
    }
    
    $stmt->bind_param("sssss", 
        $test_user['full_name'],
        $test_user['email'],
        $hashed_password,
        $test_user['phone_number'],
        $test_user['region']
    );
    
    // Execute insert
    if ($stmt->execute()) {
        $user_id = $stmt->insert_id;
        echo "✅ Test user created successfully (ID: $user_id)<br>";
    } else {
        throw new Exception("Failed to execute statement: " . $stmt->error);
    }
    $stmt->close();
    
    echo "<h3>3. Verifying Stored Data</h3>";
    // Verify the data was stored correctly
    $verify_stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $verify_stmt->bind_param("s", $test_user['email']);
    $verify_stmt->execute();
    $result = $verify_stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        echo "✅ User found in database<br>";
        echo "Stored data verification:<br>";
        echo "- Name matches: " . ($row['name'] === $test_user['full_name'] ? "✅" : "❌") . "<br>";
        echo "- Email matches: " . ($row['email'] === $test_user['email'] ? "✅" : "❌") . "<br>";
        echo "- Password is hashed: " . (password_verify($test_user['password'], $row['password']) ? "✅" : "❌") . "<br>";
        echo "- Phone matches: " . ($row['phone_number'] === $test_user['phone_number'] ? "✅" : "❌") . "<br>";
        echo "- Region matches: " . ($row['region'] === $test_user['region'] ? "✅" : "❌") . "<br>";
    } else {
        echo "❌ Failed to find test user in database<br>";
    }
    $verify_stmt->close();
    
    echo "<h3>4. Cleaning Up Test Data</h3>";
    cleanup_test_user($test_user['email']);
    echo "✅ Test user removed from database<br>";
    
    // Verify cleanup
    $final_check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $final_check->bind_param("s", $test_user['email']);
    $final_check->execute();
    $final_result = $final_check->get_result();
    if ($final_result->num_rows === 0) {
        echo "✅ Cleanup verification successful<br>";
    } else {
        echo "❌ Cleanup verification failed - user still exists<br>";
    }
    $final_check->close();
    
} catch (Exception $e) {
    echo "❌ Test failed: " . $e->getMessage() . "<br>";
    // Cleanup in case of error
    cleanup_test_user($test_user['email']);
}

// Close database connection
$conn->close();
echo "<br>✅ Test completed";
?> 