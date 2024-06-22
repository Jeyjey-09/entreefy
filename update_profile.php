<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo 'Unauthorized access';
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_POST['username'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];

$_SESSION['username'] = $username;

$sql = "UPDATE users SET username = ?, first_name = ?, last_name = ?, email = ? WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssi", $username, $first_name, $last_name, $email, $user_id);

if ($stmt->execute()) {
    echo 'Profile updated successfully';
} else {
    echo 'An error occurred while updating profile';
}
?>
