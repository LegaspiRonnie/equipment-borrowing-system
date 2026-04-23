<?php
session_start();
include '../config/db.php';

// Check if ID is provided
if (!isset($_GET['id'])) {
    header("Location: ../admin/borrow_requests.php");
    exit();
}

$id = intval($_GET['id']);

// 1. Get request details (using prepared statement for security)
$stmt = $conn->prepare("SELECT user_id, equipment_id FROM borrow_requests WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if ($data) {
    // 2. Update status to rejected
    $updateStmt = $conn->prepare("UPDATE borrow_requests SET status = 'rejected' WHERE id = ?");
    $updateStmt->bind_param("i", $id);
    $updateStmt->execute();

    // 3. Insert into history logs
    $logStmt = $conn->prepare("INSERT INTO history_logs (user_id, action, equipment_id, details) VALUES (?, 'rejected', ?, 'Request rejected')");
    $logStmt->bind_param("ii", $data['user_id'], $data['equipment_id']);
    $logStmt->execute();

    // 4. Success Alert and Redirect
    echo "<script>
            alert('Request has been rejected and logged successfully.');
            window.location.href = '../admin/borrow_requests.php';
          </script>";
} else {
    // 5. Error if request not found
    echo "<script>
            alert('Error: Request record not found.');
            window.location.href = '../admin/borrow_requests.php';
          </script>";
}

exit();
?>