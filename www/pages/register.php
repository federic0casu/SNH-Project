<?php
include_once "../php/utils/config_and_import.php";

//Check that the user isn't already logged in
$user_id = get_logged_user_id();
if($user_id > 0){
    redirect_to_index();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/register.css">
</head>
<body>
    <header class="header">
        <div class="header-left" onclick="location.href='../index.php';">
            <h1>Book Emporium</h1>
            <p>Your Source for Great Reads</p>
        </div>
    </header>
    <div class="container">
        <h2>Registration</h2>
        <div class="error-message" id="error-message" style="display:none;">
            We have a little problem. Please make sure you are logged in.
        </div>
        <br>
        <form action="../php/register.php" method="post">
            <label for="first_name">First Name:</label><br>
            <input type="text" id="first_name" name="first_name" required><br><br>

            <label for="last_name">Last Name:</label><br>
            <input type="text" id="last_name" name="last_name" required><br><br>

            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" required><br><br>

            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br><br>

            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" autocomplete="new-password" minlength="8" required><br><br>

            <label for="confirm_password">Confirm Password:</label><br>
            <input type="password" id="confirm_password" name="confirm_password" minlength="8" required><br><br>

            <input type="submit" value="Register">
        </form>
        <p>Already have an account? <a href="login.php">Login here</a>.</p>
    </div>
    <script>
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const error = urlParams.get('error');
        if (error != undefined) {
            document.getElementById('error-message').style.display = 'block';
            document.getElementById('error-message').innerText = error;
        }
    </script>
</body>
</html>
