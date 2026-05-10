<?php
require_once '../config/auth.php';
require_role('admin');

include '../config/db.php';

session_start();

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    $_SESSION['alert'] = [
        'type' => 'error',
        'message' => 'Invalid request method.'
    ];

    header("Location: ../admin/equipment.php");
    exit();
}

// Get form data
$name = trim($_POST['name'] ?? '');
$category = trim($_POST['category'] ?? '');
$quantity = (int) ($_POST['quantity'] ?? 0);
$status = trim($_POST['status'] ?? '');

// Validate inputs
if (
    $name === '' ||
    $category === '' ||
    $quantity <= 0 ||
    $status === ''
) {

    $_SESSION['alert'] = [
        'type' => 'warning',
        'message' => 'Please fill in all required fields.'
    ];

    header("Location: ../admin/equipment.php");
    exit();
}

// Check image upload
if (!isset($_FILES['image']) || $_FILES['image']['error'] !== 0) {

    $_SESSION['alert'] = [
        'type' => 'error',
        'message' => 'Please upload a valid image.'
    ];

    header("Location: ../admin/equipment.php");
    exit();
}

// Image upload
$image = time() . '_' . basename($_FILES['image']['name']);
$tmp = $_FILES['image']['tmp_name'];

$upload_path = "../assets/images/" . $image;

// Move uploaded file
if (!move_uploaded_file($tmp, $upload_path)) {

    $_SESSION['alert'] = [
        'type' => 'error',
        'message' => 'Failed to upload image.'
    ];

    header("Location: ../admin/equipment.php");
    exit();
}

// Insert equipment
$stmt = $conn->prepare("
    INSERT INTO equipment 
    (name, category, quantity, status, image) 
    VALUES (?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "ssiss",
    $name,
    $category,
    $quantity,
    $status,
    $image
);

if ($stmt->execute()) {

    $_SESSION['alert'] = [
        'type' => 'success',
        'message' => 'Equipment added successfully!'
    ];

} else {

    $_SESSION['alert'] = [
        'type' => 'error',
        'message' => 'Could not save equipment to database.'
    ];
}

$stmt->close();
$conn->close();

header("Location: ../admin/equipment.php");
exit();
?>