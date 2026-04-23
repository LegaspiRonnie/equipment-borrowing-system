<?php
session_start();
include '../config/db.php';

if (!isset($_GET['id'])) {
    header("Location: ../admin/equipment.php");
    exit();
}

$id = intval($_GET['id']);
$user_id = $_SESSION['user_id'] ?? 0;

// START TRANSACTION
$conn->begin_transaction();

try {

    // 1. DELETE FROM borrow_requests (FIRST - most dependent)
    $stmt1 = $conn->prepare("DELETE FROM borrow_requests WHERE equipment_id = ?");
    $stmt1->bind_param("i", $id);
    $stmt1->execute();

    // 2. DELETE FROM borrow_records
    $stmt2 = $conn->prepare("DELETE FROM borrow_records WHERE equipment_id = ?");
    $stmt2->bind_param("i", $id);
    $stmt2->execute();

    // 3. DELETE FROM history_logs (optional but recommended)
    $stmt3 = $conn->prepare("DELETE FROM history_logs WHERE equipment_id = ?");
    $stmt3->bind_param("i", $id);
    $stmt3->execute();

    // 4. NOW DELETE THE MAIN EQUIPMENT
    $stmt4 = $conn->prepare("DELETE FROM equipment WHERE id = ?");
    $stmt4->bind_param("i", $id);
    $stmt4->execute();

    // COMMIT ALL CHANGES
    $conn->commit();

    header("Location: ../admin/equipment.php?success=deleted");
    exit();

} catch (Exception $e) {

    // ROLLBACK IF ANY ERROR
    $conn->rollback();

    echo "Error deleting equipment: " . $e->getMessage();
}
?>