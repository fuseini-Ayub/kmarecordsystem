<?php
include './assets/inc/functions.php';

check_login(1);

// Only main admin (Main Office) can add sub metros
if ($_SESSION['user_data']['branch_id'] != 1) {
    header("Location: index.php?error=Unauthorized Access");
    exit();
}

include_once '../../assets/inc/config.php';

$success_message = '';
$error_message = '';
$submetro_name = '';
$code = '';
$prefix = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $submetro_name = trim($_POST['submetro_name'] ?? '');
    $code = strtoupper(trim($_POST['code'] ?? ''));
    $prefix = strtoupper(trim($_POST['prefix'] ?? ''));

    if ($submetro_name === '' || $code === '' || $prefix === '') {
        $error_message = 'Sub metro name, code, and prefix are required.';
    } else {
        $stmt = $mysqli->prepare("SELECT id FROM branches WHERE code = ? LIMIT 1");
        if ($stmt) {
            $stmt->bind_param('s', $code);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $error_message = 'A sub metro with this code already exists.';
            }
            $stmt->close();
        } else {
            $error_message = 'Error preparing duplicate check: ' . $mysqli->error;
        }

        if ($error_message === '') {
            $stmt = $mysqli->prepare("INSERT INTO branches (name, code, prefix) VALUES (?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param('sss', $submetro_name, $code, $prefix);
                if ($stmt->execute()) {
                    $success_message = 'Sub metro added successfully!';
                    $submetro_name = '';
                    $code = '';
                    $prefix = '';
                } else {
                    $error_message = 'Failed to add sub metro: ' . $stmt->error;
                }
                $stmt->close();
            } else {
                $error_message = 'Error preparing statement: ' . $mysqli->error;
            }
        }
    }
}

include_once './assets/inc/navbar.php';
include_once './assets/inc/sidebar.php';
?>

<div class="container">
    <div class="page-header">
        <h2 class="page-title">Add New Sub Metro</h2>
        <a href="submetros.php" class="btn btn-secondary">View Sub Metros</a>
    </div>

    <div class="admin-card">
        <?php if ($success_message !== ''): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($success_message); ?>
        </div>
        <?php endif; ?>

        <?php if ($error_message !== ''): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
        <?php endif; ?>

        <form action="add_submetro.php" method="post">
            <div class="form-group">
                <label for="submetro_name">Sub Metro Name</label>
                <input type="text" class="form-control" id="submetro_name" name="submetro_name"
                    value="<?php echo htmlspecialchars($submetro_name); ?>" placeholder="Example: Oforikrom Sub Metro"
                    required>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="code">Code</label>
                    <input type="text" class="form-control" id="code" name="code"
                        value="<?php echo htmlspecialchars($code); ?>" placeholder="Example: KMA.OS" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="prefix">Reference Prefix</label>
                    <input type="text" class="form-control" id="prefix" name="prefix"
                        value="<?php echo htmlspecialchars($prefix); ?>" placeholder="Example: KMA.OS" required>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Add Sub Metro</button>
                <a href="submetros.php" class="btn btn-secondary">View Sub Metros</a>
            </div>
        </form>
    </div>
    <script src="./assets/js/theme-toggle.js"></script>
</div>

<?php include_once './assets/inc/footer.php'; ?>
