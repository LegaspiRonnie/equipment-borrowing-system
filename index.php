<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Equipment System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php if (isset($_GET['success'])): ?>
<script>
    alert("<?php echo $_GET['success']; ?>");
</script>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
<script>
    alert("<?php echo $_GET['error']; ?>");
</script>
<?php endif; ?>

<div class="container">
    <div class="form-box">
        <h2>Login</h2>

        <!-- IMPORTANT: action + method -->
        <form action="process/login_process.php" method="POST">
            <input type="email" name="email" placeholder="Enter Email" required>
            <input type="password" name="password" placeholder="Enter Password" required>

            <button type="submit">Login</button>
        </form>

        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</div>

</body>
</html>