<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register | Equipment Borrowing System</title>

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
        height: 520px;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.4);
    }

    /* LEFT INFO */
    .left {
        flex: 1;
        background: linear-gradient(135deg, #10b981, #059669);
        padding: 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .left h1 {
        font-size: 26px;
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

    /* RIGHT FORM */
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
        text-align: center;
        margin-bottom: 15px;
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
        border: 1px solid #10b981;
    }

    button {
        width: 100%;
        padding: 12px;
        margin-top: 10px;
        border: none;
        border-radius: 8px;
        background: #10b981;
        color: white;
        font-weight: bold;
        cursor: pointer;
    }

    button:hover {
        background: #059669;
    }

    p {
        text-align: center;
        font-size: 13px;
        margin-top: 10px;
        color: #94a3b8;
    }

    a {
        color: #34d399;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }

    .error {
        color: #f87171;
        text-align: center;
        font-size: 13px;
        margin-bottom: 10px;
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

<div class="wrapper">

    <!-- LEFT PANEL -->
    <div class="left">
        <h1>Create Account</h1>
        <p>
            Join the Computer Equipment Borrowing System to manage borrowing of devices like mouse,
            keyboard, system unit, projector, printer, and more.
        </p>

        <div class="badge">🔐 Secure • 📦 Inventory • 📊 Tracking System</div>
    </div>

    <!-- RIGHT PANEL -->
    <div class="right">

        <div class="form-box">
            <h2>Register</h2>

            <!-- ERROR -->
            <?php if (isset($_GET['error'])): ?>
                <div class="error"><?php echo $_GET['error']; ?></div>
            <?php endif; ?>

            <form action="process/register_process.php" method="POST">

                <input type="text" name="name" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email Address" required>
                <input type="password" name="password" placeholder="Password" required>

                <button type="submit">Create Account</button>
            </form>

            <p>
                Already have an account? <a href="index.php">Login</a>
            </p>
        </div>

    </div>

</div>

</body>
</html>