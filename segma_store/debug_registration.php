<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Debug log file
$debug_log = 'registration_debug.log';

// Function to log debug information
function debug_log($message, $type = 'INFO') {
    global $debug_log;
    $timestamp = date('Y-m-d H:i:s');
    $log_message = "[$timestamp][$type] $message\n";
    file_put_contents($debug_log, $log_message, FILE_APPEND);
}

// Function to validate input
function validate_input($data) {
    debug_log("Validating input: " . print_r($data, true));
    
    $errors = [];
    
    // Validate name
    if (empty($data['full_name'])) {
        $errors[] = "Name is required";
        debug_log("Name validation failed: Empty name", "ERROR");
    } elseif (strlen($data['full_name']) < 2 || strlen($data['full_name']) > 100) {
        $errors[] = "Name must be between 2 and 100 characters";
        debug_log("Name validation failed: Invalid length", "ERROR");
    }
    
    // Validate email
    if (empty($data['email'])) {
        $errors[] = "Email is required";
        debug_log("Email validation failed: Empty email", "ERROR");
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
        debug_log("Email validation failed: Invalid format", "ERROR");
    }
    
    // Validate password
    if (empty($data['password'])) {
        $errors[] = "Password is required";
        debug_log("Password validation failed: Empty password", "ERROR");
    } elseif (strlen($data['password']) < 8) {
        $errors[] = "Password must be at least 8 characters long";
        debug_log("Password validation failed: Too short", "ERROR");
    } elseif (!preg_match('/[A-Z]/', $data['password'])) {
        $errors[] = "Password must contain at least one uppercase letter";
        debug_log("Password validation failed: No uppercase", "ERROR");
    } elseif (!preg_match('/[a-z]/', $data['password'])) {
        $errors[] = "Password must contain at least one lowercase letter";
        debug_log("Password validation failed: No lowercase", "ERROR");
    } elseif (!preg_match('/[0-9]/', $data['password'])) {
        $errors[] = "Password must contain at least one number";
        debug_log("Password validation failed: No number", "ERROR");
    }
    
    // Validate phone number (optional)
    if (!empty($data['phone_number'])) {
        if (!preg_match('/^[0-9]{10,15}$/', $data['phone_number'])) {
            $errors[] = "Invalid phone number format";
            debug_log("Phone validation failed: Invalid format", "ERROR");
        }
    }
    
    // Validate region (optional)
    if (!empty($data['region'])) {
        if (strlen($data['region']) > 100) {
            $errors[] = "Region name is too long";
            debug_log("Region validation failed: Too long", "ERROR");
        }
    }
    
    debug_log("Validation complete. Errors: " . count($errors));
    return $errors;
}

// Function to check database connection
function check_db_connection() {
    require_once 'db_connection.php';
    global $conn;
    
    if ($conn->connect_error) {
        debug_log("Database connection failed: " . $conn->connect_error, "ERROR");
        return false;
    }
    debug_log("Database connection successful");
    return true;
}

// Function to check if email exists
function check_email_exists($email) {
    global $conn;
    debug_log("Checking if email exists: $email");
    
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows > 0;
    
    debug_log("Email exists check result: " . ($exists ? "Yes" : "No"));
    $stmt->close();
    return $exists;
}

// Main debug process
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    debug_log("Registration attempt started");
    debug_log("POST data: " . print_r($_POST, true));
    
    // Check database connection
    if (!check_db_connection()) {
        debug_log("Registration failed: Database connection error", "ERROR");
        die("Database connection error. Check debug log for details.");
    }
    
    // Validate input
    $errors = validate_input($_POST);
    
    if (empty($errors)) {
        debug_log("Input validation passed");
        
        // Check if email exists
        if (check_email_exists($_POST['email'])) {
            $errors[] = "Email already exists";
            debug_log("Registration failed: Email already exists", "ERROR");
        } else {
            debug_log("Email is available");
            
            // Hash password
            $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            debug_log("Password hashed successfully");
            
            // Prepare insert statement
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone_number, region) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", 
                $_POST['full_name'],
                $_POST['email'],
                $hashed_password,
                $_POST['phone_number'],
                $_POST['region']
            );
            
            // Execute insert
            if ($stmt->execute()) {
                debug_log("User registered successfully. User ID: " . $stmt->insert_id);
                $_SESSION['user_id'] = $stmt->insert_id;
                $_SESSION['user_name'] = $_POST['full_name'];
                $_SESSION['user_email'] = $_POST['email'];
                debug_log("Session variables set");
            } else {
                debug_log("Registration failed: " . $stmt->error, "ERROR");
                $errors[] = "Registration failed. Please try again later.";
            }
            $stmt->close();
        }
    }
    
    // Log final result
    if (empty($errors)) {
        debug_log("Registration process completed successfully");
        header("Location: registration_success.php");
        exit();
    } else {
        debug_log("Registration failed with errors: " . implode(", ", $errors), "ERROR");
        $_SESSION['register_errors'] = $errors;
        $_SESSION['register_data'] = $_POST;
        header("Location: register.html");
        exit();
    }
} else {
    debug_log("Invalid request method: " . $_SERVER['REQUEST_METHOD'], "ERROR");
    header("Location: register.html");
    exit();
}

// Close database connection
if (isset($conn)) {
    $conn->close();
    debug_log("Database connection closed");
}
?> 