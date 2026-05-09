<?php
require_once '../config/auth.php';
require_role('user');
include '../config/db.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$user_id = current_user_id();

if ($id <= 0) {
    header("Location: ../403.php");
    exit();
}

// update only the logged-in user's own borrowed item
$stmt = $conn->prepare("UPDATE borrow_records SET status='pending' WHERE id=? AND user_id=? AND status='borrowed'");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();

if ($stmt->affected_rows === 0) {
    header("Location: ../403.php");
    exit();
}

header("Location: ../user/my_items.php");
exit();
?>
