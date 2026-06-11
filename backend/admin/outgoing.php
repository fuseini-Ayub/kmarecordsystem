<?php
include './assets/inc/functions.php';
check_login(1);
include_once '../../assets/inc/config.php';
include_once './assets/inc/navbar.php';
include_once './assets/inc/sidebar.php';
?>

    <div class="container-fluid">
        <h2 class="mb-4 mt-5 page-title">Outgoing Correspondence Register 🗃️</h2>

        <?php
        if (isset($_GET['success'])) {
            $success_message = '';
            switch ($_GET['success']) {
                case '1':
                    $success_message = 'Outgoing file added successfully.';
                    break;
                case '2':
                    $success_message = 'Outgoing file updated successfully.';
                    break;
                case '3':
                    $success_message = 'Outgoing file deleted successfully.';
                    break;
            }
            echo "<div class='alert alert-success'>$success_message</div>";
        }

        if (isset($_GET['error'])) {
            $error_message = 'An error occurred. Please try again.';
            echo "<div class='alert alert-danger'>$error_message</div>";
        }
        ?>

        <div class="row mb-3 align-items-center">
            <div class="col-md-6 mb-2 mb-md-0">
                <a href="add_outgoing.php" class="btn btn-primary">Add New</a>
            </div>
            <div class="col-md-4">
                <input type="text" id="searchInput" class="form-control" placeholder="Search...">
            </div>
        </div>

        <?php
        $branch_id = isset($_SESSION['user_data']['branch_id']) ? (int)$_SESSION['user_data']['branch_id'] : 1;
        $query = "SELECT o.*, d.name AS department_name, t.name AS transaction_name, t.reference_no AS transaction_reference 
                  FROM outgoing_files o
                  LEFT JOIN departments d ON o.department_id = d.id
                  LEFT JOIN transactions t ON o.transaction_id = t.id
                  WHERE o.branch_id = $branch_id OR o.branch_id = 1
                  ORDER BY o.date_dispatched DESC";
        $result = $mysqli->query($query);

        if ($result->num_rows > 0): ?>
        <div class="admin-card">
        <div class="table-responsive">
        <table class="table table-striped" id="outgoingTable">
            <thead>
                <tr class="">
                    <th>Serial Number</th>
                    <th>Date Dispatched</th>
                    <th>Date Received for Dispatch</th>
                    <th>Addressee</th>
                    <th>Mode of Dispatch</th>
                    <th>Subject</th>
                    <th>Department / Unit</th>
                    <th>Transaction</th>
                    <th>File Reference</th>
                    <th>Folio</th>
                    <th>Action Taken</th>
                    <th>File</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['serial_number']); ?></td>
                    <td><?php echo htmlspecialchars($row['date_dispatched']); ?></td>
                    <td><?php echo htmlspecialchars($row['date_received_for_dispatch']); ?></td>
                    <td><?php echo htmlspecialchars($row['addressee']); ?></td>
                    <td><?php echo htmlspecialchars($row['mode_of_dispatch']); ?></td>
                    <td><?php echo htmlspecialchars($row['subject']); ?></td>
                    <td><?php echo htmlspecialchars($row['department_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['transaction_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['transaction_reference']); ?></td>
                    <td><?php echo htmlspecialchars($row['folio']); ?></td>
                    <td><?php echo $row['action_taken'] ? '✅' : '❌'; ?></td>
                    <td>
                        <?php if ($row['file_path']): ?>
                        <a href="viewfile.php?id=<?php echo $row['id']; ?>&type=outgoing"
                            class="btn btn-info btn-sm">View</a>
                        <?php else: ?>
                        No file
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="update_outgoing.php?id=<?php echo $row['id']; ?>"
                            class="btn btn-warning btn-sm">Update</a>
                        <button class="btn btn-danger btn-sm delete-btn"
                            data-id="<?php echo $row['id']; ?>">Delete</button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        </div>
        </div>
        <?php else: ?>
        <div class="alert alert-info">No outgoing files found.</div>
        <?php endif; ?>
    </div>
    <script src="./assets/js/jquery-3.5.1.min.js"></script>
    <script src="./assets/js/kma-base.js"></script>
    <script>
    $(document).ready(function() {
        function filterTable() {
            var searchValue = $('#searchInput').val().toLowerCase();
            $('#outgoingTable tbody tr').each(function() {
                var rowText = $(this).text().toLowerCase();
                var matchesSearch = searchValue === '' || rowText.includes(searchValue);
                $(this).toggle(matchesSearch);
            });
        }

        $('#searchInput').on('input', filterTable);

        $('.delete-btn').on('click', function() {
            var id = $(this).data('id');
            if (confirm('Are you sure you want to delete this file?')) {
                $.ajax({
                    url: 'delete_outgoing.php',
                    method: 'POST',
                    data: {
                        id: id
                    },
                    success: function(response) {
                        var result = JSON.parse(response);
                        if (result.status === 'success') {
                            alert('File deleted successfully');
                            location.href = 'outgoing.php?success=3';
                        } else {
                            alert('Error deleting file: ' + result.message);
                        }
                    },
                    error: function() {
                        alert('Error deleting file. Please try again.');
                    }
                });
            }
        });
    });
    </script>
<?php include './assets/inc/footer.php'; ?>