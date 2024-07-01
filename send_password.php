<?php
    $email = $_POST['email'];
    $token = bin2hex(random_bytes(16));
    $token_hash = hash('sha256', $token);
    $expiry = date("Y-m-d H:i:s",time() + 60*30);
    
    $conn = require __DIR__ . '/db_connect.php';

    $sql = "UPDATE users
            SET reset_token_hash = ?, 
            reset_token_expires_at = ?
            WHERE email = ?";

    $stmt = $conn->prepare($sql); 
    $stmt->bind_param('sss', $token_hash, $expiry, $email);
    $stmt->execute();

    if($conn->affected_rows){
        $mail = require __DIR__ . '/mailer.php';
        $mail->setFrom("noreply@example.com");
        $mail->addAddress($email);
        $mail->Subject = "Password Reset";
        $mail->Body = <<<END

        <h1>Password Reset</h1>
        <p>Click the link below to reset your password</p>
        <a href="http://localhost/finals/entreefy/reset_password.php?token=$token">Reset Password</a>
        END;

        try{
            $mail->send();
        } catch (Exception $e){
            echo "Error: " . $mail->ErrorInfo;
        }
    }

    echo "Check your email for password reset link";

    $stmt->close();
    $conn->close();
?>