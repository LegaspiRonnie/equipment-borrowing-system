<?php
require_once '../config/auth.php';
require_role('admin');

include '../config/db.php';

session_start();

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    $_SESSION['alert'] = [
        'type' => 'error',
        'message' => 'Invalid request.'
    ];

    header("Location: ../admin/users.php");
    exit();
}

// Get data safely
$id    = (int) ($_POST['id'] ?? 0);
$name  = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$role  = trim($_POST['role'] ?? '');

// Validate inputs
if (
    $id <= 0 ||
    $name === '' ||
    $email === '' ||
    $role === ''
) {

    $_SESSION['alert'] = [
        'type' => 'warning',
        'message' => 'Please fill in all required fields.'
    ];

    header("Location: ../admin/users.php");
    exit();
}

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    $_SESSION['alert'] = [
        'type' => 'error',
        'message' => 'Invalid email format.'
    ];

    header("Location: ../admin/users.php");
    exit();
}

// Update user
$stmt = $conn->prepare("
    UPDATE users 
    SET name=?, email=?, role=? 
    WHERE id=?
");

$stmt->bind_param("sssi", $name, $email, $role, $id);

if ($stmt->execute()) {

    $_SESSION['alert'] = [
        'type' => 'success',
        'message' => 'User updated successfully!'
    ];

} else {

    $_SESSION['alert'] = [
        'type' => 'error',
        'message' => 'Error updating user.'
    ];
}

$stmt->close();
$conn->close();

header("Location: ../admin/users.php");
exit();
?>