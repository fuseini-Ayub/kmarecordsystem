<?php

// Your query and other PHP logic here
$data = array();
$qr = mysqli_query($con, "SELECT * FROM users WHERE usertype='2'");
while ($row = mysqli_fetch_assoc($qr)) {
    array_push($data, $row);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>document.documentElement.setAttribute("data-theme",localStorage.getItem("theme")||"light");</script>
    <link href="./assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="./assets/css/themes.css">
    <link rel="stylesheet" href="./assets/css/styles.css">
    <link rel="stylesheet" href="./assets/css/records.css">
    <link rel="icon" href="../images/logo.png" type="image/png">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light navbar-custom fixed-top px-4">

        <a class="navbar-brand" href="index.php">
            <span class="sam-main">KMA </span>RECORDS MANAGEMENT UNIT</a>
        <span class="badge badge-pill badge-info ml-3"><?php echo htmlspecialchars($_SESSION['branch']['name'] ?? 'Main Office'); ?></span>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>


        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <!-- User Dropdown -->
                <li class="nav-item">

                </li>

                <li class="nav-item d-flex align-items-center">
                    <?php define('INCLUDE_CHECK', true); include 'assets/inc/theme-toggle.php'; ?>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown"
                        role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="font-17 mr-2">

                        </span>
                        <?php
                        if (!empty($_SESSION['user_data']['userimage'])) {
                            echo '<img src="' . htmlspecialchars($_SESSION['user_data']['userimage']) . '" alt="User Profile" class="user-img">';
                        } else {
                            $username = $_SESSION['user_data']['name'];
                            $firstChar = !empty($username) ? strtoupper(substr($username, 0, 1)) : 'U';
                            echo '<div class="user-img d-flex justify-content-center align-items-center bg-primary text-white">' . htmlspecialchars($firstChar) . '</div>';
                        }
                        ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <a class="dropdown-item"
                            href="view_profile.php?id=<?php echo $_SESSION['user_data']['id'];?>">View
                            Profile</a>
                        <a class="dropdown-item"
                            href="change_password.php?id=<?php echo $_SESSION['user_data']['id']; ?>">Change
                            Password</a>

                        <a class="dropdown-item" href="logout.php">Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <!-- jQuery first -->
    <script src="./assets/js/jquery-3.5.1.min.js"></script>


    <!-- Popper.js (required for Bootstrap) -->
    <script src="./assets/js/popper.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="./assets/js/bootstrap.min.js"></script>

    <script src="./assets/js/theme-toggle.js"></script>
    <script>applyTheme();</script>