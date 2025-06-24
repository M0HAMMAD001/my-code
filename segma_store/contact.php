<?php
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // No input sanitization
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Vulnerable to XSS
    echo "<h3>Thank you for your message, $name!</h3>";
    echo "<p>We will reply to your email at $email.</p>";
    echo "<p>Your message: $message</p>";

    // Message is stored in the database without escaping
    // there is a vulnerability // put in the message bar '; DROP TABLE users; --    to delet table user 
 
    $query = "INSERT INTO contact_inquiries (name, email, message) VALUES ('$name', '$email', '$message')";
    mysqli_query($conn, $query);
}
?>
