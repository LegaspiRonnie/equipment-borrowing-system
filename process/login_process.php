<?php

session_start();

include '../config/db.php';
include '../config/recaptcha.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

    // Validate empty fields
    if (empty($email) || empty($password)) {

        $_SESSION['alert'] = [
            'type' => 'warning',
            'message' => 'All fields are required.'
        ];

        header("Location: ../index.php");
        exit();
    }

    // Validate reCAPTCHA
    if (!verify_recaptcha($recaptchaResponse)) {

        $_SESSION['alert'] = [
            'type' => 'warning',
            'message' => 'Please complete the reCAPTCHA verification.'
        ];

        header("Location: ../index.php");
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $_SESSION['alert'] = [
            'type' => 'error',
            'message' => 'Invalid email format.'
        ];

        header("Location: ../index.php");
        exit();
    }

    // Check user
    $stmt = $conn->prepare("
        SELECT id, name, email, password, role 
        FROM users 
        WHERE email = ?
    ");

    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    // User not found
    if ($result->num_rows === 0) {

        $_SESSION['alert'] = [
            'type' => 'error',
            'message' => 'Account not found.'
        ];

        header("Location: ../index.php");
        exit();
    }

    $user = $result->fetch_assoc();

    // Wrong password
    if (!password_verify($password, $user['password'])) {

        $_SESSION['alert'] = [
            'type' => 'error',
            'message' => 'Incorrect password.'
        ];

        header("Location: ../index.php");
        exit();
    }

    // Success login
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['role'] = $user['role'];

    $_SESSION['alert'] = [
        'type' => 'success',
        'message' => 'Login successful. Welcome back!'
    ];

    // Role-based redirect
    if ($user['role'] == 'admin') {

        header("Location: ../admin/dashboard.php");

    } else {

        header("Location: ../user/dashboard.php");
    }

    $stmt->close();
    $conn->close();

    exit();
}
?>