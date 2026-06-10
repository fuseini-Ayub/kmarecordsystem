<?php
session_start();
include '../../assets/inc/config.php';

if (isset($_GET['department_id'])) {
    $department_id = intval($_GET['department_id']);
    $branch_id = isset($_SESSION['user_data']['branch_id']) ? (int)$_SESSION['user_data']['branch_id'] : 1;
    
    $stmt = $mysqli->prepare("SELECT id, name FROM transactions WHERE department_id = ? AND (branch_id = ? OR branch_id = 1)");
    $stmt->bind_param('ii', $department_id, $branch_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        echo "<option value='{$row['id']}'>{$row['name']}</option>";
    }

    $stmt->close();
}
$mysqli->close();
?>