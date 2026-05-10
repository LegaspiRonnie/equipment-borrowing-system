<?php

session_start();

include '../config/db.php';
include '../config/recaptcha.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

    // Validate empty fields
    if (
        empty($name) ||
        empty($email) ||
        empty($password)
    ) {

        $_SESSION['alert'] = [
            'type' => 'warning',
            'message' => 'All fields are required.'
        ];

        header("Location: ../register.php");
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $_SESSION['alert'] = [
            'type' => 'error',
            'message' => 'Invalid email format.'
        ];

        header("Location: ../register.php");
        exit();
    }

    // Validate reCAPTCHA
    if (!verify_recaptcha($recaptchaResponse)) {

        $_SESSION['alert'] = [
            'type' => 'warning',
            'message' => 'Please complete the reCAPTCHA verification.'
        ];

        header("Location: ../register.php");
        exit();
    }

    // Check if email exists
    $check = $conn->prepare("
        SELECT id 
        FROM users 
        WHERE email = ?
    ");

    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {

        $_SESSION['alert'] = [
            'type' => 'warning',
            'message' => 'Email already exists.'
        ];

        header("Location: ../register.php");
        exit();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user
    $stmt = $conn->prepare("
        INSERT INTO users 
        (name, email, password) 
        VALUES (?, ?, ?)
    ");

    $stmt->bind_param(
        "sss",
        $name,
        $email,
        $hashed_password
    );

    if ($stmt->execute()) {

        $_SESSION['alert'] = [
            'type' => 'success',
            'message' => 'Registered successfully! You can now login.'
        ];

        header("Location: ../index.php");

    } else {

        $_SESSION['alert'] = [
            'type' => 'error',
            'message' => 'Something went wrong during registration.'
        ];

        header("Location: ../register.php");
    }

    $stmt->close();
    $conn->close();

    exit();
}
?>