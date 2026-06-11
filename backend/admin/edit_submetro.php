<?php
include './assets/inc/functions.php';

check_login(1);

include_once '../../assets/inc/config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$error_message = '';

if ($id <= 0) {
    header("Location: submetros.php?error=Invalid sub metro selected");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submetro_name = trim($_POST['submetro_name'] ?? '');
    $code = strtoupper(trim($_POST['code'] ?? ''));

    if ($submetro_name === '' || $code === '') {
        $error_message = 'Sub metro name and code are required.';
    } else {
        $stmt = $mysqli->prepare("SELECT id FROM branches WHERE code = ? AND id != ? LIMIT 1");
        $stmt->bind_param('si', $code, $id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error_message = 'A sub metro with this code already exists.';
        }
        $stmt->close();

        if ($error_message === '') {
            $prefix = $code;
            $stmt = $mysqli->prepare("UPDATE branches SET name = ?, code = ?, prefix = ? WHERE id = ?");
            $stmt->bind_param('sssi', $submetro_name, $code, $prefix, $id);

            if ($stmt->execute()) {
                header("Location: submetros.php?success=Sub metro updated successfully");
                exit();
            }

            $error_message = 'Failed to update sub metro.';
            $stmt->close();
        }
    }
}

$stmt = $mysqli->prepare("SELECT id, name, code, prefix FROM branches WHERE id = ? LIMIT 1");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$submetro = $result->fetch_assoc();
$stmt->close();

if (!$submetro) {
    header("Location: submetros.php?error=Sub metro not found");
    exit();
}

include_once './assets/inc/navbar.php';
include_once './assets/inc/sidebar.php';
?>

<div class="container">
    <div class="page-header">
        <h2 class="page-title">Edit Sub Metro</h2>
        <a href="submetros.php" class="btn btn-secondary">View Sub Metros</a>
    </div>

    <div class="admin-card">
        <?php if ($error_message !== ''): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
        <?php endif; ?>

        <form action="edit_submetro.php?id=<?php echo (int)$submetro['id']; ?>" method="post">
            <div class="form-group">
                <label for="submetro_name">Sub Metro Name</label>
                <input type="text" class="form-control" id="submetro_name" name="submetro_name"
                    value="<?php echo htmlspecialchars($_POST['submetro_name'] ?? $submetro['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="code">Code</label>
                <input type="text" class="form-control" id="code" name="code"
                    value="<?php echo htmlspecialchars($_POST['code'] ?? $submetro['code']); ?>" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="submetros.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
    <script src="./assets/js/theme-toggle.js"></script>
</div>

<?php include_once './assets/inc/footer.php'; ?>
