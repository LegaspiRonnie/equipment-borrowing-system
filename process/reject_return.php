<?php
require_once '../config/auth.php';
require_role('admin');

include '../config/db.php';

session_start();

// Validate ID
if (!isset($_GET['id'])) {

    $_SESSION['alert'] = [
        'type' => 'error',
        'message' => 'Record ID not found.'
    ];

    header("Location: ../admin/return_items.php");
    exit();
}

$id = (int) $_GET['id'];

// Update status back to borrowed
$stmt = $conn->prepare("
    UPDATE borrow_records 
    SET status = 'borrowed' 
    WHERE id = ?
");

$stmt->bind_param("i", $id);

if ($stmt->execute()) {

    $_SESSION['alert'] = [
        'type' => 'success',
        'message' => 'Return request reverted successfully.'
    ];

} else {

    $_SESSION['alert'] = [
        'type' => 'error',
        'message' => 'Failed to revert return request.'
    ];
}

header("Location: ../admin/return_items.php");
exit();
?>