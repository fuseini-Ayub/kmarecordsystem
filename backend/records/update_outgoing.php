<?php
include './assets/inc/functions.php';
check_login(2);
include_once '../../assets/inc/config.php';
include_once './assets/inc/navbar.php';
include_once './assets/inc/sidebar.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$outgoing = [];

if ($id > 0) {
    $stmt = $mysqli->prepare("SELECT * FROM outgoing_files WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $outgoing = $result->fetch_assoc();
    $stmt->close();
}

if (!$outgoing) {
    echo "Outgoing file not found.";
    exit;
}

if (isset($_GET['error'])) {
    $error_message = '';
    switch ($_GET['error']) {
        case '1':
            $error_message = 'Error updating outgoing file. Please try again.';
            break;
        case '2':
            $error_message = 'Error uploading file. Please try again.';
            break;
        default:
            $error_message = 'An unknown error occurred. Please try again.';
    }
    echo "<div class='alert alert-danger'>$error_message</div>";
}

if (isset($_GET['success']) && $_GET['success'] == '1') {
    $success_message = 'Outgoing file updated successfully.';
    echo "<div class='alert alert-success'>$success_message</div>";
}

?>

    <div class="container mt-5 mb-5">
        <h2 class="mb-4 mt-5 page-title">Update Outgoing Correspondence 📂</h2>

        <?php
        if (isset($error_message)) {
            echo "<div class='alert alert-danger'>$error_message</div>";
        }
        if (isset($success_message)) {
            echo "<div class='alert alert-success'>$success_message</div>";
        }
        ?>

        <form action="update_outgoing_post.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $outgoing['id']; ?>">
            <div class="row">
                <div class="form-group col-lg-4">
                    <label for="serial_number">Serial Number</label>
                    <input type="text" class="form-control" id="serial_number" name="serial_number"
                        value="<?php echo htmlspecialchars($outgoing['serial_number']); ?>" readonly required>
                </div>
                <div class="form-group col-lg-4">
                    <label for="date_dispatched">Date Dispatched</label>
                    <input type="date" class="form-control" id="date_dispatched" name="date_dispatched"
                        value="<?php echo htmlspecialchars($outgoing['date_dispatched']); ?>" required>
                </div>
                <div class="form-group col-lg-4">
                    <label for="date_received_for_dispatch">Date Received for Dispatch</label>
                    <input type="date" class="form-control" id="date_received_for_dispatch"
                        name="date_received_for_dispatch"
                        value="<?php echo htmlspecialchars($outgoing['date_received_for_dispatch']); ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-6">
                    <label for="addressee">Addressee</label>
                    <input type="text" class="form-control" id="addressee" name="addressee"
                        value="<?php echo htmlspecialchars($outgoing['addressee']); ?>" required>
                </div>
                <div class="form-group col-lg-6">
                    <label for="mode_of_dispatch">Mode of Dispatch</label>
                    <input type="text" class="form-control" id="mode_of_dispatch" name="mode_of_dispatch"
                        value="<?php echo htmlspecialchars($outgoing['mode_of_dispatch']); ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label for="subject">Subject</label>
                <textarea class="form-control" id="subject" name="subject"
                    required><?php echo htmlspecialchars($outgoing['subject']); ?></textarea>
            </div>

            <div class="row">
                <div class="form-group col-lg-6">
                    <label for="department">Department / Unit</label>
                    <select class="form-control" id="department" name="department" required>
                        <option value="">Select Department</option>
                    </select>
                </div>
                <div class="form-group col-lg-6">
                    <label for="transaction">Transaction</label>
                    <select class="form-control" id="transaction" name="transaction" required>
                        <option value="">Select Transaction</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-6">
                    <label for="file_reference">File Reference</label>
                    <input type="text" class="form-control" id="file_reference" name="file_reference"
                        value="<?php echo htmlspecialchars($outgoing['file_reference']); ?>" required>
                </div>
                <div class="form-group col-lg-6">
                    <label for="folio">Folio</label>
                    <input type="text" class="form-control" id="folio" name="folio"
                        value="<?php echo htmlspecialchars($outgoing['folio']); ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-6">
                    <label for="file">Upload File (Leave empty if not changing)</label>
                    <input type="file" class="form-control" id="file" name="file">
                </div>
            </div>

            <div class="d-flex align-items-center mt-4">
                <button type="submit" class="btn btn-primary mr-5">Update Outgoing File</button>
                <div class="form-check d-flex align-items-center">
                    <input type="checkbox" class="form-check-input mr-2" id="action_taken" name="action_taken"
                        <?php echo $outgoing['action_taken'] ? 'checked' : ''; ?>>
                    <label class="form-check-label mb-0" for="action_taken">Action Taken</label>
                </div>
            </div>
        </form>
    </div>

    <script src="./assets/js/jquery-3.5.1.min.js"></script>
    <script>
    $(document).ready(function() {
        function loadTransactions(departmentId) {
            $.ajax({
                url: 'get_transactions.php',
                method: 'GET',
                data: {
                    department_id: departmentId
                },
                success: function(data) {
                    $('#transaction').html('<option value="">Select Transaction</option>' + data);
                    $('#transaction').val('<?php echo $outgoing['transaction_id']; ?>');
                    updateFileReference();
                }
            });
        }

        function updateFileReference() {
            var transactionId = $('#transaction').val();
            if (transactionId) {
                $.ajax({
                    url: 'get_reference_number.php',
                    method: 'GET',
                    data: {
                        transaction_id: transactionId
                    },
                    success: function(data) {
                        $('#file_reference').val(data);
                    }
                });
            }
        }

        $.ajax({
            url: 'get_departments.php',
            method: 'GET',
            success: function(data) {
                $('#department').html('<option value="">Select Department</option>' + data);
                $('#department').val('<?php echo $outgoing['department_id']; ?>');
                loadTransactions('<?php echo $outgoing['department_id']; ?>');
            }
        });

        $('#department').change(function() {
            var departmentId = $(this).val();
            loadTransactions(departmentId);
        });

        $('#transaction').change(function() {
            updateFileReference();
        });
    });
    </script>
    <script src="./assets/js/theme-toggle.js"></script>

<?php include './assets/inc/footer.php'; ?>