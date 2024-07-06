<?php
include 'php/utils/csrf.php';
include 'php/utils/db_manager.php';

//Show a sample of random books
$db = DBManager::get_instance();
$query = "SELECT * FROM books ORDER BY RAND() LIMIT 4";
$books = $db->exec_query("SELECT", $query);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Emporium - Your Source for Great Reads</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <header class="header">
        <div class="header-left">
            <h1>Book Emporium</h1>
            <p>Your Source for Great Reads</p>
        </div>
        <div class="header-right">
            <button class="login-button" onclick="location.href='pages/login.php';">Login</button> 
            <button class="register-button" onclick="location.href='pages/register.php';">Register</button> 
            <form action="../php/utils/logout.php" method="post">
                <input type="hidden" name="csrf_token" value="<?php echo generate_or_get_csrf_token(); ?>">
                <input type="submit" value="Logout">
            </form>
            <button class="cart-button" onclick="location.href='pages/shopping_cart.php';">Cart</button>
        </div>
    </header>
    
    <section class="featured-books">
        <h2>Featured Books</h2>
        
        <?php foreach ($books as $book): ?>
            <div class="book">
                <img src="<?php echo $book['image_url_L']; ?>" alt="<?php echo $book['book_title']; ?>">
                <h3><?php echo $book['book_title']; ?></h3>
                <p>Author: <?php echo $book['book_author']; ?></p>
                <button onclick="location.href='php/update_cart.php?isbn=<?php echo urlencode($book['isbn']); ?>&action=1'">Add to Cart</button>
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
