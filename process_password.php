<?php
    $token = $_POST['token'];
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

    if($_POST['password'] !== $_POST['confirm_password']){
        die("Passwords do not match");
    }

    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $sql = "UPDATE users
            SET password = ?,
            reset_token_hash = NULL,
            reset_token_expires_at = NULL
            WHERE user_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $password, $user['user_id']);
    $stmt->execute();

    echo "Password reset successfully";
?>