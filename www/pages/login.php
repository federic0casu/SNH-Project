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
        <title>Login</title>
        <link rel="stylesheet" href="../css/style.css">
        <link rel="stylesheet" href="../css/login.css">
    </head>
    <body>
        <div class="container">
            <h2>Login</h2>
            <div class="error-message" id="error-message" style="display:none;">
                We have a little problem. Please make sure you are logged in.
            </div>
            <br>
            <form action="../php/login.php" method="post">
                <label for="username">Username:</label><br>
                <input type="text" id="username" name="username" required><br><br>

                <label for="password">Password:</label><br>
                <input type="password" id="password" name="password" required><br><br>

                <input type="submit" value="Login">
            </form>
            <p>Don't have an account? <a href="register.php">Register here</a>.</p>
            <p>Lost your password? <a href="send_password_reset.php">Reset it here</a>.</p>
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
