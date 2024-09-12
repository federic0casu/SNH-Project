<?php
include '../php/utils/config_and_import.php';
?>
<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Change Password</title>
        <link rel="stylesheet" href="../css/style.css">
        <link rel="stylesheet" href="../css/register.css">
    </head>
    <body>
        <header class="header">
            <div class="header-left" onclick="location.href='./../index.php';">
                <h1>Book Emporium</h1>
                <p>Your Source for Great Reads</p>
            </div>
        </header>
        <div class="container">
            <h2>Change password</h2>
            <div class="error-message" id="error-message" style="display:none;">
                An error was encountered.
            </div>
            <br>
            <form action="../php/start_password_change.php" method="post">
                
                <label for="password">Current Password:</label><br>
                <input type="password" id="password" name="password" autocomplete="current-password" minlength="8" required><br><br>

                <input type="hidden" name="csrf_token" value="<?php echo generate_or_get_csrf_token(); ?>">

                <input type="submit" value="Change Password">
            </form>
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
