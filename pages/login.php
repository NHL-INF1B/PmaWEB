<?php
require_once "function.php";
$_SESSION['projectId'] = "";

$error = array();

if (isset($_POST['login'])) {
    $inputEmail = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
    $inputPassword = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

    if (!checkLogin($inputEmail, $inputPassword)) {
        $conn = dbConnect();

        $sql = "SELECT id, email, password
                FROM user 
                WHERE email =?";

        $stmt = mysqli_prepare($conn, $sql) or die("prepare error");
        mysqli_stmt_bind_param($stmt, 's', $inputEmail) or die("bind param error");
        mysqli_stmt_execute($stmt) or die("execute error");
        mysqli_stmt_bind_result($stmt, $id, $email, $password);
        mysqli_stmt_store_result($stmt);

        $error[] = mysqli_stmt_num_rows($stmt);
        if (mysqli_stmt_num_rows($stmt) == 1) {
            while (mysqli_stmt_fetch($stmt)) {
                if (password_verify($inputPassword, $password)) {
                    $_SESSION['userId'] = $id;
                    header("location: ../index.php");
                    exit;
                } else {
                    $error[] = "De inloggegevens zijn 1.";
                }
            }
        } else {
            $error[] = "De inloggegevens zijn verkeerd.2";
        }
        mysqli_stmt_close($stmt);
        dbClose($conn);
    }

    if (!empty($error)) {
        $_SESSION['ERROR_MESSAGE'] = $error;
    }
}


?>


<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/login.css">
</head>

<body>
<section id="header">
</section>

<section id="content" class="container mb-3">
    <div class="height">
        <div class="row">
            <div class="col-md-12 text-center mt-2">
                <h1>Inloggen</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 text-center mt-2">
                <?php
                if (isset($_POST['login']) && !empty($_SESSION['ERROR_MESSAGE'])) {
                    ?>
                    <div class="row">
                        <div class="col-md-12 p-0">
                            <div class="alert alert-danger text-black fw-bold p-4 rounded mb-3" role="alert">
                                <ul>
                                    <?php
                                    foreach ($_SESSION['ERROR_MESSAGE'] as $errorMsg) {
                                        echo '<li>' . $errorMsg . '</li>';
                                    }
                                    unset($_SESSION['ERROR_MESSAGE']);
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>

                <div class="box row rounded">
                    <div class="col-md-12">
                        <h2 class="form-heading mt-3">Inloggen</h2>
                        <form class="mb-3" action="<?= htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
                            <div class="form-group">
                                <input class="form-control mt-2" placeholder="Gebruikersnaam" type="text"
                                       name="username" value="<?php if (isset($_POST['login'])) {
                                    echo htmlentities($_POST['username']);
                                } ?>">
                                <input class="form-control mt-3" placeholder="Wachtwoord" type="password"
                                       name="password">
                                <input class="btn btn-danger mt-3" type="submit" name="login" value="Inloggen">
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6 text-center mt-2">
                <?php
                if (isset($_POST['register']) && !empty($_SESSION['ERROR_MESSAGE'])) {
                    ?>
                    <div class="row">
                        <div class="col-md-12 p-0">
                            <div class="alert alert-danger text-black fw-bold p-4 rounded mb-3" role="alert">
                                <ul>
                                    <?php
                                    foreach ($_SESSION['ERROR_MESSAGE'] as $errorMsg) {
                                        echo '<li>' . $errorMsg . '</li>';
                                    }
                                    unset($_SESSION['ERROR_MESSAGE']);
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <?php
                }
                ?>
            </div>
        </div>
</section>
</body>
</html>