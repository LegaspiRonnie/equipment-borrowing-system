<?php
require_once '../config/auth.php';
require_role('admin');

include '../config/db.php';

session_start();

if (!isset($_GET['id'])) {

    $_SESSION['alert'] = [
        'type' => 'error',
        'message' => 'Request ID not found.'
    ];

    header("Location: ../admin/borrow_requests.php");
    exit();
}

$id = (int) $_GET['id'];

// get request
$result = $conn->query("SELECT * FROM borrow_requests WHERE id=$id");

if ($result->num_rows == 0) {

    $_SESSION['alert'] = [
        'type' => 'error',
        'message' => 'Borrow request not found.'
    ];

    header("Location: ../admin/borrow_requests.php");
    exit();
}

$data = $result->fetch_assoc();

$user_id = $data['user_id'];
$equipment_id = $data['equipment_id'];
$quantity = $data['quantity'];

// approve request
$approve = $conn->query("
    UPDATE borrow_requests 
    SET status='approved' 
    WHERE id=$id
");

// insert borrow record
$borrowRecord = $conn->query("
    INSERT INTO borrow_records 
    (user_id, equipment_id, quantity, return_date, status)
    VALUES 
    ($user_id, $equipment_id, $quantity, '{$data['return_date']}', 'borrowed')
");

// decrease equipment quantity
$updateQuantity = $conn->query("
    UPDATE equipment 
    SET quantity = quantity - $quantity 
    WHERE id = $equipment_id
");

// history log
$history = $conn->query("
    INSERT INTO history_logs 
    (user_id, action, equipment_id, details)
    VALUES 
    ($user_id, 'approved', $equipment_id, 'Approved $quantity item(s)')
");

if ($approve && $borrowRecord && $updateQuantity && $history) {

    $_SESSION['alert'] = [
        'type' => 'success',
        'message' => 'Borrow request approved successfully!'
    ];

} else {

    $_SESSION['alert'] = [
        'type' => 'error',
        'message' => 'Failed to approve borrow request.'
    ];
}

header("Location: ../admin/borrow_requests.php");
exit();
?>