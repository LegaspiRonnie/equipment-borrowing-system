<?php
require_once '../config/auth.php';
require_role('admin');

include '../config/db.php';

session_start();

// Check if ID is provided
if (!isset($_GET['id'])) {

    $_SESSION['alert'] = [
        'type' => 'error',
        'message' => 'Record ID not provided.'
    ];

    header("Location: ../admin/return_items.php");
    exit();
}

$id = intval($_GET['id']);

// Get borrow record first
$stmt = $conn->prepare("
    SELECT equipment_id, quantity 
    FROM borrow_records 
    WHERE id = ?
");

$stmt->bind_param("i", $id);
$stmt->execute();

$data = $stmt->get_result()->fetch_assoc();

if (!$data) {

    $_SESSION['alert'] = [
        'type' => 'error',
        'message' => 'Invalid return record.'
    ];

    header("Location: ../admin/return_items.php");
    exit();
}

$equipment_id = $data['equipment_id'];
$quantity = $data['quantity'];

// START TRANSACTION
$conn->begin_transaction();

try {

    // Update borrow record status
    $stmt1 = $conn->prepare("
        UPDATE borrow_records 
        SET status='returned' 
        WHERE id = ?
    ");

    $stmt1->bind_param("i", $id);
    $stmt1->execute();

    // Restore equipment quantity
    $stmt2 = $conn->prepare("
        UPDATE equipment 
        SET quantity = quantity + ? 
        WHERE id = ?
    ");

    $stmt2->bind_param("ii", $quantity, $equipment_id);
    $stmt2->execute();

    // Commit transaction
    $conn->commit();

    $_SESSION['alert'] = [
        'type' => 'success',
        'message' => "Return approved! {$quantity} unit(s) restored to inventory."
    ];

    header("Location: ../admin/return_items.php");
    exit();

} catch (Exception $e) {

    // Rollback on error
    $conn->rollback();

    $_SESSION['alert'] = [
        'type' => 'error',
        'message' => 'Error processing return.'
    ];

    header("Location: ../admin/return_items.php");
    exit();
}
?>