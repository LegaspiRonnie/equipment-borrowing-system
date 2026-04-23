<?php
include '../config/db.php';

// 1. Validate the ID exists and is a number
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // 2. Use a prepared statement for security
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // 3. Success Alert and Redirect
        echo "<script>
                alert('User deleted successfully.');
                window.location.href = '../admin/users.php';
              </script>";
    } else {
        // 4. Error Alert
        echo "<script>
                alert('Error: Could not delete user.');
                window.history.back();
              </script>";
    }
    
    $stmt->close();
} else {
    // Redirect if no ID is provided
    header("Location: ../admin/users.php");
}

$conn->close();
exit();
?>