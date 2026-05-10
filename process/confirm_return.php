<?php
require_once '../config/auth.php';
require_role('admin');

include '../config/db.php';

session_start();

// Validate ID
if (!isset($_GET['id'])) {

    $_SESSION['alert'] = [
        'type' => 'error',
        'message' => 'Return record ID is missing.'
    ];

    header("Location: ../admin/return_items.php");
    exit();
}

$id = (int) $_GET['id'];

// Get borrow record
$result = $conn->query("
    SELECT * 
    FROM borrow_records 
    WHERE id = $id
");

$data = $result->fetch_assoc();

if (!$data) {

    $_SESSION['alert'] = [
        'type' => 'error',
        'message' => 'Return record not found.'
    ];

    header("Location: ../admin/return_items.php");
    exit();
}

$user_id = $data['user_id'];
$equipment_id = $data['equipment_id'];
$quantity = $data['quantity'];

// Start transaction
$conn->begin_transaction();

try {

    // Mark as returned
    $returned = $conn->query("
        UPDATE borrow_records 
        SET status='returned' 
        WHERE id=$id
    ");

    // Restore equipment stock
    $stock = $conn->query("
        UPDATE equipment 
        SET quantity = quantity + $quantity 
        WHERE id = $equipment_id
    ");

    // History log
    $history = $conn->query("
        INSERT INTO history_logs 
        (user_id, action, equipment_id, details)
        VALUES (
            $user_id,
            'returned',
            $equipment_id,
            'Returned $quantity item(s)'
        )
    ");

    if ($returned && $stock && $history) {

        $conn->commit();

        $_SESSION['alert'] = [
            'type' => 'success',
            'message' => 'Item returned successfully!'
        ];

    } else {

        throw new Exception('Database operation failed.');
    }

} catch (Exception $e) {

    $conn->rollback();

    $_SESSION['alert'] = [
        'type' => 'error',
        'message' => 'Failed to process returned item.'
    ];
}

header("Location: ../admin/return_items.php");
exit();
?>