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
        <h2 class="page-title">Departments List</h2>
        <a href="add_department.php" class="btn btn-primary">Add Department</a>
    </div>

    <div class="admin-card">
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $mysqli->query("SELECT id, name FROM departments");
                while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    </div>
    <script src="./assets/js/theme-toggle.js"></script>
</div>

<?php include './assets/inc/footer.php'; ?>