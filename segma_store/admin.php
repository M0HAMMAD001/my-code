<?php
session_start();

// Check if the admin is logged in, otherwise redirect to login page
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_name'])) {
    header("Location: admin_login.php");
    exit();
}

// Logout logic
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin_login.php");
    exit();
}

$admin_name = htmlspecialchars($_SESSION['admin_name']);

// Include database connection (optional for dashboard, but good for consistency or future use)
// require_once 'db_connection.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SEGMA STORE</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Merriweather:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- <link rel="stylesheet" href="styles1.css"> -->
    <style>
        html {
            font-size: 16px;
        }
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #121212; /* Main background */
            color: #e0e0e0;
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
            line-height: 1.6;
        }

        .admin-sidebar {
            width: 250px; /* Slightly narrower sidebar */
            background-color: #1e1e1e; /* Sidebar background */
            border-right: 1px solid #2d2d2d;
            display: flex;
            flex-direction: column;
            padding: 20px 15px; /* Adjusted padding */
            box-shadow: 2px 0 10px rgba(0,0,0,0.2);
        }

        .admin-sidebar .logo-header {
            text-align: center;
            padding-bottom: 25px;
            margin-bottom: 25px;
            border-bottom: 1px solid #2d2d2d;
        }

        .admin-sidebar .logo-header img {
            max-height: 40px;
        }

        .admin-sidebar .logo-header .logo-text {
            font-size: 1.25rem;
            color: #ffffff;
            font-weight: 500;
            display: block;
            margin-top: 10px;
        }

        .admin-sidebar nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .admin-sidebar nav ul li a {
            display: flex;
            align-items: center;
            padding: 13px 18px; /* Adjusted padding */
            color: #b0b0b0;
            text-decoration: none;
            border-radius: 6px; /* Softer radius */
            margin-bottom: 8px;
            font-size: 0.95rem; /* Slightly larger font */
            font-weight: 400;
            transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out, padding-left 0.2s ease-in-out;
        }
        .admin-sidebar nav ul li a .material-icons {
            margin-right: 12px; /* More space for icon */
            font-size: 1.15rem; /* Icon size */
        }

        .admin-sidebar nav ul li a:hover,
        .admin-sidebar nav ul li a.active {
            background-color: #4a90e2; /* Accent color */
            color: #ffffff;
            padding-left: 22px; /* Indent on hover/active for effect */
        }
        
        .admin-sidebar .logout-link-container { /* Changed class name */
            margin-top: auto; /* Pushes logout to the bottom */
            padding-top: 20px;
            border-top: 1px solid #2d2d2d;
        }
        .admin-sidebar .logout-link-container a {
             display: flex;
            align-items: center;
            padding: 13px 18px;
            color: #b0b0b0;
            text-decoration: none;
            border-radius: 6px;
            margin-bottom: 0; /* No margin for last item */
            font-size: 0.95rem;
            font-weight: 400;
            transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out, padding-left 0.2s ease-in-out;
        }
        .admin-sidebar .logout-link-container a:hover {
            background-color: #c62828; /* Red for logout hover */
            color: #ffffff;
            padding-left: 22px;
        }

        .admin-main-content {
            flex-grow: 1;
            padding: 0; /* Remove padding to allow header to be full width */
            background-color: #121212; 
        }

        .admin-top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 35px; /* Adjusted padding */
            background-color: #1e1e1e; /* Header background */
            border-bottom: 1px solid #2d2d2d;
            color: #e0e0e0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        .admin-top-header h1 {
            font-size: 1.6rem; /* Adjusted size */
            margin: 0;
            font-weight: 500;
        }
        .admin-top-header .admin-info span {
            margin-right: 15px;
            font-size: 0.9rem;
        }
        .admin-top-header .admin-info a {
            color: #4a90e2;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: color 0.2s ease-in-out;
        }
         .admin-top-header .admin-info a:hover {
            color: #357abd;
            text-decoration: underline;
        }

        .page-content-area { /* Renamed from content-area for clarity */
            padding: 35px; /* Main content padding */
        }

        .dashboard-card {
            background-color: #1e1e1e; /* Card background */
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            border: 1px solid #2d2d2d;
        }
        .dashboard-card h2 {
            color: #ffffff;
            margin-top: 0;
            margin-bottom: 25px;
            border-bottom: 1px solid #383838;
            padding-bottom: 15px;
            font-size: 1.4rem;
            font-weight: 500;
        }
        .dashboard-card p {
            line-height: 1.7;
            font-size: 1rem;
            color: #b0b0b0;
            margin-bottom: 15px;
        }
        .dashboard-card p:last-child {
            margin-bottom: 0;
        }

        /* Responsive adjustments */
        @media (max-width: 992px) { /* Adjusted breakpoint */
            .admin-sidebar {
                width: 220px; /* Slimmer sidebar for tablets */
            }
             .admin-top-header {
                padding: 15px 25px;
            }
            .page-content-area {
                padding: 25px;
            }
        }

        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }
            .admin-sidebar {
                width: 100%;
                height: auto;
                padding: 15px;
                box-shadow: none;
                border-bottom: 1px solid #2d2d2d;
            }
            .admin-sidebar .logo-header {
                margin-bottom: 15px; padding-bottom: 15px;
            }
            .admin-sidebar nav ul li a {
                padding: 12px 15px;
            }
             .admin-sidebar nav ul li a:hover, 
             .admin-sidebar nav ul li a.active {
                padding-left: 18px;
             }
            .admin-sidebar .logout-link-container {
                margin-top: 15px; padding-top: 15px;
            }
            .admin-top-header {
                 padding: 15px 20px;
            }
            .admin-top-header h1 {
                font-size: 1.4rem;
            }
            .page-content-area {
                padding: 20px;
            }
             .dashboard-card {
                padding: 20px;
            }
        }

    </style>
