<?php
session_start();
include '../../assets/inc/config.php';

if (isset($_SESSION['user_data'])) {
    if ($_SESSION['user_data']['usertype'] != 1) {
        header("Location: backend/records/index.php");
        exit();
    }

    if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
        $id = intval($_GET['id']);

        $select_query = "SELECT userimage FROM users WHERE id = ?";
        if ($stmt = $con->prepare($select_query)) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $stmt->close();

            if (!empty($user['userimage']) && file_exists($user['userimage'])) {
                unlink($user['userimage']);
            }

            $delete_query = "DELETE FROM users WHERE id = ?";
            if ($stmt = $con->prepare($delete_query)) {
                $stmt->bind_param("i", $id);
                if ($stmt->execute()) {
                    header("Location: users.php?success=User deleted successfully");
                    exit();
                }

                header("Location: users.php?error=Failed to delete user");
                exit();
            }
        }
    }

    $data = array();
    $is_main_admin = ($_SESSION['user_data']['branch_id'] == 1);
    $branch_filter = $is_main_admin ? "1=1" : "u.branch_id = " . (int)$_SESSION['user_data']['branch_id'];
    $qr = mysqli_query($con, "SELECT u.*, b.name AS branch_name FROM users u LEFT JOIN branches b ON u.branch_id = b.id WHERE $branch_filter ORDER BY u.id DESC");
    while ($row = mysqli_fetch_assoc($qr)) {
        array_push($data, $row);
    }
?>

    <?php include './assets/inc/navbar.php'; ?>
    <?php include './assets/inc/sidebar.php'; ?>

    <div class="container">
        <div class="page-header">
            <h2 class="page-title">Users</h2>
            <a href="add_user.php" class="btn btn-primary">Add User</a>
        </div>

        <?php if (isset($_REQUEST['error'])): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($_REQUEST['error']); ?>
        </div>
        <?php endif; ?>

        <?php if (isset($_REQUEST['success'])): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($_REQUEST['success']); ?>
        </div>
        <?php endif; ?>

        <div class="admin-card">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Profile Image</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Phone Number</th>
                            <th>Address</th>
                            <th>Staff ID</th>
                            <th>Sub Metro</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $d): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($d['id']); ?></td>
                            <td><?php echo htmlspecialchars($d['name']); ?></td>
                            <td><?php echo htmlspecialchars($d['email']); ?></td>
                            <td>
                                <?php if ((int)$d['usertype'] === 1): ?>
                                <span class="role-badge role-admin">Admin</span>
                                <?php else: ?>
                                <span class="role-badge role-user">User</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($d['userimage'])): ?>
                                <img class="profile-thumb" src="<?php echo htmlspecialchars($d['userimage']); ?>"
                                    alt="Profile Image">
                                <?php else: ?>
                                <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($d['age']); ?></td>
                            <td><?php echo htmlspecialchars($d['gender']); ?></td>
                            <td><?php echo htmlspecialchars($d['phone_number']); ?></td>
                            <td><?php echo htmlspecialchars($d['address']); ?></td>
                            <td><?php echo htmlspecialchars($d['staffID']); ?></td>
                            <td><?php echo htmlspecialchars($d['branch_name'] ?? 'Main Office'); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a class="btn btn-sm btn-info"
                                        href="update_user.php?id=<?php echo (int)$d['id']; ?>">Edit</a>
                                    <?php if ((int)$d['id'] !== (int)$_SESSION['user_data']['id']): ?>
                                    <a class="btn btn-sm btn-danger"
                                        href="users.php?action=delete&id=<?php echo (int)$d['id']; ?>"
                                        onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="./assets/js/theme-toggle.js"></script>

<?php
} else {
    header("Location: index.php?error=Unauthorized Access");
    exit();
}
?>
<?php include './assets/inc/footer.php'; ?>
