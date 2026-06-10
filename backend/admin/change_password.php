<?php
session_start();
include '../../assets/inc/config.php';

if (isset($_SESSION['user_data']) && $_SESSION['user_data']['usertype'] == 1) {
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Retrieve form data and sanitize it
            $current_password = mysqli_real_escape_string($con, $_POST['current_password']);
            $new_password = mysqli_real_escape_string($con, $_POST['new_password']);

            // Verify current password
            $select_query = "SELECT password FROM users WHERE id = ?";
            if ($stmt = $con->prepare($select_query)) {
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $stmt->bind_result($hashed_password);
                $stmt->fetch();
                $stmt->close();

                if (md5($current_password) === $hashed_password) {
                    // Update password
                    $new_hashed_password = md5($new_password);
                    $update_query = "UPDATE users SET password = ? WHERE id = ?";
                    if ($stmt = $con->prepare($update_query)) {
                        $stmt->bind_param("si", $new_hashed_password, $id);
                        if ($stmt->execute()) {
                            header("Location: users.php?success=Password updated successfully");
                            exit();
                        } else {
                            header("Location: change_password.php?id=$id&error=Failed to update password");
                            exit();
                        }
                        $stmt->close();
                    }
                } else {
                    header("Location: change_password.php?id=$id&error=Incorrect current password");
                    exit();
                }
            }
        }
    } else {
        header("Location: teacher_home.php?error=No user ID specified");
        exit();
    }
} else {
    header("Location: index.php?error=Unauthorized Access");
    exit();
}
?>

    <?php include './assets/inc/navbar.php'; ?>
    <?php include './assets/inc/sidebar.php'; ?>

    <div class="container">
        <div class="page-header">
            <h2 class="page-title">Change Password</h2>
        </div>
        <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>
        <div class="admin-card" style="max-width: 500px;">
            <form action="change_password.php?id=<?php echo $id; ?>" method="post">
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                </div>
                <button type="submit" class="btn btn-primary">Change Password</button>
            </form>
        </div>
    </div>
    <script src="./assets/js/theme-toggle.js"></script>
