<?php
include '../config/db.php';

$id = $_GET['id'];

$data = $conn->query("SELECT * FROM borrow_records WHERE id=$id")->fetch_assoc();

$user_id = $data['user_id'];
$equipment_id = $data['equipment_id'];
$quantity = $data['quantity'];

// mark returned
$conn->query("UPDATE borrow_records SET status='returned' WHERE id=$id");

// return equipment stock
$conn->query("
UPDATE equipment 
SET quantity = quantity + $quantity 
WHERE id = $equipment_id
");

// history log
$conn->query("
INSERT INTO history_logs (user_id, action, equipment_id, details)
VALUES ($user_id, 'returned', $equipment_id, 'Returned $quantity item(s)')
");

header("Location: ../admin/return_items.php");
exit();
?>