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
    <h2 class="mb-4 page-title mt-5 pt-5">Transactions List ⚖️</h2>

    <!-- Button to navigate to Add Transaction page -->
    <a href="add_transaction.php" class="btn btn-primary mb-3">Add Transaction</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Department</th>
                <th>Reference Number</th>
                <th>Name</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $branch_id = isset($_SESSION['user_data']['branch_id']) ? (int)$_SESSION['user_data']['branch_id'] : 1;
            $query = "SELECT t.id, d.name as department_name, t.reference_no, t.name
                      FROM transactions t
                      JOIN departments d ON t.department_id = d.id
                      WHERE t.branch_id = $branch_id OR t.branch_id = 1";
            $result = $mysqli->query($query);
            while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['department_name']; ?></td>
                <td><?php echo $row['reference_no']; ?></td>
                <td><?php echo $row['name']; ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <script src="./assets/js/theme-toggle.js"></script>
</div>

<?php include './assets/inc/footer.php'; ?>