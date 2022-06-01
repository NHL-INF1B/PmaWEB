<?php
require_once "function.php";

$_SESSION['project'] = array();

if (isset($_GET['projectid']) && isset($_GET['token'])) {
    if ($_SESSION['projectid'] = filter_var(htmlentities($_GET['projectid']), FILTER_SANITIZE_SPECIAL_CHARS) && $_SESSION['token'] = filter_var(htmlentities($_GET['token']), FILTER_SANITIZE_SPECIAL_CHARS)) {
        if (isset($_SESSION['id'])) {
            if (!checkIfJoined($_SESSION['projectid'], $_SESSION['id'])) {
                if ($_SESSION['project'] = getProjectDetails($_SESSION['token'], $_SESSION['projectid'])) {
                    ?>
                    <!DOCTYPE html>
                    <html lang="en">
                        <head>
                            <meta charset="utf-8" />
                            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
                            <title>Aansluiten bij project</title>
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
                                                <h1 class="display-1 lh-1 mb-3">Deelnemen aan: <span style="color: #009BAA; text-decoration: underline;" class="display-1 lh-1 mb-3"><?php echo $_SESSION['project']['name']; ?></span></h1>
                                                <p class="lead fw-normal text-muted mb-5">Je bent uitgenodigd om deel te nemen aan dit project. Klik op de onderstaande knop om deel te nemen.</p>
                                                <div class="d-flex flex-column flex-lg-row align-items-center">
                                                    <form action="succes.php" method="post">
                                                        <input class="btn btn-outline-dark py-3 px-4 rounded-pill" type="submit" name="join" value="Deelnemen">
                                                    </form>
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
                } else {
                    echo 'Token ongeldig';
                }
            } else {
                echo 'Je bent al een deelnemer van dit project.';
            }
        } else {
            //header to login
            header("location: login.php");
        }
    } else {
        echo 'Security warning';
    }
} else {
    echo '404 not found';
}

function getProjectDetails ($tokenid, $projectid) {
    $conn = connectDB();
    $values = array();
    
    //Requesting data from the database
    $sql = "SELECT `name` FROM project WHERE qrcode = ? AND id = ?";

    $stmt = mysqli_prepare($conn, $sql) or die("prepare error");
    mysqli_stmt_bind_param($stmt, 'si', $tokenid, $projectid) or die("bind param error");
    mysqli_stmt_execute($stmt) or die("execute error");
    mysqli_stmt_bind_result($stmt, $name);
    mysqli_stmt_store_result($stmt);
    
    if (mysqli_stmt_num_rows($stmt) == 1) {
        while (mysqli_stmt_fetch($stmt)) {}
        $values['name'] = $name;
    
        return $values;
    } else {
        return false;
    }

    mysqli_stmt_close($stmt);
}

function checkIfJoined($projectid, $userid) {
    $conn = connectDB();
    
    $sql = "SELECT * FROM projectmember WHERE `project_id` = ? AND `user_id` = ?";

    $stmt = mysqli_prepare($conn, $sql) or die("prepare error");
    mysqli_stmt_bind_param($stmt, 'ii', $projectid, $userid) or die("bind param error");
    mysqli_stmt_execute($stmt) or die("execute error");
    mysqli_stmt_bind_result($stmt, $userid, $projectid, $roleid, $rewardpoints);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) == 1) {
        return true;
    } else {
        return false;
    }

    mysqli_stmt_close($stmt);
}
?>
