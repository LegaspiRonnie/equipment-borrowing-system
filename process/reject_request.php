<?php
require_once '../config/auth.php';
require_role('admin');

include '../config/db.php';

session_start();

// Validate ID
if (!isset($_GET['id'])) {

    $_SESSION['alert'] = [
        'type' => 'error',
        'message' => 'Request ID not found.'
    ];

    header("Location: ../admin/borrow_requests.php");
    exit();
}

$id = intval($_GET['id']);

// Get request details
$stmt = $conn->prepare("
    SELECT user_id, equipment_id 
    FROM borrow_requests 
    WHERE id = ?
");

$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {

    $_SESSION['alert'] = [
        'type' => 'error',
        'message' => 'Request record not found.'
    ];

    header("Location: ../admin/borrow_requests.php");
    exit();
}

try {

    // Update status to rejected
    $updateStmt = $conn->prepare("
        UPDATE borrow_requests 
        SET status = 'rejected' 
        WHERE id = ?
    ");

    $updateStmt->bind_param("i", $id);
    $updateStmt->execute();

    // Insert history log
    $logStmt = $conn->prepare("
        INSERT INTO history_logs 
        (user_id, action, equipment_id, details) 
        VALUES (?, 'rejected', ?, 'Request rejected')
    ");

    $logStmt->bind_param(
        "ii",
        $data['user_id'],
        $data['equipment_id']
    );

    $logStmt->execute();

    $_SESSION['alert'] = [
        'type' => 'success',
        'message' => 'Request rejected successfully.'
    ];

} catch (Exception $e) {

    $_SESSION['alert'] = [
        'type' => 'error',
        'message' => 'Failed to reject request.'
    ];
}

header("Location: ../admin/borrow_requests.php");
exit();
?>