<?php
session_start();
include '../../assets/inc/config.php';

$branch_id = isset($_SESSION['user_data']['branch_id']) ? (int)$_SESSION['user_data']['branch_id'] : 1;
$result = $mysqli->query("SELECT id, name FROM departments WHERE branch_id = $branch_id OR branch_id = 1");

while ($row = $result->fetch_assoc()) {
    echo "<option value='{$row['id']}'>{$row['name']}</option>";
}

$mysqli->close();
?>