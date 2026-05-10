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

// Get form data
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$passwordRaw = $_POST['password'] ?? '';
$role = trim($_POST['role'] ?? '');

// Validate inputs
if (
    $name === '' ||
    $email === '' ||
    $passwordRaw === '' ||
    $role === ''
) {

    $_SESSION['alert'] = [
        'type' => 'warning',
        'message' => 'All fields are required.'
    ];

    header("Location: ../admin/users.php");
    exit();
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    $_SESSION['alert'] = [
        'type' => 'error',
        'message' => 'Invalid email format.'
    ];

    header("Location: ../admin/users.php");
    exit();
}

// Hash password
$password = password_hash($passwordRaw, PASSWORD_DEFAULT);

// Insert user
$stmt = $conn->prepare("
    INSERT INTO users (name, email, password, role) 
    VALUES (?, ?, ?, ?)
");

$stmt->bind_param("ssss", $name, $email, $password, $role);

if ($stmt->execute()) {

    $_SESSION['alert'] = [
        'type' => 'success',
        'message' => "User '{$name}' has been created successfully."
    ];

} else {

    $_SESSION['alert'] = [
        'type' => 'error',
        'message' => 'Failed to create user. Email may already exist.'
    ];
}

$stmt->close();
$conn->close();

header("Location: ../admin/users.php");
exit();
?>