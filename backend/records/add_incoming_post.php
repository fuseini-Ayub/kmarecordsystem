<?php
include './assets/inc/functions.php';
check_login(2);
include_once '../../assets/inc/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $serial_number = $_POST['serial_number'];
    
    // Check if serial number already exists
    $check_stmt = $mysqli->prepare("SELECT id FROM incoming_files WHERE serial_number = ?");
    $check_stmt->bind_param("s", $serial_number);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        header("Location: add_incoming.php?error=3&serial_number=" . urlencode($serial_number));
        exit;
    }
    $check_stmt->close();

    $date_of_letter = $_POST['date_of_letter'];
    $date_received = $_POST['date_received'];
    $from_whom_received = $_POST['from_whom_received'];
    $institution_reference = $_POST['institution_reference'];
    $subject = $_POST['subject'];

    $file_path = '';
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $upload_dir = '../../assets/images/uploads/incoming/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $fileExtension = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
        $newFileName = preg_replace('/[^a-zA-Z0-9_.-]/', '_', $subject . '_' . $date_of_letter . '_' . $from_whom_received) . '.' . $fileExtension;
        $file_path = $upload_dir . $newFileName;
        if (move_uploaded_file($_FILES['file']['tmp_name'], $file_path)) {
            // File uploaded successfully
        } else {
            // Handle file upload error
            header("Location: add_incoming.php?error=2");
            exit;
        }
    }

    $branch_id = isset($_SESSION['user_data']['branch_id']) ? (int)$_SESSION['user_data']['branch_id'] : 1;
    $stmt = $mysqli->prepare("INSERT INTO incoming_files (serial_number, date_of_letter, date_received, from_whom_received, institution_reference, subject, branch_id, file_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssis", $serial_number, $date_of_letter, $date_received, $from_whom_received, $institution_reference, $subject, $branch_id, $file_path);

    if ($stmt->execute()) {
        header("Location: add_incoming.php?success=1");
        exit;
    } else {
        $error_message = "Database error: " . $stmt->error;
        error_log($error_message);
        header("Location: add_incoming.php?error=1");
        exit;
    }
    $stmt->close();
}

$mysqli->close();
?>