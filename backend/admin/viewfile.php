<?php
include './assets/inc/functions.php';
check_login(1);
include_once '../../assets/inc/config.php';
include_once './assets/inc/navbar.php';
include_once './assets/inc/sidebar.php';

if (!isset($_GET['id']) || !isset($_GET['type'])) {
    header("Location: incoming.php");
    exit;
}

$id = intval($_GET['id']);
$type = $_GET['type'];

if ($type === 'incoming') {
    $table = 'incoming_files';
    $redirect = 'incoming.php';
} elseif ($type === 'outgoing') {
    $table = 'outgoing_files';
    $redirect = 'outgoing.php';
} else {
    header("Location: incoming.php");
    exit;
}

$query = "SELECT * FROM $table WHERE id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$file = $result->fetch_assoc();

if (!$file) {
    header("Location: $redirect");
    exit;
}
?>
    <div class="container mt-5">
        <h2 class="mb-4 mt-5 page-title">File Details ✉️</h2>

        <div class="card">
            <h2><?php echo htmlspecialchars($file['subject']); ?></h2>
            <div class="file-info">
                <p><strong>Serial Number:</strong> <?php echo htmlspecialchars($file['serial_number']); ?></p>
                <?php if ($type === 'incoming'): ?>
                <p><strong>Date of Letter:</strong> <?php echo htmlspecialchars($file['date_of_letter']); ?></p>
                <p><strong>Date Received:</strong> <?php echo htmlspecialchars($file['date_received']); ?></p>
                <p><strong>From:</strong> <?php echo htmlspecialchars($file['from_whom_received']); ?></p>
                <p><strong>Institution Reference:</strong>
                    <?php echo htmlspecialchars($file['institution_reference']); ?></p>
                <?php else: ?>
                <p><strong>Date Dispatched:</strong> <?php echo htmlspecialchars($file['date_dispatched']); ?></p>
                <p><strong>Date Received for Dispatch:</strong>
                    <?php echo htmlspecialchars($file['date_received_for_dispatch']); ?></p>
                <p><strong>Addressee:</strong> <?php echo htmlspecialchars($file['addressee']); ?></p>
                <p><strong>Mode of Dispatch:</strong> <?php echo htmlspecialchars($file['mode_of_dispatch']); ?></p>
                <?php endif; ?>
                <p><strong>File Reference:</strong> <?php echo htmlspecialchars($file['file_reference']); ?></p>
                <p><strong>Folio:</strong> <?php echo htmlspecialchars($file['folio']); ?></p>
                <p><strong>Action Taken:</strong> <?php echo $file['action_taken'] ? 'Yes' : 'No'; ?></p>
            </div>
        </div>

        <?php if ($file['file_path']): ?>
        <div class="card">
            <h3>File Preview</h3>
            <iframe src="<?php echo htmlspecialchars($file['file_path']); ?>" width="100%" height="600"
                id="filePreview"></iframe>
            <div style="margin-top: 15px;">
                <a href="<?php echo htmlspecialchars($file['file_path']); ?>" download
                    class="btn btn-primary">Download</a>
                <button onclick="printFile('<?php echo htmlspecialchars($file['file_path']); ?>')"
                    class="btn btn-secondary">Print</button>
            </div>
        </div>
        <?php else: ?>
        <div class="card">
            <p>No file available for preview.</p>
        </div>
        <?php endif; ?>
    </div>

    <script>
    function printFile(filePath) {
        const printWindow = window.open(filePath, '_blank');
        printWindow.onload = function() {
            printWindow.print();
        };
    }
    </script>
<script src="./assets/js/theme-toggle.js"></script>
<?php include './assets/inc/footer.php'; ?>