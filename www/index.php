<?php
include 'php/db_connect.php';

$sql = "SELECT * FROM books ORDER BY RAND() LIMIT 4";
$result = $conn->query($sql);

$books = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
}

$conn->close();

/*
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== 1) {
    header("Location: ../index.html?bad_attempt=true");
    exit();
}
*/
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Emporium - Your Source for Great Reads</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/index.css">
</head>
<body>
    <header class="header">
        <div class="header-left">
            <h1>Book Emporium</h1>
            <p>Your Source for Great Reads</p>
        </div>
        <div class="header-right">
            <button class="login-button" onclick="location.href='pages/login.html';">Login</button> 
            <button class="login-button" onclick="location.href='pages/register.html';">Register</button> 
            <button class="cart-icon" onclick="showCart()">🛒</button>
        </div>
    </header>

    <div id="cart-container">
        <div class="close-button" onclick="showCart()">X</div>
        <div class="cart-item">
            <img src="path_to_image.jpg" alt="Book Title">
            <div>
                <h3>Book Title</h3>
                <p>Author: Author Name</p>
                <p>Price: $XX.YY</p>
            </div>
        </div>

        <div class="total-price">
            Total: $ZZ.ZZ
        </div>

        <button class="checkout-button">Proceed to Checkout</button>
    </div>
    <script>
        // JavaScript to show/hide cart container
        function showCart() {
            if (document.getElementById("cart-container").style.display == 'block')
                document.getElementById("cart-container").style.display = 'none';
            else 
                document.getElementById("cart-container").style.display = 'block';
        }
    </script>
    
    <section class="featured-books">
        <h2>Featured Books</h2>
        
        <?php foreach ($books as $book): ?>
            <div class="book">
                <img src="<?php echo $book['image_url_L']; ?>" alt="<?php echo $book['book_title']; ?>">
                <h3><?php echo $book['book_title']; ?></h3>
                <p>Author: <?php echo $book['book_author']; ?></p>
                <p>Price: $XX.YY</p>
                <button>Add to Cart</button>
            </div>
        <?php endforeach; ?>

    </section>

    <section class="about-us">
        <h2>About Us</h2>
        <p>Book Emporium is dedicated to providing a curated selection of high-quality books across various genres. We believe in the power of literature to inspire, educate, and entertain. Explore our collection and find your next favorite read!</p>
    </section>

    <footer>
        <p>&copy; 2023 Book Emporium. All rights reserved.</p>
    </footer>
</body>
</html>
