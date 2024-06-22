<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo 'Unauthorized access';
    exit();
}

$user_id = $_SESSION['user_id'];
$current_password = $_POST['current_password'];
$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];

if ($new_password !== $confirm_password) {
    echo 'New passwords do not match';
    exit();
}

$sql = "SELECT password FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!password_verify($current_password, $user['password'])) {
    echo 'Current password is incorrect';
    exit();
}

$new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

$sql = "UPDATE users SET password = ? WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $new_hashed_password, $user_id);

if ($stmt->execute()) {
    echo 'Password changed successfully';
} else {
    echo 'An error occurred while changing password';
}
?>
