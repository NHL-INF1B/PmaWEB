<?php
session_start();

function checkLogin(string $email, string $password)
{
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

function dbConnect()
{
    require_once('env.dist.php');
    $connection = mysqli_connect(HOSTNAME, USERNAME, PASSWORD);
    if (mysqli_connect_errno() > 0) {
        die("Failed to connect to MySQL: " . mysqli_connect_error());
    }
    mysqli_select_db($connection, DATABASE);
    return $connection;
}

function dbClose(mysqli $connection)
{
    mysqli_close($connection);
}