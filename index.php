<?php
    session_start();

    $identity = isset($_COOKIE['identity']) ? $_COOKIE['identity'] : '';
    $pass = isset($_COOKIE['pass']) ? $_COOKIE['pass'] : '';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register</title>
    <link rel="stylesheet" href="styles.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
 <div class="wrapper">
    <nav class="nav">
        <div class="nav-logo">
            <p>LOGO .</p>
        </div>
        <div class="nav-menu" id="navMenu">
            <ul>
                <li><a href="#" class="link active">Home</a></li>
                <li><a href="#" class="link">About</a></li>
                <li><a href="#" class="link">Venues</a></li>
                <li><a href="#" class="link">Profile</a></li>
            </ul>
        </div>
        <div class="nav-button">
            <?php if (!isset($_SESSION['user_id'])): ?>
                <button class="btn white-btn" id="loginBtn" onclick="showLogin()">Sign In</button>
                <button class="btn" id="registerBtn" onclick="showRegister()">Sign Up</button>
            <?php else: ?>
                <img src="images/user.png" class="profile-pic" onclick="toggleMenu()">
                <div class="sub-menu-wrap" id="subMenu">
                    <div class="sub-menu">
                        <div class="user-info">
                            <img src="images/user.png">
                            <h3><?php echo htmlspecialchars($_SESSION['username']); ?></h3>
                        </div>
                        <hr>
                        <a href="profile.php" class="sub-menu-link">
                            <img src="images/profile.png">
                            <p>Edit Profile</p>
                            <span>></span>
                        </a>
                        <a href="logout.php" class="sub-menu-link">
                            <img src="images/logout.png">
                            <p>Logout</p>
                            <span>></span>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="nav-menu-btn">
            <i class="bx bx-menu" onclick="myMenuFunction()"></i>
        </div>
    </nav>

    <!----------------------------------FORM BOX-------------------------------->

    <div class="form-box">

        <!---------------------Login Form---------------------->
        <div class="login-container" id="login">
            <div class="top">
                <span>Don't have an account? <a href="#" onclick="showRegister()">Sign Up</a></span>
                <header>Login</header>
            </div>
            <form method="POST" action="login_handler.php">
                <div class="input-box">
                    <input type="text" class="input-field" name="login_identifier" placeholder="Username or Email" value="<?php echo $identity ?>" required>
                    <i class="bx bx-user"></i>
                </div>
                <div class="input-box">
                    <input type="password" class="input-field" name="login_password" placeholder="Password" value="<?php echo $pass ?>" required>
                    <i class="bx bx-lock-alt"></i>
                </div>
                <div class="input-box">
                    <input type="submit" class="submit" name="login" value="Sign In">
                </div>
                <div class="two-col">
                    <div class="one">
                        <input type="checkbox" name="rememberMe" id="login-check">
                        <label for="login-check">Remember Me</label>
                    </div>
                    <!-- YOU NEED TO DO THIS! -->
                    <div class="two">
                        <label><a href="forgot_password.php">Forget password?</a></label>
                    </div>
                </div>
            </form>
        </div>

        <!---------------------Registration Form---------------------->
        <div class="register-container" id="register">
            <div class="top">
                <span>Have an account? <a href="#" onclick="showLogin()">Login</a></span>
                <header>Sign up</header>
            </div>
            <form method="POST" action="register_handler.php">
                <div class="two-forms">
                    <div class="input-box">
                        <input type="text" class="input-field" name="firstname" placeholder="Firstname" required>
                        <i class="bx bx-user"></i>
                    </div>
                    <div class="input-box">
                        <input type="text" class="input-field" name="lastname" placeholder="Lastname" required>
                        <i class="bx bx-user"></i>
                    </div>
                </div>
                <div class="input-box">
                    <input type="email" class="input-field" name="register_email" placeholder="Email" required>
                    <i class="bx bx-envelope"></i>
                </div>
                <div class="input-box">
                    <input type="password" class="input-field" name="register_password" placeholder="Password" required>
                    <i class="bx bx-lock-alt"></i>
                </div>
                <div class="input-box">
                    <input type="submit" class="submit" name="register" value="Register">
                </div>
            </form>
        </div>
    </div>

 </div>

<script>
    function myMenuFunction() {
        var i = document.getElementById("navMenu");

        if(i.className === "nav-menu") {
            i.className += " responsive";
        } else {
            i.className = "nav-menu";
        }
   }

    var loginBtn = document.getElementById("loginBtn");
    var registerBtn = document.getElementById("registerBtn");
    var loginContainer = document.getElementById("login");
    var registerContainer = document.getElementById("register");

    function showLogin(){
        loginContainer.style.left = "4px";
        registerContainer.style.right = "-520px";
        loginBtn.classList.add("white-btn");
        registerBtn.classList.remove("white-btn");
        loginContainer.style.opacity = 1;
        registerContainer.style.opacity = 0;
    }
    
    function showRegister(){
        loginContainer.style.left = "-510px";
        registerContainer.style.right = "5px";
        loginBtn.classList.remove("white-btn");
        registerBtn.classList.add("white-btn");
        loginContainer.style.opacity = 0;
        registerContainer.style.opacity = 1;
    }

    let subMenu = document.getElementById("subMenu");

    function toggleMenu(){
        subMenu.classList.toggle("open-menu");
    }

</script>

</body>
</html>
