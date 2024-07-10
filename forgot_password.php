<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <script>
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('success') && urlParams.get('success') === '1') {
                alert('Check your email for password reset link.');
            }
        };
    </script>
</head>
<body>
    <h1>Forgot Password</h1>
    <form action="send_password.php" method="post">
        <input type="email" name="email" placeholder="Email" required>
        <button type="submit" name="forgot_password">Submit</button>
    </form>
    <a href="index.php">Back to Home</a>
</body>
</html>
