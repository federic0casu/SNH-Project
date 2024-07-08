<?php
include_once "../php/utils/config_and_import.php";

// Check that the user isn't already logged in
$user_id = get_logged_user_id();
if($user_id > 0){
    redirect_to_index();
}

// Sanitize the error parameter
$error_message = "";
if (isset($_GET['error'])) {
    $error_message = htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8');
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
            <?php if ($error_message): ?>
                <script>
                    // Pass the PHP sanitized error message to JavaScript
                    document.getElementById('error-message').style.display = 'block';
                    document.getElementById('error-message').innerText = "<?php echo $error_message; ?>";
                </script>
            <?php endif; ?>
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
    </div>
</body>
</html>
