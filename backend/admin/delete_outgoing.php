<?php
include './assets/inc/functions.php';
check_login(1);
include_once '../../assets/inc/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    
    // Start a transaction
    $mysqli->begin_transaction();
    
    try {
        // First, get the file path
        $query = "SELECT file_path FROM outgoing_files WHERE id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        if ($row && $row['file_path']) {
            // Delete the file from the server
            if (file_exists($row['file_path'])) {
                if (!unlink($row['file_path'])) {
                    throw new Exception("Failed to delete file from server");
                }
            }
        }
        
        // Now delete the record from the database
        $query = "DELETE FROM outgoing_files WHERE id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("i", $id);
        
        if (!$stmt->execute()) {
            throw new Exception("Failed to delete record from database");
        }
        
        // If we've made it this far, commit the transaction
        $mysqli->commit();
        echo json_encode(['status' => 'success', 'message' => 'File deleted successfully']);
    } catch (Exception $e) {
        // An error occurred, rollback the transaction
        $mysqli->rollback();
        error_log("Error deleting outgoing file: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}

$mysqli->close();
?>