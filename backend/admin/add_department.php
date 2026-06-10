<?php


// Use include_once to ensure the file is included only once
include './assets/inc/functions.php';

// Use the function
check_login(1);

// Continue with the rest of the page code


// // Include other necessary files
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

// Handling form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $department_name = $_POST['department_name'];
    if (!empty($department_name)) {
        $stmt = $mysqli->prepare("INSERT INTO departments (name) VALUES (?)");
        if ($stmt) {
            $stmt->bind_param('s', $department_name);
            $stmt->execute();
            $stmt->close();
            echo "<div class='alert alert-success mt-4'>Department added successfully!</div>";
        } else {
            echo "<div class='alert alert-danger mt-4'>Error preparing statement: " . $mysqli->error . "</div>";
        }
    } else {
        echo "<div class='alert alert-danger mt-4'>Department name cannot be empty!</div>";
    }
}

include_once './assets/inc/footer.php'; // Use include_once to avoid multiple inclusions

?>