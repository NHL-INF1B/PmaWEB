<?php
session_start();

function checkLogin(string $email, string $password) {
    $error = array();

    if (!$email && empty($email)) {
        $error[] = "Het emailadres moet ingevuld zijn.";
    }
    if (!$password && empty($password)) {
        $error[] = "Het wachtwoord moet ingevuld zijn.";
    }
    if (empty($error)) {
        return false;
    } else {
        return $_SESSION['ERROR_MESSAGE'] = $error;
    }
}

function connectDB() {
    //Require ENV
    require_once('../env.php');

    // Connect to server (localhost server)
    $conn = mysqli_connect(HOSTNAME, USERNAME, PASSWORD, DATABASE);

    // Test the connection
    if (!$conn) {
        echo "database_connect_error";
    }

    return $conn;
}

function dbClose(mysqli $connection) {
    mysqli_close($connection);
}