<?php
$servername = "db";     // Service name from Docker Compose
$username   = "book_shop";
$password   = "book_shop_password";
$dbname     = "book_shop_db";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("[ERROR] Connection failed: " . mysqli_connect_error());
}
?>
