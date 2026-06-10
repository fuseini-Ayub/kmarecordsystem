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
        <h2 class="page-title">Transactions List</h2>
        <a href="add_transaction.php" class="btn btn-primary">Add Transaction</a>
    </div>

    <div class="admin-card">
    <div class="table-responsive">
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
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['department_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['reference_no']); ?></td>
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