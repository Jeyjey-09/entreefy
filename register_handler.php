<?php
    include 'db_connect.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['register_email'];
        $password = password_hash($_POST['register_password'], PASSWORD_BCRYPT);

        // Combine firstname and lastname to create username
        $username = $firstname . ' ' . $lastname;

        $sql = "INSERT INTO users (username, email, password, first_name, last_name) VALUES ('$username', '$email', '$password', '$firstname', '$lastname')";

        if ($conn->query($sql) === TRUE) {
            echo "Registration successful!";
            header("Location: index.php");
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
    }
?>
