<?php
include './assets/inc/functions.php';

check_login(1);

include_once '../../assets/inc/config.php';

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    // Only super admin can delete sub metros
    if ($_SESSION['user_data']['branch_id'] != 1 || $_SESSION['user_data']['email'] !== 'admin@gmail.com') {
        header("Location: submetros.php?error=Unauthorized Access");
        exit();
    }

    $id = (int)$_GET['id'];

    if ($id === 1) {
        header("Location: submetros.php?error=Main Office cannot be deleted");
        exit();
    }

    $stmt = $mysqli->prepare("SELECT COUNT(*) FROM users WHERE branch_id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->bind_result($user_count);
    $stmt->fetch();
    $stmt->close();

    if ($user_count > 0) {
        header("Location: submetros.php?error=This sub metro has users assigned to it and cannot be deleted");
        exit();
    }

    $stmt = $mysqli->prepare("DELETE FROM branches WHERE id = ?");
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        header("Location: submetros.php?success=Sub metro deleted successfully");
    } else {
        header("Location: submetros.php?error=Failed to delete sub metro");
    }
    $stmt->close();
    exit();
}

include_once './assets/inc/navbar.php';
include_once './assets/inc/sidebar.php';
?>

<div class="container">
    <div class="page-header">
        <h2 class="page-title">Sub Metros</h2>
        <?php if ($_SESSION['user_data']['branch_id'] == 1 && $_SESSION['user_data']['email'] === 'admin@gmail.com'): ?>
        <a href="add_submetro.php" class="btn btn-primary">Add Sub Metro</a>
        <?php endif; ?>
    </div>

    <div class="admin-card">
        <?php if (isset($_REQUEST['success'])): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($_REQUEST['success']); ?>
        </div>
        <?php endif; ?>

        <?php if (isset($_REQUEST['error'])): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($_REQUEST['error']); ?>
        </div>
        <?php endif; ?>

        <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Sub Metro Name</th>
                    <th>Code</th>
                    <th>Prefix</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $mysqli->query("SELECT id, name, code, prefix FROM branches ORDER BY id");
                while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['code']); ?></td>
                    <td><?php echo htmlspecialchars($row['prefix']); ?></td>
                    <td>
                        <div class="action-buttons">
                            <a class="btn btn-sm btn-info"
                                href="edit_submetro.php?id=<?php echo (int)$row['id']; ?>">Edit</a>
                            <?php if ((int)$row['id'] !== 1 && $_SESSION['user_data']['branch_id'] == 1 && $_SESSION['user_data']['email'] === 'admin@gmail.com'): ?>
                            <a class="btn btn-sm btn-danger"
                                href="submetros.php?action=delete&id=<?php echo (int)$row['id']; ?>"
                                onclick="return confirm('Delete this sub metro?');">Delete</a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        </div>
    </div>
    <script src="./assets/js/theme-toggle.js"></script>
</div>

<?php include './assets/inc/footer.php'; ?>
