<?php
require_once '../config/auth.php';
require_role('user');

include '../config/db.php';

session_start();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$user_id = current_user_id();

// Validate ID
if ($id <= 0) {

    $_SESSION['alert'] = [
        'type' => 'error',
        'message' => 'Invalid request.'
    ];

    header("Location: ../user/my_items.php");
    exit();
}

// Update only the logged-in user's own borrowed item
$stmt = $conn->prepare("
    UPDATE borrow_records 
    SET status='pending' 
    WHERE id=? 
    AND user_id=? 
    AND status='borrowed'
");

$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();

// If no rows affected (invalid or unauthorized action)
if ($stmt->affected_rows === 0) {

    $_SESSION['alert'] = [
        'type' => 'error',
        'message' => 'You are not allowed to perform this action.'
    ];

    header("Location: ../user/my_items.php");
    exit();
}

// Success
$_SESSION['alert'] = [
    'type' => 'success',
    'message' => 'Item successfully moved to pending.'
];

header("Location: ../user/my_items.php");
exit();
?>