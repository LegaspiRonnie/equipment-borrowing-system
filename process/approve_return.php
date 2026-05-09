<?php
require_once '../config/auth.php';
require_role('admin');
include '../config/db.php';

// Check if ID is provided
if (!isset($_GET['id'])) {
    header("Location: ../admin/return_items.php");
    exit();
}

$id = intval($_GET['id']);

// 1. Get borrow record first using prepared statement
$stmt = $conn->prepare("SELECT equipment_id, quantity FROM borrow_records WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    echo "<script>
            alert('Error: Invalid record ID.');
            window.location.href = '../admin/return_items.php';
          </script>";
    exit();
}

$equipment_id = $data['equipment_id'];
$quantity = $data['quantity'];

// START TRANSACTION for data integrity
$conn->begin_transaction();

try {
    // 2. Update borrow record status
    $stmt1 = $conn->prepare("UPDATE borrow_records SET status='returned' WHERE id = ?");
    $stmt1->bind_param("i", $id);
    $stmt1->execute();

    // 3. Return stock back to equipment table
    $stmt2 = $conn->prepare("UPDATE equipment SET quantity = quantity + ? WHERE id = ?");
    $stmt2->bind_param("ii", $quantity, $equipment_id);
    $stmt2->execute();

    // COMMIT changes
    $conn->commit();

    // SUCCESS ALERT
    echo "<script>
            alert('Return approved! $quantity unit(s) have been restored to inventory.');
            window.location.href = '../admin/return_items.php';
          </script>";

} catch (Exception $e) {
    // ROLLBACK on error
    $conn->rollback();
    
    echo "<script>
            alert('Error processing return: " . addslashes($e->getMessage()) . "');
            window.history.back();
          </script>";
}

exit();
?>