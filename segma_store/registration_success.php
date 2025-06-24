<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles1.css">
    <title>Registration Successful - SEGMA STORE</title>
    <style>
        .success-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            background: #1a1a1a;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .success-icon {
            color: #4CAF50;
            font-size: 64px;
            margin-bottom: 20px;
        }

        .success-message {
            color: #b8d7e0;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .welcome-message {
            color: #b5bbba;
            margin-bottom: 30px;
        }

        .continue-btn {
            display: inline-block;
            padding: 12px 30px;
            background: #b8d7e0;
            color: #1a1a1a;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            transition: background 0.3s;
        }

        .continue-btn:hover {
            background: #9bc5d1;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo">
            <img src="logo.ico" alt="SEGMA Logo" />
            <span class="logo-text">SEGMA</span>
        </div>
        
        <nav class="main-nav">
            <div class="nav-icons">
                <a href="index.html"><span class="material-icons">home</span></a>
            </div>
            <ul class="items">
                <li><a href="products.html">Products</a></li>
                <li><a href="about.html">About</a></li>
                <li><a href="contact.html">Contact</a></li>
            </ul>
        </nav>

        <div class="right-icons">
            <div class="nav-icons">
                <a href="wishlist.html" class="wishlist-icon"><span class="material-icons">favorite</span></a>
                <a href="cart.html"><span class="material-icons">shopping_cart</span></a>
                <a href="login.html"><span class="material-icons">person</span></a>
            </div>
        </div>
    </header>

    <main>
        <div class="success-container">
            <span class="material-icons success-icon">check_circle</span>
            <h1 class="success-message">Registration Successful!</h1>
            <p class="welcome-message">Welcome to SEGMA STORE, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</p>
            <p class="welcome-message">Your account has been created successfully.</p>
            <a href="index.html" class="continue-btn">Continue Shopping</a>
        </div>
    </main>

    <footer>
        <hr>
        <nav>
            <a href="contact.html">Contact</a> |
            <a href="about.html">About</a> |
            <a href="faq.html">FAQ</a> |
            <a href="terms.html">Terms</a>
        </nav>
        <p>&copy; 2025 SEGMA STORE</p>
    </footer>

    <script src="scripts.js"></script>
</body>
</html> 