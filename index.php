<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login | Equipment Borrowing System</title>

<link rel="stylesheet" href="assets/css/style.css">

<style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background: linear-gradient(135deg, #0f172a, #1e293b);
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .wrapper {
        display: flex;
        width: 900px;
        height: 500px;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.4);
    }

    /* LEFT SIDE (INFO) */
    .left {
        flex: 1;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        padding: 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .left h1 {
        font-size: 28px;
        margin-bottom: 10px;
    }

    .left p {
        font-size: 14px;
        opacity: 0.9;
        line-height: 1.5;
    }

    .badge {
        margin-top: 20px;
        display: inline-block;
        padding: 6px 12px;
        background: rgba(255,255,255,0.2);
        border-radius: 20px;
        font-size: 12px;
    }

    /* RIGHT SIDE (LOGIN) */
    .right {
        flex: 1;
        background: #0f172a;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 30px;
    }

    .form-box {
        width: 100%;
        max-width: 320px;
    }

    .form-box h2 {
        margin-bottom: 20px;
        text-align: center;
    }

    input {
        width: 100%;
        padding: 12px;
        margin: 8px 0;
        border: none;
        border-radius: 8px;
        outline: none;
        background: #1e293b;
        color: white;
    }

    input:focus {
        border: 1px solid #3b82f6;
    }

    button {
        width: 100%;
        padding: 12px;
        margin-top: 10px;
        border: none;
        border-radius: 8px;
        background: #3b82f6;
        color: white;
        font-weight: bold;
        cursor: pointer;
    }

    button:hover {
        background: #2563eb;
    }

    p {
        text-align: center;
        font-size: 13px;
        margin-top: 10px;
        color: #94a3b8;
    }

    a {
        color: #60a5fa;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }

    /* MOBILE */
    @media (max-width: 768px) {
        .wrapper {
            flex-direction: column;
            height: auto;
        }
    }
</style>
</head>

<body>

<?php if (isset($_GET['success'])): ?>
<script>alert("<?php echo $_GET['success']; ?>");</script>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
<script>alert("<?php echo $_GET['error']; ?>");</script>
<?php endif; ?>

<div class="wrapper">

    <!-- LEFT PANEL -->
    <div class="left">
        <h1>Computer Equipment Borrowing System</h1>
        <p>
            Manage borrowing of computer parts such as mouse, keyboard, system unit, projector,
            speaker, microphone, printer, and more.
        </p>

        <div class="badge">📦 Inventory • 📊 Tracking • 🔐 Secure System</div>
    </div>

    <!-- RIGHT PANEL -->
    <div class="right">

        <div class="form-box">
            <h2>Login</h2>

            <form action="process/login_process.php" method="POST">
                <input type="email" name="email" placeholder="Email Address" required>
                <input type="password" name="password" placeholder="Password" required>

                <button type="submit">Login</button>
            </form>

            <p>
                Don't have an account? <a href="register.php">Register</a>
            </p>
        </div>

    </div>

</div>

</body>
</html>