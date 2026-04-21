<?php
session_start();
include '../config/db.php';

$id = $_GET['id'];

// update status to pending
$stmt = $conn->prepare("UPDATE borrow_records SET status='pending' WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: ../user/my_items.php");
exit();
?>