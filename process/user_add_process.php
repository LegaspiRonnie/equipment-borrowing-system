<?php
require_once '../config/auth.php';
require_role('admin');
include '../config/db.php';

$name = $_POST['name'];
$email = $_POST['email'];
// Always a good idea to check if password isn't empty before hashing
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$role = $_POST['role'];

// Prepare the statement
$stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $email, $password, $role);

// Execute and check for success
if ($stmt->execute()) {
    echo "<script>
            alert('New user \'$name\' has been successfully created.');
            window.location.href = '../admin/users.php';
          </script>";
} else {
    // Handle errors (like duplicate emails)
    echo "<script>
            alert('Error: Could not create user. The email might already be in use.');
            window.history.back();
          </script>";
}

$stmt->close();
$conn->close();
?>