<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Password Reset</title>
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
            <h2>Set New Password</h2>
            <div class="error-message" id="error-message" style="display:none;">
                An error was encountered.
            </div>
            <br>
            <form action="../php/password_reset.php" method="post">
                <input type="hidden" id="reset_token" name="reset_token" value="">
                <label for="password">New Password:</label><br>
                <input type="password" id="password" name="password" autocomplete="new-password" minlength="8" required><br><br>

                <label for="confirm_password">Confirm New Password:</label><br>
                <input type="password" id="confirm_password" name="confirm_password" minlength="8" required><br><br>

                <input type="submit" value="Set new password">
            </form>
        </div>
        <script>
            const queryString = window.location.search;
            const urlParams = new URLSearchParams(queryString);
            const resetToken = urlParams.get('reset_token')
            document.getElementById('reset_token').value = resetToken;
            const error = urlParams.get('error');
            if (error != undefined) {
                document.getElementById('error-message').style.display = 'block';
                document.getElementById('error-message').innerText = error;
            }
        </script>
    </body>
</html>
