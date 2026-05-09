<?php

include '../config/db.php';
include '../config/recaptcha.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

    // Validation
    if (empty($name) || empty($email) || empty($password)) {
        header("Location: ../register.php?error=All fields are required");
        exit();
    }

    if (!verify_recaptcha($recaptchaResponse)) {
        header("Location: ../register.php?error=Please complete the reCAPTCHA verification");
        exit();
    }

    // Check if email exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        header("Location: ../register.php?error=Email already exists");
        exit();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $hashed_password);

    if ($stmt->execute()) {
        header("Location: ../index.php?success=Registered successfully! You can now login.");
        exit();
    } else {
        header("Location: ../register.php?error=Something went wrong");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
