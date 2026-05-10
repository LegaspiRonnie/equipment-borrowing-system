<?php
require_once '../config/auth.php';
require_role('admin');

include '../config/db.php';

session_start();

// Validate ID
if (!isset($_GET['id'])) {

    $_SESSION['alert'] = [
        'type' => 'error',
        'message' => 'User ID not found.'
    ];

    header("Location: ../admin/users.php");
    exit();
}

$id = (int) $_GET['id'];

// Prepare delete
$stmt = $conn->prepare("
    DELETE FROM users 
    WHERE id = ?
");

$stmt->bind_param("i", $id);

// Execute
if ($stmt->execute()) {

    $_SESSION['alert'] = [
        'type' => 'success',
        'message' => 'User deleted successfully.'
    ];

} else {

    $_SESSION['alert'] = [
        'type' => 'error',
        'message' => 'Failed to delete user.'
    ];
}

$stmt->close();
$conn->close();

header("Location: ../admin/users.php");
exit();
?>