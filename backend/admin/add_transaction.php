<?php


include './assets/inc/functions.php';

function normalize_branch_reference($reference_no, $branch_prefix) {
    $reference_no = trim((string)$reference_no);
    if ($reference_no === '') {
        return $reference_no;
    }

    $branch_prefix = trim((string)$branch_prefix);
    if ($branch_prefix === '') {
        $branch_prefix = 'KMA';
    }

    if (preg_match('/^KMA(?:\.[A-Z]+)?/i', $reference_no)) {
        return preg_replace('/^KMA(?:\.[A-Z]+)?/i', $branch_prefix, $reference_no, 1);
    }

    return $branch_prefix . '.' . ltrim($reference_no, '.');
}

check_login(1);


// // Include other necessary files
include_once '../../assets/inc/config.php';
include_once './assets/inc/navbar.php';
include_once './assets/inc/sidebar.php';

?>

<div class="container">
    <div class="page-header">
        <h2 class="page-title">Add New Transaction</h2>
    </div>
    <div class="admin-card" style="max-width: 500px;">
        <form action="add_transaction.php" method="post">
            <div class="form-group">
                <label for="department">Department</label>
                <select class="form-control" id="department" name="department" required="required">
                    <!-- Options will be populated dynamically using PHP -->
                </select>
            </div>
            <div class="form-group">
                <label for="transaction_name">Transaction Name</label>
                <input type="text" class="form-control" id="transaction_name" name="transaction_name"
                    required="required">
            </div>
            <div class="form-group">
                <label for="reference_no">Reference Number</label>
                <input type="text" class="form-control" id="reference_no" name="reference_no" required="required">
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Add Transaction</button>
            </div>
        </form>
    </div>
    <script src="./assets/js/theme-toggle.js"></script>
</div>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $department_id = $_POST['department'];
    $reference_no = $_POST['reference_no'];
    $transaction_name = $_POST['transaction_name'];

    if (!empty($department_id) && !empty($reference_no) && !empty($transaction_name)) {
        $branch_id = isset($_SESSION['user_data']['branch_id']) ? (int)$_SESSION['user_data']['branch_id'] : 1;
        $branch_prefix = isset($_SESSION['branch']['prefix']) ? $_SESSION['branch']['prefix'] : 'KMA';
        $reference_no = normalize_branch_reference($reference_no, $branch_prefix);
        $stmt = $mysqli->prepare("INSERT INTO transactions (department_id, branch_id, reference_no, name) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('iiss', $department_id, $branch_id, $reference_no, $transaction_name);
        $stmt->execute();
        $stmt->close();
        echo "<div class='alert alert-success mt-4'>Transaction added successfully!</div>";
    }
}
?>

<script>
// Populate departments dynamically
document.addEventListener('DOMContentLoaded', function() {
    fetch('get_departments.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById('department').innerHTML = data;
        });
});
</script>

<?php include './assets/inc/footer.php'; ?>