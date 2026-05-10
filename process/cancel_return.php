<?php
require_once '../config/auth.php';
require_role('user');

include '../config/db.php';

session_start();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$user_id = current_user_id();

// Invalid ID
if ($id <= 0) {

    $_SESSION['alert'] = [
        'type' => 'error',
        'message' => 'Invalid item selected.'
    ];

    header("Location: ../user/my_items.php");
    exit();
}

// Change back only the logged-in user's own pending return
$stmt = $conn->prepare("
    UPDATE borrow_records 
    SET status='borrowed' 
    WHERE id=? 
    AND user_id=? 
    AND status='pending'
");

$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();

if ($stmt->affected_rows === 0) {

    $_SESSION['alert'] = [
        'type' => 'warning',
        'message' => 'Unable to cancel return request.'
    ];

    header("Location: ../user/my_items.php");
    exit();
}

// Success
$_SESSION['alert'] = [
    'type' => 'success',
    'message' => 'Return request cancelled successfully.'
];

header("Location: ../user/my_items.php");
exit();
?>