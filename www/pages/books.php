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
        <div class="header-left">
            <h1>Book Emporium</h1>
            <p>Your Source for Great Reads</p>
        </div>
        <div class="header-right">
            <button class="login-button" onclick="location.href='login.php';">Login</button> 
            <button class="register-button" onclick="location.href='register.php';">Register</button> 
            <button class="cart-button" onclick="location.href='shopping_cart.php';">Cart</button>
        </div>
    </header>

    <section>
        <form action="../php/books.php" method="post">
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