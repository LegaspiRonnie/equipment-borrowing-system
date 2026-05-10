<?php
require_once '../config/auth.php';
require_role('admin');

include '../config/db.php';
session_start();

// Check ID
if (!isset($_GET['id'])) {

    $_SESSION['alert'] = [
        'type' => 'error',
        'message' => 'Equipment ID not found.'
    ];

    header("Location: ../admin/equipment.php");
    exit();
}

$id = intval($_GET['id']);

// START TRANSACTION
$conn->begin_transaction();

try {

    // Delete borrow requests
    $stmt1 = $conn->prepare("
        DELETE FROM borrow_requests 
        WHERE equipment_id = ?
    ");
    $stmt1->bind_param("i", $id);
    $stmt1->execute();

    // Delete borrow records
    $stmt2 = $conn->prepare("
        DELETE FROM borrow_records 
        WHERE equipment_id = ?
    ");
    $stmt2->bind_param("i", $id);
    $stmt2->execute();

    // Delete history logs
    $stmt3 = $conn->prepare("
        DELETE FROM history_logs 
        WHERE equipment_id = ?
    ");
    $stmt3->bind_param("i", $id);
    $stmt3->execute();

    // Delete equipment
    $stmt4 = $conn->prepare("
        DELETE FROM equipment 
        WHERE id = ?
    ");
    $stmt4->bind_param("i", $id);
    $stmt4->execute();

    // Commit all changes
    $conn->commit();

    $_SESSION['alert'] = [
        'type' => 'success',
        'message' => 'Equipment and related records deleted successfully.'
    ];

} catch (Exception $e) {

    $conn->rollback();

    $_SESSION['alert'] = [
        'type' => 'error',
        'message' => 'Failed to delete equipment.'
    ];
}

// redirect (alert will be shown in equipment.php via partials/alert.php)
header("Location: ../admin/equipment.php");
exit();
?>