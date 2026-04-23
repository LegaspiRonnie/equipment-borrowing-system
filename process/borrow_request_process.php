<?php
session_start();
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $equipment_id = $_POST['equipment_id'];
    $quantity = $_POST['quantity'];
    $purpose = $_POST['purpose'];
    $return_date = $_POST['return_date'];

    // Insert request
    $stmt = $conn->prepare("INSERT INTO borrow_requests (user_id, equipment_id, quantity, purpose, return_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiss", $user_id, $equipment_id, $quantity, $purpose, $return_date);
    
    if ($stmt->execute()) {
        // History log
        $conn->query("INSERT INTO history_logs (user_id, action, equipment_id, details) VALUES ($user_id, 'request', $equipment_id, 'Requested $quantity item(s)')");
        
        // Set Success Message
        $_SESSION['msg'] = "Request submitted successfully!";
        $_SESSION['msg_type'] = "success";
    } else {
        $_SESSION['msg'] = "Something went wrong.";
        $_SESSION['msg_type'] = "error";
    }

    header("Location: ../user/borrow.php");
    exit();
}