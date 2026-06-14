<?php
$admin_name = $_SESSION['user_data']['name'] ?? 'Admin';
$admin_initial = !empty($admin_name) ? strtoupper(substr($admin_name, 0, 1)) : 'A';

$page_titles = [
    'index' => 'Dashboard',
    'users' => 'User Management',
    'add_user' => 'Add New User',
    'update_user' => 'Edit User',
    'departments' => 'Department Management',
    'add_department' => 'Add Department',
    'submetros' => 'Sub Metro Management',
    'add_submetro' => 'Add Sub Metro',
    'edit_submetro' => 'Edit Sub Metro',
    'add_transaction' => 'New Transaction',
    'view_profile' => 'My Profile',
    'edit_profile' => 'Edit Profile',
    'change_password' => 'Change Password',
    'incoming' => 'Incoming Files',
    'outgoing' => 'Outgoing Files',
    'add_outgoing' => 'Add Outgoing File',
    'view_incoming' => 'Incoming File Details',
    'view_outgoing' => 'Outgoing File Details',
];
$page_slug = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
$page_title = $page_titles[$page_slug] ?? 'Admin Panel';
$browser_title = $page_title . ' - KMA Records';
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $browser_title; ?></title>
    <script>(function(){var t=localStorage.getItem('theme')||'light';document.documentElement.setAttribute('data-theme',t);var d=t==='dark',s=document.documentElement.style;s.setProperty('--sidebar-bg',d?'#0a0f1a':'#ffffff');s.setProperty('--sidebar-link',d?'#64748b':'#475569');s.setProperty('--sidebar-section',d?'#475569':'#94a3b8');s.setProperty('--sidebar-hover-bg',d?'rgba(255,255,255,0.05)':'rgba(0,0,0,0.05)');s.setProperty('--sidebar-divider',d?'rgba(255,255,255,0.05)':'rgba(0,0,0,0.08)');s.setProperty('--sidebar-brand',d?'#f1f5f9':'#1e293b');s.setProperty('--sidebar-link-active',d?'#60a5fa':'#1d4ed8');s.setProperty('--sidebar-link-hover',d?'#e2e8f0':'#1e2a3a');s.setProperty('--nb-bg',d?'rgba(17,24,39,0.92)':'#ffffff');s.setProperty('--navbar-bg',d?'rgba(17,24,39,0.92)':'#ffffff');s.setProperty('--navbar-border',d?'#1e293b':'#d0d5dd');s.setProperty('--navbar-brand',d?'#f1f5f9':'#1e293b');s.setProperty('--bg-body',d?'#0a0f1a':'#f4f6f9');s.setProperty('--card-bg',d?'#111827':'#ffffff');s.setProperty('--card-border',d?'#1e293b':'#e8ecf1');s.setProperty('--text-primary',d?'#e2e8f0':'#1e2a3a');s.setProperty('--text-secondary',d?'#8896ab':'#5a6577');s.setProperty('--text-muted',d?'#556680':'#8c98a8');s.setProperty('--input-bg',d?'#1e293b':'#ffffff');s.setProperty('--input-border',d?'#334155':'#c9cfd8');s.setProperty('--table-header-bg',d?'#1e293b':'#f8f9fb');s.setProperty('--table-border',d?'#1e293b':'#e8ecf1');s.setProperty('--table-hover',d?'#172033':'#f0f4ff');s.setProperty('--toggle-btn-bg',d?'#1e293b':'#f0f2f5');s.setProperty('--toggle-btn-border',d?'#334155':'#dde1e8');s.setProperty('--toggle-btn-color',d?'#8896ab':'#5a6577');s.setProperty('--toggle-btn-hover-bg',d?'#334155':'#e2e6ed');s.setProperty('--primary',d?'#3b82f6':'#2563eb');s.setProperty('--primary-hover',d?'#60a5fa':'#1d4ed8');s.setProperty('--primary-light',d?'#1e3a5f':'#eff6ff');s.setProperty('--primary-border',d?'#1e40af':'#bfdbfe');s.setProperty('--success',d?'#22c55e':'#16a34a');s.setProperty('--danger',d?'#ef4444':'#dc2626');s.setProperty('--warning',d?'#f59e0b':'#d97706');s.setProperty('--info',d?'#22d3ee':'#0891b2');s.setProperty('--sidebar-active-bg',d?'rgba(59,130,246,0.15)':'rgba(59,130,246,0.10)')})();</script>
    <?php $v = '?v=' . time(); ?>
    <link rel="stylesheet" href="./assets/css/themes.css<?php echo $v; ?>">
    <link rel="stylesheet" href="./assets/css/kma-base.css<?php echo $v; ?>">
    <link rel="stylesheet" href="./assets/css/styles.css<?php echo $v; ?>">
    <link rel="stylesheet" href="./assets/css/admin-modern.css<?php echo $v; ?>">
    <link rel="icon" href="../images/logo.png" type="image/png">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light navbar-custom fixed-top">
        <a class="navbar-brand" href="index.php">
            <span class="brand-mark">KMA</span> <span class="brand-text">Records Management Unit</span>
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
                            echo '<div class="user-img d-flex justify-content-center align-items-center bg-primary text-white">' . htmlspecialchars($admin_initial) . '</div>';
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

    <script src="./assets/js/jquery-3.5.1.min.js<?php echo $v; ?>"></script>
    <script src="./assets/js/kma-base.js<?php echo $v; ?>"></script>
    <script src="./assets/js/theme-toggle.js<?php echo $v; ?>"></script>
