<?php
include '../config/db.php';

$id = $_GET['id'];

// get request
$data = $conn->query("SELECT * FROM borrow_requests WHERE id=$id")->fetch_assoc();

$user_id = $data['user_id'];
$equipment_id = $data['equipment_id'];
$quantity = $data['quantity'];

// approve request
$conn->query("UPDATE borrow_requests SET status='approved' WHERE id=$id");

// INSERT borrow record
$conn->query("
INSERT INTO borrow_records (user_id, equipment_id, quantity, return_date, status)
VALUES ($user_id, $equipment_id, $quantity, '{$data['return_date']}', 'borrowed')
");

// 🔥 DECREASE EQUIPMENT QUANTITY
$conn->query("
UPDATE equipment 
SET quantity = quantity - $quantity 
WHERE id = $equipment_id
");

// history log
$conn->query("
INSERT INTO history_logs (user_id, action, equipment_id, details)
VALUES ($user_id, 'approved', $equipment_id, 'Approved $quantity item(s)')
");

header("Location: ../admin/borrow_requests.php");
exit();
?>