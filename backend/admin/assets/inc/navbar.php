<?php
$admin_name = $_SESSION['user_data']['name'] ?? 'Admin';
$admin_initial = !empty($admin_name) ? strtoupper(substr($admin_name, 0, 1)) : 'A';
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
    <link rel="stylesheet" href="./assets/css/admin-modern.css">
    <link rel="icon" href="../images/logo.png" type="image/png">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light navbar-custom fixed-top">
        <a class="navbar-brand" href="index.php">
            <span class="brand-mark">KMA</span> Records Management Unit
        </a>
        <span class="branch-badge d-none d-md-inline-flex ml-3">
            <?php echo htmlspecialchars($_SESSION['branch']['name'] ?? 'Main Office'); ?>
        </span>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto align-items-lg-center">
                <li class="nav-item d-flex align-items-center mr-lg-3">
                    <?php define('INCLUDE_CHECK', true); include 'assets/inc/theme-toggle.php'; ?>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown"
                        role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php
                        if (!empty($_SESSION['user_data']['userimage'])) {
                            echo '<img src="' . htmlspecialchars($_SESSION['user_data']['userimage']) . '" alt="User Profile" class="user-img">';
                        } else {
                            echo '<div class="user-img">' . htmlspecialchars($admin_initial) . '</div>';
                        }
                        ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="view_profile.php?id=<?php echo (int)$_SESSION['user_data']['id']; ?>">View Profile</a>
                        <a class="dropdown-item" href="change_password.php?id=<?php echo (int)$_SESSION['user_data']['id']; ?>">Change Password</a>
                        <a class="dropdown-item" href="logout.php">Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <script src="./assets/js/jquery-3.5.1.min.js"></script>
    <script src="./assets/js/popper.min.js"></script>
    <script src="./assets/js/bootstrap.min.js"></script>
    <script src="./assets/js/theme-toggle.js"></script>
    <script>applyTheme();</script>
