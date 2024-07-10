<?php
    // Start the session
    session_start();

    // Include database connection
    include 'db_connect.php';

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    // Fetch user details from the database
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT username, first_name, last_name, email FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
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

        .card-header h4{
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

        .profile-img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
        }

        .btn-primary {
            background-color: #f28e76; /* peach */
            border-color: #f28e76; /* peach */
        }

        .btn-primary:hover {
            background-color: #f7a693; /* darker peach */
            border-color: #f7a693; /* darker peach */
        }

        .form-control {
            border-color: #f28e76; /* peach */
        }

        .form-control:focus {
            border-color: #f7a693; /* darker peach */
            box-shadow: none;
        }

        h4 {
            color: #333; /* black */
        }

        .card {
            background-color: white;
            border: none;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            background-color: white;
        }

        .container {
            margin-top: 50px;
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
    <div class="container py-4">
        <div class="card">
            <div class="card-header">
                <h4 class="font-weight-bold mb-0">Account Settings</h4>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs mb-4">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#profile">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#password">Change Password</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="profile">
                        <div class="row">
                            <div class="col-md-3 text-center">
                                <img src="images/user.png" alt="Profile Picture" class="profile-img">
                                <div class="mt-2">
                                    <h4><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h4>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <form id="profile-form">
                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="firstname">First Name</label>
                                        <input type="text" class="form-control" id="firstname" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="lastname">Last Name</label>
                                        <input type="text" class="form-control" id="lastname" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                    <a href="index.php" class="btn btn-primary">Go Back</a>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="password">
                        <div class="col-md-9 mx-auto">
                            <form id="password-form">
                                <div class="form-group">
                                    <label for="current-password">Current Password</label>
                                    <input type="password" class="form-control" id="current-password" name="current_password">
                                </div>
                                <div class="form-group">
                                    <label for="new-password">New Password</label>
                                    <input type="password" class="form-control" id="new-password" name="new_password">
                                </div>
                                <div class="form-group">
                                    <label for="confirm-password">Confirm New Password</label>
                                    <input type="password" class="form-control" id="confirm-password" name="confirm_password">
                                </div>
                                <button type="submit" class="btn btn-primary">Change Password</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Handle profile form submission
        $('#profile-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: 'update_profile.php',
                data: $(this).serialize(),
                success: function(response) {
                    alert(response);
                },
                error: function() {
                    alert('An error occurred while updating profile');
                }
            });
        });

        // Handle password form submission
        $('#password-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: 'change_password.php',
                data: $(this).serialize(),
                success: function(response) {
                    alert(response);
                },
                error: function() {
                    alert('An error occurred while changing password');
                }
            });
        });
    </script>
</body>

</html>
