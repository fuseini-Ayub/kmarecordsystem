<?php
include './assets/inc/functions.php';
include '../../assets/inc/config.php';
check_login(2);

// Fetch user data from session
$user_data = $_SESSION['user_data'];
?>

    <?php include './assets/inc/navbar.php'; ?>
    <?php include './assets/inc/sidebar.php'; ?>

    <div class="container profile-container">
        <div class="page-header">
            <h2 class="page-title">My Profile</h2>
            <a href="edit_profile.php" class="btn btn-primary">Edit Profile</a>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="profile-card text-center">
                    <div class="d-flex justify-content-center">
                        <?php
                        if (!empty($_SESSION['user_data']['userimage'])) {
                            echo '<img src="' . htmlspecialchars($_SESSION['user_data']['userimage']) . '" alt="User Profile" class="profile-img">';
                        } else {
                            $username = $_SESSION['user_data']['name'];
                            $firstChar = !empty($username) ? strtoupper(substr($username, 0, 1)) : 'U';
                            echo '<div class="user-img d-flex justify-content-center align-items-center bg-primary text-white">' . htmlspecialchars($firstChar) . '</div>';
                        }
                        ?>


                    </div>
                    <div class="profile-info">
                        <h3><?php echo htmlspecialchars($user_data['name']); ?></h3>
                        <p>Email: <?php echo htmlspecialchars($user_data['email']); ?></p>
                        <p>Age: <?php echo htmlspecialchars($user_data['age']); ?></p>
                        <p>Gender: <?php echo htmlspecialchars($user_data['gender']); ?></p>
                        <p>Phone Number: <?php echo htmlspecialchars($user_data['phone_number']); ?></p>
                        <p>Address: <?php echo htmlspecialchars($user_data['address']); ?></p>
                        <p>Staff ID: <?php echo htmlspecialchars($user_data['staffID']); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script src="./assets/js/theme-toggle.js"></script>

<?php include './assets/inc/footer.php'; ?>