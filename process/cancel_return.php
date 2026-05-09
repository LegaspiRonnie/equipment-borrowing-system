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

// change back only the logged-in user's own pending return
$stmt = $conn->prepare("UPDATE borrow_records SET status='borrowed' WHERE id=? AND user_id=? AND status='pending'");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();

if ($stmt->affected_rows === 0) {
    header("Location: ../403.php");
    exit();
}

header("Location: ../user/my_items.php");
exit();
?>
