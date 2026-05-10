<?php
require_once '../config/auth.php';
require_role('admin');

include '../config/db.php';

session_start();

// Validate request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    $_SESSION['alert'] = [
        'type' => 'error',
        'message' => 'Invalid request method.'
    ];

    header("Location: ../admin/equipment.php");
    exit();
}

// Get form data
$id       = (int) ($_POST['id'] ?? 0);
$name     = trim($_POST['name'] ?? '');
$category = trim($_POST['category'] ?? '');
$quantity = (int) ($_POST['quantity'] ?? 0);
$status   = trim($_POST['status'] ?? '');

// Validate inputs
if (
    $id <= 0 ||
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

// Check if image uploaded
$image = $_FILES['image']['name'] ?? '';

if ($image != "") {

    $tmp = $_FILES['image']['tmp_name'];

    // Create unique filename
    $image = time() . '_' . basename($image);

    $uploadPath = "../assets/images/" . $image;

    // Upload image
    if (!move_uploaded_file($tmp, $uploadPath)) {

        $_SESSION['alert'] = [
            'type' => 'error',
            'message' => 'Failed to upload image.'
        ];

        header("Location: ../admin/equipment.php");
        exit();
    }

    // Update with image
    $stmt = $conn->prepare("
        UPDATE equipment 
        SET name=?, category=?, quantity=?, status=?, image=? 
        WHERE id=?
    ");

    $stmt->bind_param(
        "ssissi",
        $name,
        $category,
        $quantity,
        $status,
        $image,
        $id
    );

} else {

    // Update without image
    $stmt = $conn->prepare("
        UPDATE equipment 
        SET name=?, category=?, quantity=?, status=? 
        WHERE id=?
    ");

    $stmt->bind_param(
        "ssisi",
        $name,
        $category,
        $quantity,
        $status,
        $id
    );
}

// Execute update
if ($stmt->execute()) {

    $_SESSION['alert'] = [
        'type' => 'success',
        'message' => 'Equipment updated successfully!'
    ];

} else {

    $_SESSION['alert'] = [
        'type' => 'error',
        'message' => 'Error updating equipment.'
    ];
}

header("Location: ../admin/equipment.php");
exit();
?>