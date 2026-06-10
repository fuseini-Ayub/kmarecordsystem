<?php
include './assets/inc/functions.php';
check_login(2);
include_once '../../assets/inc/config.php';
include_once './assets/inc/navbar.php';
include_once './assets/inc/sidebar.php';

if (isset($_GET['error'])) {
    $error_message = '';
    switch ($_GET['error']) {
        case '1':
            $error_message = 'Error adding outgoing file. Please try again.';
            break;
        case '2':
            $error_message = 'Error uploading file. Please try again.';
            break;
        case '3':
            $serial_number = isset($_GET['serial_number']) ? htmlspecialchars($_GET['serial_number']) : '';
            $error_message = "Serial number '$serial_number' already exists in the database. Please use a different serial number.";
            break;
        default:
            $error_message = 'An unknown error occurred. Please try again.';
    }
}

if (isset($_GET['success']) && $_GET['success'] == '1') {
    $success_message = 'Outgoing file added successfully.';
}
?>
    <div class="container mt-5">
        <h2 class="mb-4 mt-5 page-title">Add New Outgoing Correspondence 📂</h2>

        <?php
        if (isset($error_message)) {
            echo "<div class='alert alert-danger'>$error_message</div>";
        }
        if (isset($success_message)) {
            echo "<div class='alert alert-success'>$success_message</div>";
        }
        ?>

        <form action="add_outgoing_post.php" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="form-group col-lg-4">
                    <label for="serial_number">Serial Number</label>
                    <input type="text" class="form-control" id="serial_number" name="serial_number" readonly>
                </div>
                <div class="form-group col-lg-4">
                    <label for="date_dispatched">Date Dispatched</label>
                    <input type="date" class="form-control" id="date_dispatched" name="date_dispatched" required>
                </div>
                <div class="form-group col-lg-4">
                    <label for="date_received_for_dispatch">Date Received for Dispatch</label>
                    <input type="date" class="form-control" id="date_received_for_dispatch"
                        name="date_received_for_dispatch" required>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-6">
                    <label for="addressee">Addressee</label>
                    <input type="text" class="form-control" id="addressee" name="addressee">
                </div>
                <div class="form-group col-lg-6">
                    <label for="mode_of_dispatch">Mode of Dispatch</label>
                    <input type="text" class="form-control" id="mode_of_dispatch" name="mode_of_dispatch">
                </div>
            </div>

            <div class="form-group">
                <label for="subject">Subject</label>
                <textarea class="form-control" id="subject" name="subject" required></textarea>
            </div>
            <div class="row">
                <div class="form-group col-lg-6">
                    <label for="department">Department / Unit</label>
                    <select class="form-control" id="department" name="department">
                        <option value="">Select Department</option>
                    </select>
                </div>
                <div class="form-group col-lg-6">
                    <label for="transaction">Transaction</label>
                    <select class="form-control" id="transaction" name="transaction">
                        <option value="">Select Transaction</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-6">
                    <label for="file_reference">File Reference</label>
                    <input type="text" class="form-control" id="file_reference" name="file_reference">
                </div>
                <div class="form-group col-lg-6">
                    <label for="folio">Folio</label>
                    <input type="text" class="form-control" id="folio" name="folio">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-6">
                    <label for="file">Upload File</label>
                    <input type="file" class="form-control" id="file" name="file" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Add Outgoing File</button>
        </form>
    </div>

    <script src="./assets/js/jquery-3.5.1.min.js"></script>
    <script>
    $(document).ready(function() {
        function generateSerialNumber() {
            return Math.floor(100000000000 + Math.random() * 900000000000).toString();
        }

        $('#serial_number').val(generateSerialNumber());

        $.ajax({
            url: 'get_departments.php',
            method: 'GET',
            success: function(data) {
                $('#department').html('<option value="">Select Department</option>' + data);
            }
        });

        $('#department').change(function() {
            var departmentId = $(this).val();
            if (departmentId) {
                $.ajax({
                    url: 'get_transactions.php',
                    method: 'GET',
                    data: {
                        department_id: departmentId
                    },
                    success: function(data) {
                        $('#transaction').html(
                            '<option value="">Select Transaction</option>' + data);
                    }
                });
            } else {
                $('#transaction').html('<option value="">Select Transaction</option>');
            }
        });

        $('#transaction').change(function() {
            var transactionId = $(this).val();
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
        });
    });
    </script>

<?php include './assets/inc/footer.php'; ?>