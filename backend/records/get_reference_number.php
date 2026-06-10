<?php
session_start();
include '../../assets/inc/config.php'; // Database connection


function normalize_branch_reference($reference_no, $branch_prefix) {
    $reference_no = trim((string)$reference_no);
    if ($reference_no === '') {
        return $reference_no;
    }

    $branch_prefix = trim((string)($branch_prefix ?: 'KMA'));

    if (preg_match('/^KMA(?:\.[A-Z]+)?/i', $reference_no)) {
        return preg_replace('/^KMA(?:\.[A-Z]+)?/i', $branch_prefix, $reference_no, 1);
    }

    return $branch_prefix . '.' . ltrim($reference_no, '.');
}

if (isset($_GET['transaction_id'])) {
    $transaction_id = intval($_GET['transaction_id']);
    
    $stmt = $mysqli->prepare("SELECT reference_no FROM transactions WHERE id = ?");
    $stmt->bind_param('i', $transaction_id);
    $stmt->execute();
    $stmt->bind_result($reference_no);
    $stmt->fetch();

    $branch_prefix = isset($_SESSION['branch']['prefix']) ? $_SESSION['branch']['prefix'] : 'KMA';
    echo normalize_branch_reference($reference_no, $branch_prefix);

    $stmt->close();
}
?>