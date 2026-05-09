<?php
require_once '../config/auth.php';
require_role('admin');
include '../config/db.php';

if (!isset($_GET['id'])) {
    header("Location: ../admin/equipment.php");
    exit();
}

$id = intval($_GET['id']);

// START TRANSACTION
$conn->begin_transaction();

try {
    // 1. DELETE FROM borrow_requests
    $stmt1 = $conn->prepare("DELETE FROM borrow_requests WHERE equipment_id = ?");
    $stmt1->bind_param("i", $id);
    $stmt1->execute();

    // 2. DELETE FROM borrow_records
    $stmt2 = $conn->prepare("DELETE FROM borrow_records WHERE equipment_id = ?");
    $stmt2->bind_param("i", $id);
    $stmt2->execute();

    // 3. DELETE FROM history_logs
    $stmt3 = $conn->prepare("DELETE FROM history_logs WHERE equipment_id = ?");
    $stmt3->bind_param("i", $id);
    $stmt3->execute();

    // 4. DELETE THE MAIN EQUIPMENT
    $stmt4 = $conn->prepare("DELETE FROM equipment WHERE id = ?");
    $stmt4->bind_param("i", $id);
    $stmt4->execute();

    // COMMIT ALL CHANGES
    $conn->commit();

    // Success Alert and Redirect
    echo "<script>
            alert('Equipment and all related records deleted successfully.');
            window.location.href = '../admin/equipment.php';
          </script>";
    exit();

} catch (Exception $e) {
    // ROLLBACK IF ANY ERROR
    $conn->rollback();

    // Error Alert and Redirect back
    echo "<script>
            alert('Error deleting equipment: " . addslashes($e->getMessage()) . "');
            window.history.back();
          </script>";
    exit();
}
?>