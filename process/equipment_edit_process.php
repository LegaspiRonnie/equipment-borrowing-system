<?php
include '../config/db.php';

$id = $_POST['id'];
$name = $_POST['name'];
$category = $_POST['category'];
$quantity = $_POST['quantity'];
$status = $_POST['status'];

$image = $_FILES['image']['name'];

if ($image != "") {
    $tmp = $_FILES['image']['tmp_name'];
    move_uploaded_file($tmp, "../assets/images/" . $image);

    $stmt = $conn->prepare("UPDATE equipment SET name=?, category=?, quantity=?, status=?, image=? WHERE id=?");
    $stmt->bind_param("ssissi", $name, $category, $quantity, $status, $image, $id);
} else {
    $stmt = $conn->prepare("UPDATE equipment SET name=?, category=?, quantity=?, status=? WHERE id=?");
    $stmt->bind_param("ssisi", $name, $category, $quantity, $status, $id);
}

$stmt->execute();

header("Location: ../admin/equipment.php");
?>