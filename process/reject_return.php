<?php
include '../config/db.php';

$id = $_GET['id'];

// set status back to borrowed
$conn->query("
UPDATE borrow_records 
SET status='borrowed' 
WHERE id=$id
");

header("Location: ../admin/return_items.php");
exit();
?>