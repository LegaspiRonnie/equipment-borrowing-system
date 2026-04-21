<?php
session_start();
include '../config/db.php';

$user_id = $_SESSION['user_id'];
$equipment_id = $_POST['equipment_id'];
$quantity = $_POST['quantity'];
$purpose = $_POST['purpose'];
$return_date = $_POST['return_date'];

// insert request WITH quantity
$stmt = $conn->prepare("
INSERT INTO borrow_requests (user_id, equipment_id, quantity, purpose, return_date) 
VALUES (?, ?, ?, ?, ?)
");

$stmt->bind_param("iiiss", $user_id, $equipment_id, $quantity, $purpose, $return_date);
$stmt->execute();

// history log
$conn->query("
INSERT INTO history_logs (user_id, action, equipment_id, details)
VALUES ($user_id, 'request', $equipment_id, 'Requested $quantity item(s)')
");

header("Location: ../user/borrow.php?success=Request submitted!");
exit();
?>