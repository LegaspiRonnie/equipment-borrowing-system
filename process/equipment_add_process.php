<?php
require_once '../config/auth.php';
require_role('admin');
include '../config/db.php';

$name = $_POST['name'];
$category = $_POST['category'];
$quantity = $_POST['quantity'];
$status = $_POST['status'];

// IMAGE UPLOAD logic
$image = $_FILES['image']['name'];
$tmp = $_FILES['image']['tmp_name'];
$upload_path = "../assets/images/" . $image;

if (move_uploaded_file($tmp, $upload_path)) {
    $stmt = $conn->prepare("INSERT INTO equipment (name, category, quantity, status, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiss", $name, $category, $quantity, $status, $image);

    if ($stmt->execute()) {
        // Success Alert
        echo "<script>
                alert('Equipment added successfully!');
                window.location.href='../admin/equipment.php';
              </script>";
    } else {
        // Database Error Alert
        echo "<script>
                alert('Error: Could not save to database.');
                window.history.back();
              </script>";
    }
    $stmt->close();
} else {
    // File Upload Error Alert
    echo "<script>
            alert('Failed to upload image. Please check folder permissions.');
            window.history.back();
          </script>";
}

$conn->close();
?>