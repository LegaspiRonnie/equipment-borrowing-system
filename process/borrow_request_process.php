<?php
require_once '../config/auth.php';
require_role('user');

include '../config/db.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $user_id = current_user_id();

    $equipment_id = isset($_POST['equipment_id']) 
        ? (int) $_POST['equipment_id'] 
        : 0;

    $quantity = isset($_POST['quantity']) 
        ? (int) $_POST['quantity'] 
        : 0;

    $purpose = trim($_POST['purpose'] ?? '');
    $return_date = trim($_POST['return_date'] ?? '');

    // Validation
    if (
        $equipment_id <= 0 ||
        $quantity <= 0 ||
        $purpose === '' ||
        $return_date === ''
    ) {

        $_SESSION['alert'] = [
            'type' => 'warning',
            'message' => 'Please complete all required fields.'
        ];

        header("Location: ../user/borrow.php");
        exit();
    }

    // Insert borrow request
    $stmt = $conn->prepare("
        INSERT INTO borrow_requests 
        (user_id, equipment_id, quantity, purpose, return_date) 
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "iiiss",
        $user_id,
        $equipment_id,
        $quantity,
        $purpose,
        $return_date
    );

    if ($stmt->execute()) {

        // History log
        $conn->query("
            INSERT INTO history_logs 
            (user_id, action, equipment_id, details) 
            VALUES (
                $user_id,
                'request',
                $equipment_id,
                'Requested $quantity item(s)'
            )
        ");

        $_SESSION['alert'] = [
            'type' => 'success',
            'message' => 'Borrow request submitted successfully!'
        ];

    } else {

        $_SESSION['alert'] = [
            'type' => 'error',
            'message' => 'Something went wrong while submitting your request.'
        ];
    }

    header("Location: ../user/borrow.php");
    exit();
}
?>