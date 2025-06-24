<?php
session_start();

// Database connection
$host = 'localhost';
$dbname = 'segma_store';
$username = 'root';
$password = '';  // Default Bitnami MySQL password is empty

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    header("Location: login.html?error=Database connection failed");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $username = $_GET['username'] ?? '';
    $password = $_GET['password'] ?? '';

    if (empty($username) || empty($password)) {
        header("Location: login.html?error=Please fill in all fields");
        exit();
    }

    // Vulnerable SQL query - intentionally not using prepared statements
    $query = "SELECT * FROM users WHERE username = '$username' OR password = '$password'";
    
    try {
        $stmt = $pdo->query($query);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: index.html");
            exit();
        } else {
            header("Location: login.html?error=Invalid username or password");
            exit();
        }
    } catch(PDOException $e) {
        header("Location: login.html?error=Login failed");
        exit();
    }
} else {
    header("Location: login.html?error=Invalid request method");
    exit();
}
?>