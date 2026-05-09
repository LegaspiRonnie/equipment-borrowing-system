<?php

session_start();
include '../config/db.php';
include '../config/recaptcha.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

    // ✅ Validation
    if (empty($email) || empty($password)) {
        header("Location: ../index.php?error=All fields are required");
        exit();
    }

    if (!verify_recaptcha($recaptchaResponse)) {
        header("Location: ../index.php?error=Please complete the reCAPTCHA verification");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../index.php?error=Invalid email format");
        exit();
    }

    // ✅ Check user
    $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // ❌ If user not found
    if ($result->num_rows === 0) {
        header("Location: ../index.php?error=Account not found");
        exit();
    }

    $user = $result->fetch_assoc();

    // ❌ Wrong password
    if (!password_verify($password, $user['password'])) {
        header("Location: ../index.php?error=Incorrect password");
        exit();
    }

    // ✅ SUCCESS LOGIN

    // Store session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['role'] = $user['role'];

    // 🔀 Role-based redirect
    if ($user['role'] == 'admin') {
        header("Location: ../admin/dashboard.php");
    } else {
        header("Location: ../user/dashboard.php");
    }

    exit();

    $stmt->close();
    $conn->close();
}
?>
