<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register | Equipment Borrowing System</title>

<link rel="stylesheet" href="assets/css/style.css">
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background:
            radial-gradient(circle at 18% 18%, rgba(16, 185, 129, 0.26), transparent 26%),
            radial-gradient(circle at 82% 24%, rgba(20, 184, 166, 0.2), transparent 24%),
            linear-gradient(135deg, #07111f, #111827 48%, #0f172a);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        padding: 24px;
        box-sizing: border-box;
    }

    .wrapper {
        display: flex;
        width: min(940px, 100%);
        min-height: 540px;
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid rgba(255,255,255,0.16);
        box-shadow: 0 24px 70px rgba(0,0,0,0.48);
    }

    /* LEFT INFO */
    .left {
        flex: 1;
        background:
            linear-gradient(135deg, rgba(16, 185, 129, 0.95), rgba(14, 116, 144, 0.94));
        padding: 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 18px;
    }

    .left p {
        font-size: 14px;
        opacity: 0.9;
        line-height: 1.5;
    }

    .equipment-icons {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
        margin-top: 4px;
    }

    .equipment-icon {
        width: 100%;
        height: 92px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border-radius: 8px;
        border: 1px solid rgba(255,255,255,0.22);
        background: rgba(255,255,255,0.14);
        box-shadow: 0 10px 22px rgba(0,0,0,0.22);
        font-size: 12px;
        font-weight: bold;
    }

    .icon-symbol {
        position: relative;
        display: block;
        width: 38px;
        height: 30px;
    }

    .icon-monitor {
        border: 3px solid #dcfce7;
        border-radius: 5px;
    }

    .icon-monitor::after {
        content: "";
        position: absolute;
        left: 12px;
        right: 12px;
        bottom: -11px;
        height: 8px;
        border-bottom: 3px solid #dcfce7;
        border-left: 3px solid #dcfce7;
        border-right: 3px solid #dcfce7;
    }

    .icon-system {
        width: 28px;
        height: 40px;
        border: 3px solid #dcfce7;
        border-radius: 6px;
    }

    .icon-system::before,
    .icon-system::after {
        content: "";
        position: absolute;
        left: 8px;
        width: 8px;
        height: 3px;
        background: #dcfce7;
        border-radius: 2px;
    }

    .icon-system::before {
        top: 9px;
    }

    .icon-system::after {
        top: 18px;
    }

    .icon-printer {
        width: 42px;
        height: 28px;
        border: 3px solid #dcfce7;
        border-radius: 6px;
    }

    .icon-printer::before {
        content: "";
        position: absolute;
        left: 8px;
        right: 8px;
        top: -12px;
        height: 12px;
        border: 3px solid #dcfce7;
        border-bottom: 0;
        border-radius: 4px 4px 0 0;
    }

    .icon-cable {
        width: 44px;
        height: 28px;
        border-top: 4px solid #dcfce7;
        border-radius: 50%;
    }

    .icon-cable::before,
    .icon-cable::after {
        content: "";
        position: absolute;
        bottom: 3px;
        width: 12px;
        height: 10px;
        border: 3px solid #dcfce7;
        border-radius: 3px;
    }

    .icon-cable::before {
        left: 0;
    }

    .icon-cable::after {
        right: 0;
    }

    .badge {
        display: inline-block;
        padding: 6px 12px;
        background: rgba(255,255,255,0.2);
        border-radius: 20px;
        font-size: 12px;
    }

    /* RIGHT FORM */
    .right {
        flex: 1;
        background: rgba(15, 23, 42, 0.96);
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

    .captcha-box {
        display: flex;
        justify-content: center;
        margin: 12px 0 4px;
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

        .equipment-icons {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }

        .equipment-icon {
            height: 64px;
        }
    }
</style>
</head>

<body>
    <?php include 'partials/alert.php'; ?>

<?php include 'config/recaptcha.php'; ?>

<div class="wrapper">

    <!-- LEFT PANEL -->
    <div class="left">
        <p>
            Join the Computer Equipment Borrowing System to manage borrowing of devices like mouse,
            keyboard, system unit, projector, printer, and more.
        </p>

        <div class="equipment-icons" aria-label="Computer equipment examples">
            <div class="equipment-icon">
                <span class="icon-symbol icon-monitor"></span>
                Monitor
            </div>
            <div class="equipment-icon">
                <span class="icon-symbol icon-system"></span>
                System Unit
            </div>
            <div class="equipment-icon">
                <span class="icon-symbol icon-printer"></span>
                Printer
            </div>
            <div class="equipment-icon">
                <span class="icon-symbol icon-cable"></span>
                HDMI Cable
            </div>
        </div>

        <div class="badge">Secure - Inventory - Tracking System</div>
    </div>

    <!-- RIGHT PANEL -->
    <div class="right">

        <div class="form-box">
            <h2>Register</h2>

            <!-- ERROR -->
            <?php if (isset($_GET['error'])): ?>
                <div class="error"><?php echo htmlspecialchars($_GET['error'], ENT_QUOTES); ?></div>
            <?php endif; ?>

            <form action="process/register_process.php" method="POST">

                <input type="text" name="name" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email Address" required>
                <input type="password" name="password" placeholder="Password" required>
                <div class="captcha-box">
                    <div class="g-recaptcha" data-sitekey="<?php echo RECAPTCHA_SITE_KEY; ?>"></div>
                </div>

                <button type="submit">Create Account</button>
            </form>

            <p>
                Already have an account? <a href="index.php">Login</a>
            </p>
        </div>

    </div>

</div>

<?php include 'includes/footer.html'; ?>
