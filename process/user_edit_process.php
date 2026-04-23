<?php
include '../config/db.php';

$id = $_POST['id'];
$name = $_POST['name'];
$email = $_POST['email'];
$role = $_POST['role'];

// Prepare the update statement
$stmt = $conn->prepare("UPDATE users SET name=?, email=?, role=? WHERE id=?");
$stmt->bind_param("sssi", $name, $email, $role, $id);

// Execute and check for success
if ($stmt->execute()) {
    echo "<script>
            alert('User updated successfully!');
            window.location.href = '../admin/users.php';
          </script>";
} else {
    echo "<script>
            alert('Error updating user: " . addslashes($conn->error) . "');
            window.history.back();
          </script>";
}

$stmt->close();
$conn->close();
?>