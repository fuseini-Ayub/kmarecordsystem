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

include_once '../../assets/inc/config.php';
include_once './assets/inc/navbar.php';
include_once './assets/inc/sidebar.php';

// Fetch all branches for the dropdown
$branches_result = $mysqli->query("SELECT id, name FROM branches ORDER BY id");
$branches = [];
while ($row = $branches_result->fetch_assoc()) {
    $branches[] = $row;
}
$branches_result->close();
?>

<div class="container">
    <div class="page-header">
        <h2 class="page-title">Add New Transaction</h2>
    </div>
    <div class="admin-card" style="max-width: 500px;">
        <form action="add_transaction.php" method="post">
            <div class="form-group">
                <label for="branch">Metro</label>
                <select class="form-control" id="branch" name="branch_id" required="required">
                    <?php foreach ($branches as $b): ?>
                    <option value="<?php echo (int)$b['id']; ?>"><?php echo htmlspecialchars($b['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="department">Department</label>
                <select class="form-control" id="department" name="department" required="required">
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
    $branch_id = isset($_POST['branch_id']) ? (int)$_POST['branch_id'] : 1;

    if (!empty($department_id) && !empty($reference_no) && !empty($transaction_name)) {
        $branch_prefix = 'KMA';
        // Get the branch prefix for the selected branch
        $prefix_result = $mysqli->query("SELECT prefix FROM branches WHERE id = $branch_id");
        if ($prefix_result && $prefix_row = $prefix_result->fetch_assoc()) {
            $branch_prefix = $prefix_row['prefix'];
        }
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
document.addEventListener('DOMContentLoaded', function() {
    loadDepartments(document.getElementById('branch').value);
    document.getElementById('branch').addEventListener('change', function() {
        loadDepartments(this.value);
    });
});

function loadDepartments(branchId) {
    fetch('get_departments.php?branch_id=' + branchId)
        .then(response => response.text())
        .then(data => {
            document.getElementById('department').innerHTML = data;
        });
}
</script>

<?php include './assets/inc/footer.php'; ?>