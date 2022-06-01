<?php
require_once "function.php";
if (isset($_POST['join'])) {
    if (isset($_SESSION['id'])) {
        addMember($_SESSION['projectid'], $_SESSION['id'], getProjectLidRole());
        ?>
        <!DOCTYPE html>
        <html lang="en">
            <head>
                <meta charset="utf-8" />
                <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
                <title>ProjectManagementApp</title>
                <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
                <!-- Bootstrap icons-->
                <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
                <!-- Core theme CSS (includes Bootstrap)-->
                <link href="../css/styles.css" rel="stylesheet" />
            </head>
            <body id="page-top">
                <!-- Mashead header-->
                <header class="masthead">
                    <div class="container px-5">
                        <div class="row gx-5 align-items-center">
                            <div class="col-lg-6">
                                <!-- Mashead text and app badges-->
                                <div class="mb-5 mb-lg-0 text-center text-lg-start">
                                    <h1 class="display-1 lh-1 mb-3">Succes!</h1>
                                    <p class="lead fw-normal text-muted mb-5">Je bent nu een projectlid in: <span style="color: #009BAA;" class="lead fw-normal mb-5"><?php echo $_SESSION['project']['name']; ?></span></p>
                                    <div class="d-flex flex-column flex-lg-row align-items-center">
                                        <a class="me-lg-3 mb-4 mb-lg-0" href="#"><img class="app-badge" src="../assets/img/google-play-badge.svg" alt="Googleplaystore" /></a>
                                        <a href="#"><img class="app-badge" src="../assets/img/app-store-badge.svg" alt="Appstore" /></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <!-- Masthead device mockup feature-->
                                <div class="masthead-device-mockup">
                                    <svg class="circle" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                                        <defs>
                                            <linearGradient id="circleGradient" gradientTransform="rotate(45)">
                                                <stop class="gradient-start-color" offset="0%"></stop>
                                                <stop class="gradient-end-color" offset="100%"></stop>
                                            </linearGradient>
                                        </defs>
                                        <circle cx="50" cy="50" r="50"></circle></svg
                                    >
                                    <div class="device-wrapper">
                                        <div class="device" data-device="iPhoneX" data-orientation="portrait" data-color="black">
                                            <div class="screen bg-black">
                                                <img class="img-fluid pe-none" src="../assets/img/pma.png" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
            </body>
        </html>
        <?php
        session_unset();
        session_destroy();
    } else {
        header("location: login.php");
    }
} else {
    echo '404 not found';
}

function addMember($projectid, $userid, $roleid) {
    $conn = connectDB();
    $query = "INSERT INTO projectmember (project_id, user_id, role_id) VALUES (?,?,?)";
    $stmt = mysqli_prepare($conn, $query) or die("prepare error");
    mysqli_stmt_bind_param($stmt, "iii", $projectid, $userid, $roleid) or die("bind param error");
    mysqli_stmt_execute($stmt) or die("execute error");
    mysqli_stmt_close($stmt);
}

function getProjectLidRole() {
    $conn = connectDB();
    $role = 'projectlid';

    $sql = "SELECT id FROM role WHERE name = ?";
    $stmt = mysqli_prepare($conn, $sql) or die("prepare error");
    mysqli_stmt_bind_param($stmt, 's', $role) or die("bind param error");
    mysqli_stmt_execute($stmt) or die("execute error");
    mysqli_stmt_bind_result($stmt, $id);
    mysqli_stmt_store_result($stmt);
    while (mysqli_stmt_fetch($stmt)) {}
    $returnid = $id;
    mysqli_stmt_close($stmt);

    return $returnid;
}
