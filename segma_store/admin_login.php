<?php
session_start();
require_once 'db_connection.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$login_error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['admin_name']) && isset($_POST['admin_password'])) {
        $admin_name = $_POST['admin_name']; // No sanitization
        $admin_password = $_POST['admin_password']; // No sanitization for password for vulnerability demo

        if (empty($admin_name) || empty($admin_password)) {
            $login_error = "Please fill in both name and password.";
        } else {
            // INTENTIONALLY VULNERABLE TO SQL INJECTION & direct password check
            $query = "SELECT * FROM admins WHERE name = '$admin_name' AND password = '$admin_password'";
            // This query is vulnerable to SQLi on $admin_name and assumes $admin_password 
            // is a plain text or simply hashed password stored in the DB, NOT using password_hash()/password_verify().
            // Example SQLi for $admin_name: ' OR '1'='1
            // Example SQLi for $admin_password (if $admin_name is known): ' OR '1'='1
            
            $result = $conn->query($query);

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                // No password_verify, direct trust of the query result for vulnerability demo
                $_SESSION['admin_id'] = $row['admin_id'];
                $_SESSION['admin_name'] = $row['name'];
                header("Location: admin.php"); // Reverted redirect to admin.php
                exit();
            } else {
                $login_error = "Invalid name or password.";
                // Log error if any from DB for debugging, or failed attempt
                if ($conn->error) {
                    error_log("SQL Error on admin login: " . $conn->error . " Query: " . $query);
                } else {
                    error_log("Failed admin login attempt for name: " . $admin_name);
                }
            }
            if ($result && is_object($result)) {
                $result->close();
            }
        }
    } else {
        $login_error = "Form data is missing.";
    }
    if ($conn) {
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Merriweather:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles1.css">
    <title>Admin Login - SEGMA STORE</title>
    <style>
        html {
            font-size: 16px;
        }
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #121212;
            color: #e0e0e0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
            line-height: 1.6;
        }
        .admin-login-container {
            max-width: 420px;
            width: 100%;
            padding: 35px 40px;
            background: #1e1e1e;
            border-radius: 10px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.5), 0 4px 10px rgba(0,0,0,0.22);
            border: 1px solid #2d2d2d;
        }
        .admin-login-container h2 {
            color: #ffffff;
            text-align: center;
            margin-bottom: 30px;
            font-size: 1.75rem;
            font-weight: 500;
            letter-spacing: 0.5px;
        }
        .form-group {
            margin-bottom: 25px;
        }
        .form-group label {
            display: block;
            color: #b0b0b0;
            margin-bottom: 10px;
            font-size: 0.9rem;
            font-weight: 400;
        }
        .form-group input[type="text"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 14px 18px;
            border: 1px solid #383838;
            border-radius: 6px;
            background: #2c2c2c;
            color: #e0e0e0;
            font-size: 1rem;
            font-weight: 300;
            box-sizing: border-box;
            transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        .form-group input[type="text"]:focus,
        .form-group input[type="password"]:focus {
            outline: none;
            border-color: #4a90e2;
            background-color: #333333;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.15);
        }
        .admin-login-button {
            width: 100%;
            padding: 14px 20px;
            background: #4a90e2;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            font-size: 1.05rem;
            text-transform: capitalize;
            letter-spacing: 0.3px;
            transition: background-color 0.2s ease-in-out, transform 0.15s ease;
            margin-top: 10px;
        }
        .admin-login-button:hover {
            background-color: #357abd;
            transform: translateY(-1px);
        }
        .admin-login-button:active {
            transform: translateY(0);
            background-color: #2a6ead;
        }
        .admin-error {
            color: #ff7070;
            text-align: center;
            margin-bottom: 25px;
            padding: 12px 15px;
            background: rgba(255, 82, 82, 0.08);
            border: 1px solid rgba(255, 82, 82, 0.2);
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 400;
        }
        .logo-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo-header img {
            max-height: 45px;
            margin-bottom: 10px;
        }
        .logo-header .logo-text {
            font-size: 1.4rem;
            color: #ffffff;
            font-weight: 500;
            display: block;
        }
    </style>
</head>
<body>
    <div class="admin-login-container">
        <div class="logo-header">
            <img src="logo.ico" alt="SEGMA Logo">
            <span class="logo-text">SEGMA Admin</span>
        </div>
        <h2>Secure Panel Access</h2>
        <?php if (!empty($login_error)): ?>
            <div class="admin-error"><?php echo htmlspecialchars($login_error); ?></div>
        <?php endif; ?>
        <form action="admin_login.php" method="POST" novalidate>
            <div class="form-group">
                <label for="admin_name">Username</label>
                <input type="text" id="admin_name" name="admin_name" required placeholder="Enter your username">
            </div>
            <div class="form-group">
                <label for="admin_password">Password</label>
                <input type="password" id="admin_password" name="admin_password" required placeholder="Enter your password">
            </div>
            <button type="submit" class="admin-login-button">Login</button>
        </form>
    </div>
</body>
</html>
