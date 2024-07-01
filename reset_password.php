<?php
    $token = $_GET['token'];
    $token_hash = hash('sha256', $token);

    $conn = require __DIR__ . '/db_connect.php';

    $sql = "SELECT *
            FROM users
            WHERE reset_token_hash = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $token_hash);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if($user === null){
        echo "Invalid token";
        exit();
    }

    if(strtotime($user['reset_token_expires_at']) < time()){
        echo "Token expired";
        exit();
    }

    echo "Token is valid";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Reset Password</h1>

    <form action="process_password.php" method="post">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
        <input type="password" name="password" placeholder="New Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <button type="submit" name="reset_password">Submit</button>
    </form>
</body>
</html>