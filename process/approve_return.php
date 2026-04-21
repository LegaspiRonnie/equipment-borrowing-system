<?php
include '../config/db.php';

$id = $_GET['id'];

// get borrow record first
$data = $conn->query("SELECT * FROM borrow_records WHERE id=$id")->fetch_assoc();

if (!$data) {
    die("Invalid record");
}

$equipment_id = $data['equipment_id'];
$quantity = $data['quantity'];

// 1. update borrow record status
$conn->query("
UPDATE borrow_records 
SET status='returned' 
WHERE id=$id
");

// 2. return stock back to equipment table
$conn->query("
UPDATE equipment 
SET quantity = quantity + $quantity 
WHERE id = $equipment_id
");

header("Location: ../admin/return_items.php");
exit();
?>