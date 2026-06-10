<?php
include './assets/inc/functions.php';
check_login(2);
include_once '../../assets/inc/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $date_dispatched = $_POST['date_dispatched'];
    $date_received_for_dispatch = $_POST['date_received_for_dispatch'];
    $subject = $_POST['subject'];
    $addressee = $_POST['addressee'];
    $mode_of_dispatch = $_POST['mode_of_dispatch'];
    $department_id = !empty($_POST['department']) ? $_POST['department'] : null;
    $transaction_id = !empty($_POST['transaction']) ? $_POST['transaction'] : null;
    $file_reference = $_POST['file_reference'];
    $folio = $_POST['folio'];
    $action_taken = isset($_POST['action_taken']) ? 1 : 0;

    $file_path = '';
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $upload_dir = '../../assets/images/uploads/outgoing/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $fileExtension = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
        $newFileName = preg_replace('/[^a-zA-Z0-9_.-]/', '_', $subject . '_' . $date_dispatched . '_' . $addressee) . '.' . $fileExtension;
        $file_path = $upload_dir . $newFileName;
        if (move_uploaded_file($_FILES['file']['tmp_name'], $file_path)) {
            // File uploaded successfully
        } else {
            // Handle file upload error
            header("Location: update_outgoing.php?id=$id&error=2");
            exit;
        }
    }

    $stmt = $mysqli->prepare("UPDATE outgoing_files SET date_dispatched = ?, date_received_for_dispatch = ?, subject = ?, addressee = ?, mode_of_dispatch = ?, department_id = ?, transaction_id = ?, file_reference = ?, folio = ?, action_taken = ? WHERE id = ?");
    
    if ($file_path) {
        $stmt = $mysqli->prepare("UPDATE outgoing_files SET date_dispatched = ?, date_received_for_dispatch = ?, subject = ?, addressee = ?, mode_of_dispatch = ?, department_id = ?, transaction_id = ?, file_reference = ?, folio = ?, action_taken = ?, file_path = ? WHERE id = ?");
        $stmt->bind_param("sssssiissssi", $date_dispatched, $date_received_for_dispatch, $subject, $addressee, $mode_of_dispatch, $department_id, $transaction_id, $file_reference, $folio, $action_taken, $file_path, $id);
    } else {
        $stmt = $mysqli->prepare("UPDATE outgoing_files SET date_dispatched = ?, date_received_for_dispatch = ?, subject = ?, addressee = ?, mode_of_dispatch = ?, department_id = ?, transaction_id = ?, file_reference = ?, folio = ?, action_taken = ? WHERE id = ?");
        $stmt->bind_param("sssssiisssi", $date_dispatched, $date_received_for_dispatch, $subject, $addressee, $mode_of_dispatch, $department_id, $transaction_id, $file_reference, $folio, $action_taken, $id);
    }

    if ($stmt->execute()) {
        header("Location: update_outgoing.php?id=$id&success=1");
        exit;
    } else {
        $error_message = "Database error: " . $stmt->error;
        error_log($error_message);
        header("Location: update_outgoing.php?id=$id&error=1");
        exit;
    }
    $stmt->close();
}

$mysqli->close();
?>