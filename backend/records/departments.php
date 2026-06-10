<?php


// Use include_once to ensure the file is included only once
include './assets/inc/functions.php';

// Use the function
check_login(2);

// Continue with the rest of the page code


// // Include other necessary files
include_once '../../assets/inc/config.php';
include_once './assets/inc/navbar.php';
include_once './assets/inc/sidebar.php';

?>

<div class="container mt-5">
    <h2 class="mb-4 page-title mt-5 pt-5">Departments List 🏛️</h2>

    <!-- Button to navigate to Add Department page -->
    <a href="add_department.php" class="btn btn-primary mb-3">Add Department</a>

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
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <script src="./assets/js/theme-toggle.js"></script>
</div>

<?php include './assets/inc/footer.php'; ?>