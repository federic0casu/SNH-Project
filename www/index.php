<?php
include 'php/utils/config_and_import.php';

//Show a sample of random books
$books = get_random_books();

// Get user id
$user_id = get_logged_user_id();
$is_user_logged = true;

// If user id is less than 0 ==> user not logged in
if ($user_id < 0) {
    $is_user_logged = false;
    // Check if anonymous user cookie is not set
    if (!isset($_COOKIE['anonymous_user'])) {
        // Create anonymous session and set user id
        $user_id = create_anonymous_session();
    } else {
        // Get anonymous user id
        $user_id = get_anonymous_user_id();
        
        // If anonymous user id is still less than 0 ==> error
        if ($user_id < 0) {
            // Redirect to error page
            redirect_to_page("error");
        }
    }
}
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
            <?php if (!$is_user_logged): ?>
                <button class="login-button" onclick="location.href='pages/login.php';">Login</button>
                <button class="register-button" onclick="location.href='pages/register.php';">Register</button>
            <?php endif; ?>
            <?php if ($is_user_logged): ?>
                <form action="../php/utils/logout.php" method="post">
                    <input type="hidden" name="csrf_token" value="<?php echo generate_or_get_csrf_token(); ?>">
                    <input class="logout-button" type="submit" value="Logout">
                </form>
                <button class="password-button" onclick="location.href='pages/start_password_change.php';">Change Password</button>
                <button class="history-button" onclick="location.href='pages/order_history.php';">Order History</button>
            <?php endif; ?>
            <button class="books-button" onclick="location.href='pages/books.php';">Books</button>
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
                <form action="php/update_cart.php" method="post">
                    <input type="hidden" name="isbn" value="<?php echo urlencode($book['isbn']); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo generate_or_get_csrf_token(); ?>">
                    <input type="hidden" name="action" value="1">
                    <input type="submit" value="Add to Cart">
                </form>
            </div>
        <?php endforeach; ?>

    </section>

    <section class="about-us">
        <h2>About Us</h2>
        <p>Book Emporium is dedicated to providing a curated selection of high-quality books across various genres. We believe in the power of literature to inspire, educate, and entertain. Explore our collection and find your next favorite read!</p>
    </section>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Book Emporium. All rights reserved.</p>
    </footer>
</body>
</html>
