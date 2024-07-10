<?php
    include 'db_connect.php';
    session_start();

    if(isset($_COOKIE['identity']) && isset($_COOKIE['pass'])) {
        $_POST['login_identifier'] = $_COOKIE['identity'];
        $_POST['login_password'] = $_COOKIE['pass'];
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $identifier = $_POST['login_identifier'];
        $password = $_POST['login_password'];
        $remember_me = $_POST['rememberMe'];

        // Check if the identifier is an email or a username
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $sql = "SELECT * FROM users WHERE email='$identifier'";
        } else {
            $sql = "SELECT * FROM users WHERE username='$identifier'";
        }
        
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                if(isset($remember_me)) {
                    setcookie('identity', $_REQUEST['login_identifier'] , time() + (86400 * 30), "/");
                    setcookie('pass', $_REQUEST['login_password'], time() + (86400 * 30), "/");
                }
                else {
                    setcookie('identity', $_REQUEST['login_identifier'] , time() - 3600, "/");
                    setcookie('pass', $_REQUEST['login_password'], time() - 3600, "/");
                }
                header("Location: index.php");
            } else {
                echo "Invalid password!";
            }
        } else {
            echo '<script>
            alert("No user found with this identifier!");
            window.location.href = "index.php";
            </script>';
        }

        $conn->close();
    }
?>