</head>
<body>
    <aside class="admin-sidebar">
        <div class="logo-header">
            <img src="logo.ico" alt="SEGMA Logo">
            <span class="logo-text">SEGMA Admin</span>
        </div>
        <nav>
            <ul>
                <li><a href="admin.php" class="active"><span class="material-icons">dashboard</span>Dashboard</a></li>
                <li><a href="admin_users.php"><span class="material-icons">people_outline</span>Users</a></li> <!-- Changed icon -->
                <li><a href="admin_products.php"><span class="material-icons">inventory_2</span>Products</a></li>
                <li><a href="admin_orders.php"><span class="material-icons">receipt_long</span>Orders</a></li> <!-- Changed icon -->
                <li><a href="admin_settings.php"><span class="material-icons">settings_applications</span>Settings</a></li> <!-- Changed icon -->
            </ul>
        </nav>
        <div class="logout-link-container">
             <a href="admin.php?logout=1"><span class="material-icons">exit_to_app</span>Logout</a> <!-- Changed icon -->
        </div>
    </aside>

    <main class="admin-main-content">
        <header class="admin-top-header">
            <h1>Dashboard Overview</h1>
            <div class="admin-info">
                <span>Welcome, <?php echo $admin_name; ?>!</span>
                <a href="admin.php?logout=1">Sign Out</a> <!-- Changed text -->
            </div>
        </header>

        <section class="page-content-area">
            <div class="dashboard-card">
                <h2>Admin Panel Status</h2>
                <p>Welcome to the SEGMA Admin Control Panel. This central hub allows you to manage critical aspects of the store, including user accounts, product catalog, customer orders, and overall site configuration.</p>
                <p>Please use the sidebar navigation to access the various management modules. Ensure all changes are saved and actions are performed with care.</p>
            </div>
            <!-- Further dashboard widgets or content cards can be added here -->
            <!-- Example Widget Card
            <div class="dashboard-card" style="margin-top: 25px;">
                <h2>Quick Stats</h2>
                <p>Users: X | Products: Y | Orders Today: Z</p>
            </div>
             -->
        </section>
    </main>

    <!-- Footer can be omitted for admin panels or simplified if needed -->
    <!-- 
    <footer> 
        <p>&copy; <?php echo date("Y"); ?> SEGMA STORE - Admin Panel</p>
    </footer> 
    -->

    <!-- <script src="scripts.js"></script> -->
</body>
</html> 