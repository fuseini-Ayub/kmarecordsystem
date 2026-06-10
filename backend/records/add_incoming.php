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
            $error_message = 'Error adding incoming file. Please try again.';
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
    $success_message = 'Incoming file added successfully.';
}
?>
    <div class="container mt-5">
        <h2 class="mb-4 mt-5 page-title">Add New Incoming Correspondence 📂</h2>

        <?php
        if (isset($error_message)) {
            echo "<div class='alert alert-danger'>$error_message</div>";
        }
        if (isset($success_message)) {
            echo "<div class='alert alert-success'>$success_message</div>";
        }
        ?>

        <form action="add_incoming_post.php" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="form-group col-lg-4">
                    <label for="serial_number">Serial Number</label>
                    <input type="text" class="form-control" id="serial_number" name="serial_number" readonly>
                </div>
                <div class="form-group col-lg-4">
                    <label for="date_of_letter">Date of Letter</label>
                    <input type="date" class="form-control" id="date_of_letter" name="date_of_letter" required>
                </div>
                <div class="form-group col-lg-4">
                    <label for="date_received">Date Received</label>
                    <input type="date" class="form-control" id="date_received" name="date_received" required>
                </div>
            </div>
            <div class="row">

                <div class="form-group col-lg-6">
                    <label for="from_whom_received">From Whom Received</label>
                    <input type="text" class="form-control" id="from_whom_received" name="from_whom_received" required>
                </div>
                <div class="form-group col-lg-6">
                    <label for="institution_reference">Institution Reference</label>
                    <input type="text" class="form-control" id="institution_reference" name="institution_reference">
                </div>
            </div>

            <div class="form-group">
                <label for="subject">Subject</label>
                <textarea type="text" class="form-control" id="subject" name="subject" required></textarea>
            </div>
            <div class="row">
                <div class="form-group col-lg-6">
                    <label for="file">Upload File</label>
                    <input type="file" class="form-control" id="file" name="file" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Add Incoming File</button>
        </form>
    </div>

    <script src="./assets/js/jquery-3.5.1.min.js"></script>
    <script>
    $(document).ready(function() {
        function generateSerialNumber() {
            return Math.floor(100000000000 + Math.random() * 900000000000).toString();
        }

        $('#serial_number').val(generateSerialNumber());
    });
    </script>

<?php include './assets/inc/footer.php'; ?>