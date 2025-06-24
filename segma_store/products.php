<?php
// WARNING: This code is intentionally VULNERABLE to SQL injection for educational purposes only.
// Never use this in production or on any public-facing site!

// Database connection
$host = 'localhost';
$dbname = 'segma_store';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Fetch products, optionally filter by search (vulnerable to SQL injection)
$search = isset($_GET['search']) ? $_GET['search'] : '';
if ($search !== '') {
    // VULNERABLE: direct user input in query!
    $query = "SELECT * FROM products WHERE name LIKE '%$search%'";
} else {
    $query = "SELECT * FROM products";
}
try {
    $stmt = $pdo->query($query);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Error fetching products: " . $e->getMessage());
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
    <title>SEGMA Products</title>
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
                <li><a href="products-vulnerable.php">Products</a></li>
                <li><a href="about.html">About</a></li>
                <li><a href="contact.html">Contact</a></li>
            </ul>
            <form class="search-form" method="get" action="products-vulnerable.php">
                <input type="text" name="search" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>" />
                <button type="submit"><span class="material-icons">search</span></button>
            </form>
        </nav>

        <div class="right-icons">
            <div class="nav-icons">
                <a href="wishlist.html" class="wishlist-icon"><span class="material-icons">favorite</span></a>
                <a href="cart.html"><span class="material-icons">shopping_cart</span></a>
                <a href="login.html"><span class="material-icons">person</span></a>
            </div>
            <div class="menu-icon" onclick="toggleMenu()">
                <span class="material-icons">menu</span>
            </div>
        </div>

        <div class="dropdown-menu" id="dropdownMenu">
            <ul>
                <li><a href="core-systems.php">Core Systems</a></li>
                <li><a href="visuals-hub.php">Visuals Hub</a></li>
                <li><a href="control-center.php">Control Center</a></li>
                <li><a href="sound-zone.php">Sound Zone</a></li>
                <li><a href="comfort-corner.php">Comfort Corner</a></li>
                <li><a href="gear-up.php">Gear Up</a></li>
            </ul>
        </div>
    </header>

    <main>
        <section class="all-products">
            <div class="products-header">
                <h2>Next Level Performance Awaits</h2>
                <p>Unleash your potential with premium technology built for speed, comfort, and efficiency</p>
            </div>
            <div class="products-container">
                <?php if (count($products) === 0): ?>
                    <p>No products found.</p>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                        <div class="product">
                            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                            <p><?php echo htmlspecialchars($product['description']); ?></p>
                            <p class="price">$<?php echo htmlspecialchars($product['price']); ?></p>
                            <a href="product-details.php?id=<?php echo $product['id']; ?>">
                                <button class="buy-btn">check it out</button>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
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

    <script>
        function toggleMenu() {
            var menu = document.getElementById('dropdownMenu');
            if (menu.style.display === 'block') {
                menu.style.display = 'none';
            } else {
                menu.style.display = 'block';
            }
        }
    </script>
</body>
</html>