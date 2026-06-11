<?php

include './assets/inc/functions.php';

check_login(1);

include_once '../../assets/inc/config.php';
include_once './assets/inc/navbar.php';
include_once './assets/inc/sidebar.php';

?>

<div class="container">
    <div class="page-header">
        <h2 class="page-title">Add New Department</h2>
    </div>
    <div class="admin-card" style="max-width: 500px;">
        <form action="add_department.php" method="post">
            <div class="form-group">
                <label for="department_name">Department Name</label>
                <input type="text" class="form-control" id="department_name" name="department_name" required="required">
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Add Department</button>
            </div>
        </form>
    </div>
    <script src="./assets/js/theme-toggle.js"></script>
</div>
<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $department_name = trim($_POST['department_name']);
    if (!empty($department_name)) {
        $user_branch_id = isset($_SESSION['user_data']['branch_id']) ? (int)$_SESSION['user_data']['branch_id'] : 1;

        if ($user_branch_id == 1) {
            // Main metro: insert department for ALL branches
            $branches_result = $mysqli->query("SELECT id FROM branches");
            $all_ok = true;
            while ($b = $branches_result->fetch_assoc()) {
                $stmt = $mysqli->prepare("INSERT INTO departments (name, branch_id) VALUES (?, ?)");
                if ($stmt) {
                    $bid = (int)$b['id'];
                    $stmt->bind_param('si', $department_name, $bid);
                    $stmt->execute();
                    $stmt->close();
                } else {
                    $all_ok = false;
                }
            }
            if ($all_ok) {
                echo "<div class='alert alert-success mt-4'>Department added to all sub metros successfully!</div>";
            } else {
                echo "<div class='alert alert-danger mt-4'>Error adding department to some branches.</div>";
            }
        } else {
            // Sub metro: insert for their branch only
            $stmt = $mysqli->prepare("INSERT INTO departments (name, branch_id) VALUES (?, ?)");
            if ($stmt) {
                $stmt->bind_param('si', $department_name, $user_branch_id);
                $stmt->execute();
                $stmt->close();
                echo "<div class='alert alert-success mt-4'>Department added successfully!</div>";
            } else {
                echo "<div class='alert alert-danger mt-4'>Error preparing statement: " . $mysqli->error . "</div>";
            }
        }
    } else {
        echo "<div class='alert alert-danger mt-4'>Department name cannot be empty!</div>";
    }
}

include_once './assets/inc/footer.php';

?>