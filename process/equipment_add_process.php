<?php
include '../config/db.php';

$name = $_POST['name'];
$category = $_POST['category'];
$quantity = $_POST['quantity'];
$status = $_POST['status'];

// IMAGE UPLOAD
$image = $_FILES['image']['name'];
$tmp = $_FILES['image']['tmp_name'];

move_uploaded_file($tmp, "../assets/images/" . $image);

$stmt = $conn->prepare("INSERT INTO equipment (name, category, quantity, status, image) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssiss", $name, $category, $quantity, $status, $image);

$stmt->execute();

header("Location: ../admin/equipment.php");
?>