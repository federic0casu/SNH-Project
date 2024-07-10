<?php
include '../php/utils/config_and_import.php';

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
    <title>Book Emporium</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/books.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="js/books.js"></script>
</head>
<body>
    <header class="header">
        <div class="header-left" onclick="location.href='../index.php';">
            <h1>Book Emporium</h1>
            <p>Your Source for Great Reads</p>
        </div>
        <div class="header-right">
            <?php if (!$is_user_logged): ?>
                <button class="login-button" onclick="location.href='login.php';">Login</button>
                <button class="register-button" onclick="location.href='register.php';">Register</button>
            <?php endif; ?>
            <?php if ($is_user_logged): ?>
                <form action="../php/utils/logout.php" method="post">
                    <input type="hidden" name="csrf_token" value="<?php echo generate_or_get_csrf_token(); ?>">
                    <input class="logout-button" type="submit" value="Logout">
                </form>
            <?php endif; ?>
            <button class="books-button" onclick="location.href='books.php';">Books</button>
            <button class="cart-button" onclick="location.href='shopping_cart.php';">Cart</button>
        </div>
    </header>

    <section>
        <form id="search_books_form" action="../php/books.php" method="post">
            <label for="search_query_title">Search by Title:</label>
            <input type="text" name="search_query_title" id="search_query_title">
            <label for="search_query_author">Search by Author:</label>
            <input type="text" name="search_query_author" id="search_query_author">
            <input type="submit" value="Search">
        </form>
        <section id="result-section">
            <h2>Results</h2>
        </section>
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