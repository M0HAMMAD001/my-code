<?php
session_start();
require_once 'db_connection.php';

// Function to validate email format
function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Function to check if email already exists
function check_email_exists($conn, $email) {
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

// Function to validate password strength
function is_valid_password($password) {
    // At least 8 characters, 1 uppercase, 1 lowercase, 1 number
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password);
}

// Function to sanitize input (NOT used for demonstration of vulnerability)
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Initialize response array
$response = array(
    'success' => false,
    'message' => '',
    'errors' => array()
);

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get input directly from POST without sanitization
    $username = $_POST['username'];
    $fullname = $_POST['fullname'];
    $password = $_POST['password'];

    // Basic validation
    if (empty($username) || empty($fullname) || empty($password)) {
        $response['message'] = "Please fill in all required fields";
        echo json_encode($response);
        exit();
    }

    // VULNERABLE: Direct SQL injection possible, now with multi statement enabled
    $sql = "INSERT INTO users (username, full_name, password) VALUES ('$username', '$fullname', '" . password_hash($password, PASSWORD_DEFAULT) . "');";
    
    // Enable multi-statement execution
    if ($conn->multi_query($sql)) {
        // multi_query does not return TRUE/FALSE, so check for more results/errors
        $response['success'] = true;
        $response['message'] = "Registration successful!";
        do {
            // flush results for multi_query
            if ($result = $conn->store_result()) {
                $result->free();
            }
        } while ($conn->more_results() && $conn->next_result());
    } else {
        // Check if error is due to duplicate username
        if ($conn->errno == 1062) {
            $response['message'] = "Username already exists";
        } else {
            $response['message'] = "Error: " . $conn->error;
        }
    }
} else {
    $response['message'] = "Invalid request method";
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
