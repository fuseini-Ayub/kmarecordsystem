<?php
include './assets/inc/functions.php';
check_login(1);
include_once '../../assets/inc/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $serial_number = $_POST['serial_number'];
    $date_of_letter = $_POST['date_of_letter'];
    $date_received = $_POST['date_received'];
    $from_whom_received = $_POST['from_whom_received'];
    $institution_reference = $_POST['institution_reference'];
    $subject = $_POST['subject'];
    $department_id = $_POST['department'];
    $transaction_id = $_POST['transaction'];
    $file_reference = $_POST['file_reference'];
    $folio = $_POST['folio'];
    $action_taken = isset($_POST['action_taken']) ? 1 : 0;

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
            header("Location: update_incoming.php?id=$id&error=2");
            exit;
        }
    }

    if ($file_path) {
        $stmt = $mysqli->prepare("UPDATE incoming_files SET date_of_letter = ?, date_received = ?, from_whom_received = ?, institution_reference = ?, subject = ?, department_id = ?, transaction_id = ?, file_reference = ?, folio = ?, file_path = ?, action_taken = ? WHERE id = ?");
        $stmt->bind_param("ssssssiissii", $date_of_letter, $date_received, $from_whom_received, $institution_reference, $subject, $department_id, $transaction_id, $file_reference, $folio, $file_path, $action_taken, $id);
    } else {
        $stmt = $mysqli->prepare("UPDATE incoming_files SET date_of_letter = ?, date_received = ?, from_whom_received = ?, institution_reference = ?, subject = ?, department_id = ?, transaction_id = ?, file_reference = ?, folio = ?, action_taken = ? WHERE id = ?");
        $stmt->bind_param("ssssssiissi", $date_of_letter, $date_received, $from_whom_received, $institution_reference, $subject, $department_id, $transaction_id, $file_reference, $folio, $action_taken, $id);
    }

    if ($stmt->execute()) {
        header("Location: incoming.php?success=2");
        exit;
    } else {
        $error_message = "Database error: " . $stmt->error;
        error_log($error_message);
        header("Location: update_incoming.php?id=$id&error=1");
        exit;
    }
    $stmt->close();
}

$mysqli->close();
?>