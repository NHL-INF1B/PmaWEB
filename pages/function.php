<?php
session_start();

function checkLogin(String $email, String $password)    {
    $error = array();

    if(!$email && empty($email))   {
        $error[] = "Het emailadres moet ingevuld zijn.";
    }
    if(!$password && empty($password)){
        $error[] ="Het wachtwoord moet ingevuld zijn.";
    }
    if(empty($error)){
        return false;
    }else{
        return $_SESSION['ERROR_MESSAGE'] = $error;
    }
}

function dbConnect(){
    $connection = mysqli_connect("localhost", "root", "");
    if (mysqli_connect_errno() > 0) {
        die("Failed to connect to MySQL: " . mysqli_connect_error());
    }
    mysqli_select_db($connection, "projectmanagementapp");
    return $connection;
}

function dbClose(mysqli $connection)
{
    mysqli_close($connection);
}