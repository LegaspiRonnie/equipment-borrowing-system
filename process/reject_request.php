<?php
session_start();
include '../config/db.php';

$id = $_GET['id'];

// get request
$result = $conn->query("SELECT * FROM borrow_requests WHERE id=$id");
$data = $result->fetch_assoc();

// update status
$conn->query("UPDATE borrow_requests SET status='rejected' WHERE id=$id");

// log
$conn->query("INSERT INTO history_logs (user_id, action, equipment_id, details)
VALUES ({$data['user_id']}, 'rejected', {$data['equipment_id']}, 'Request rejected')");

header("Location: ../admin/borrow_requests.php");
?>