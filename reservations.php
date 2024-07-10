<?php
    // Start the session
    session_start();

    // Include database connection
    include 'db_connect.php';

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        echo '<script>
            alert("You need to login first.");
            window.location.href = "index.php";
            </script>';
        exit();
    }

    // Fetch user details from the database
    $user_id = $_SESSION['user_id'];

    // Fetch current and past reservations
    $current_reservations_sql = "SELECT er.title, er.date, er.time, v.name AS venue 
                                FROM event_reservations er 
                                JOIN event_venue ev ON er.id = ev.event_id 
                                JOIN venues v ON ev.venue_id = v.id 
                                WHERE er.organizer_id = ? AND er.date >= CURDATE()";
    $past_reservations_sql = "SELECT er.title, er.date, er.time, v.name AS venue 
                            FROM event_reservations er 
                            JOIN event_venue ev ON er.id = ev.event_id 
                            JOIN venues v ON ev.venue_id = v.id 
                            WHERE er.organizer_id = ? AND er.date < CURDATE()";

    $current_stmt = $conn->prepare($current_reservations_sql);
    $current_stmt->bind_param("i", $user_id);
    $current_stmt->execute();
    $current_reservations_result = $current_stmt->get_result();

    $past_stmt = $conn->prepare($past_reservations_sql);
    $past_stmt->bind_param("i", $user_id);
    $past_stmt->execute();
    $past_reservations_result = $past_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Reservations</title>
    <link rel="stylesheet" href="reservations.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f1f1; /* light peach background */
        }

        .card-header {
            background-color: #f28e76; /* peach */
            color: white;
        }

        .card-header h4 {
            color: white;
        }

        .nav-tabs .nav-link {
            color: #333; /* black */
        }

        .nav-tabs .nav-link.active {
            color: #f28e76; /* peach */
            background-color: white;
            border-color: #f28e76;
        }

        .nav-tabs .nav-link:hover {
            color: #f28e76; /* peach */
        }

        .btn-primary {
            background-color: #f28e76; /* peach */
            border-color: #f28e76; /* peach */
        }

        .btn-primary:hover {
            background-color: #f7a693; /* darker peach */
            border-color: #f7a693; /* darker peach */
        }

        h4 {
            color: #333; /* black */
        }

        .card {
            background-color: white;
            border: none;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .container {
            margin-top: 100px;
            max-width: 75%px;
        }

        p {
            color: #333; /* black */
        }

        .nav-tabs {
            border-bottom: 1px solid #f28e76; /* peach */
        }

        .tab-content {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <nav class="nav">
        <div class="nav-logo">
            <p>LOGO .</p>
        </div>
        <div class="nav-menu" id="navMenu">
            <ul>
                <li><a href="index.php" class="link active">Home</a></li>
                <li><a href="#" class="link">About</a></li>
                <li><a href="#" class="link">Venues</a></li>
                <li><a href="reservations.php" class="link">Reservations</a></li>
            </ul>
        </div>
        <div class="nav-button">
            <?php if (!isset($_SESSION['user_id'])): ?>
                <button class="btn white-btn" id="loginBtn" onclick="showLogin()">Sign In</button>
                <button class="btn" id="registerBtn" onclick="showRegister()">Sign Up</button>
            <?php else: ?>
                <img src="images/user.png" class="profile-pic" id="top-profile" onclick="toggleMenu()">
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

    <div class="container py-4">
        <div class="card">
            <div class="card-header">
                <h4 class="font-weight-bold mb-0">User Reservations</h4>
            </div>
            <div class="card-body">
                <h4>Current Reservations</h4>
                <div id="current-reservations">
                    <?php
                    if ($current_reservations_result->num_rows > 0) {
                        while ($reservation = $current_reservations_result->fetch_assoc()) {
                            echo "<p>" . htmlspecialchars($reservation['title']) . " at " . htmlspecialchars($reservation['venue']) . " on " . htmlspecialchars($reservation['date']) . " at " . htmlspecialchars($reservation['time']) . "</p>";
                        }
                    } else {
                        echo "<p>No current reservations.</p>";
                    }
                    ?>
                </div>
                <h4 class="mt-4">Past Reservations</h4>
                <div id="past-reservations">
                    <?php
                    if ($past_reservations_result->num_rows > 0) {
                        while ($reservation = $past_reservations_result->fetch_assoc()) {
                            echo "<p>" . htmlspecialchars($reservation['title']) . " at " . htmlspecialchars($reservation['venue']) . " on " . htmlspecialchars($reservation['date']) . " at " . htmlspecialchars($reservation['time']) . "</p>";
                        }
                    } else {
                        echo "<p>No past reservations.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
<script>
    function myMenuFunction() {
        var i = document.getElementById("navMenu");

        if(i.className === "nav-menu") {
            i.className += " responsive";
        } else {
            i.className = "nav-menu";
        }
   }

    let subMenu = document.getElementById("subMenu");

    function toggleMenu(){
        subMenu.classList.toggle("open-menu");
    }

</script>
</html>

