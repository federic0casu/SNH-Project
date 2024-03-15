<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Emporium</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/cart.css">
</head>
<body>
    <header class="header">
        <div class="header-left">
            <h1>Book Emporium</h1>
            <p>Your Source for Great Reads</p>
        </div>
        <div class="header-right">
            <button class="home-button" onclick="location.href='../../index.php';">Home</button>
            <button class="login-button" onclick="location.href='login.php';">Login</button> 
            <button class="register-button" onclick="location.href='register.php';">Register</button> 
        </div>
    </header>

    <h2>Shopping Cart</h2>
    <table id="cart">
        <thead>
            <tr>
                <th>Item</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
        <tfoot>
            <tr>
                <td colspan="1" class="total">Total:</td>
                <td id="total">0.0 $</td>
            </tr>
        </tfoot>
    </table>
    <br>
    <br>
    <button class="checkout-button">Proceed to Checkout</button>

    <section class="about-us">
        <h2>About Us</h2>
        <p>Book Emporium is dedicated to providing a curated selection of high-quality books across various genres. We believe in the power of literature to inspire, educate, and entertain. Explore our collection and find your next favorite read!</p>
    </section>

    <footer>
        <p>&copy; 2023 Book Emporium. All rights reserved.</p>
    </footer>
</body>
</html>
