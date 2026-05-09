<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login | Equipment Borrowing System</title>

<link rel="stylesheet" href="assets/css/style.css">
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background:
            radial-gradient(circle at 18% 18%, rgba(59, 130, 246, 0.28), transparent 26%),
            radial-gradient(circle at 82% 24%, rgba(20, 184, 166, 0.18), transparent 24%),
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
        min-height: 520px;
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid rgba(255,255,255,0.16);
        box-shadow: 0 24px 70px rgba(0,0,0,0.48);
    }

    /* LEFT SIDE (INFO) */
    .left {
        flex: 1;
        background:
            linear-gradient(135deg, rgba(37, 99, 235, 0.95), rgba(14, 116, 144, 0.94));
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

    .icon-laptop {
        border: 3px solid #dbeafe;
        border-radius: 5px 5px 2px 2px;
    }

    .icon-laptop::after {
        content: "";
        position: absolute;
        left: -7px;
        right: -7px;
        bottom: -9px;
        height: 5px;
        background: #dbeafe;
        border-radius: 2px;
    }

    .icon-keyboard {
        width: 44px;
        height: 25px;
        border: 3px solid #dbeafe;
        border-radius: 6px;
        background-image:
            linear-gradient(#dbeafe 0 0),
            linear-gradient(#dbeafe 0 0),
            linear-gradient(#dbeafe 0 0);
        background-size: 5px 5px;
        background-position: 8px 7px, 19px 7px, 30px 7px;
        background-repeat: no-repeat;
    }

    .icon-projector {
        width: 44px;
        height: 26px;
        border: 3px solid #dbeafe;
        border-radius: 6px;
    }

    .icon-projector::after {
        content: "";
        position: absolute;
        right: 7px;
        top: 7px;
        width: 8px;
        height: 8px;
        border: 3px solid #dbeafe;
        border-radius: 50%;
    }

    .icon-mouse {
        width: 26px;
        height: 38px;
        border: 3px solid #dbeafe;
        border-radius: 15px;
    }

    .icon-mouse::before {
        content: "";
        position: absolute;
        left: 50%;
        top: 6px;
        width: 3px;
        height: 9px;
        background: #dbeafe;
        transform: translateX(-50%);
    }

    .badge {
        display: inline-block;
        padding: 6px 12px;
        background: rgba(255,255,255,0.2);
        border-radius: 20px;
        font-size: 12px;
    }

    /* RIGHT SIDE (LOGIN) */
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

<?php include 'config/recaptcha.php'; ?>

<?php if (isset($_GET['success'])): ?>
<script>alert("<?php echo htmlspecialchars($_GET['success'], ENT_QUOTES); ?>");</script>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
<script>alert("<?php echo htmlspecialchars($_GET['error'], ENT_QUOTES); ?>");</script>
<?php endif; ?>

<div class="wrapper">

    <!-- LEFT PANEL -->
    <div class="left">
        <p>
            Manage borrowing of computer parts such as mouse, keyboard, system unit, projector,
            speaker, microphone, printer, and more.
        </p>

        <div class="equipment-icons" aria-label="Computer equipment examples">
            <div class="equipment-icon">
                <span class="icon-symbol icon-laptop"></span>
                Laptop
            </div>
            <div class="equipment-icon">
                <span class="icon-symbol icon-keyboard"></span>
                Keyboard
            </div>
            <div class="equipment-icon">
                <span class="icon-symbol icon-projector"></span>
                Projector
            </div>
            <div class="equipment-icon">
                <span class="icon-symbol icon-mouse"></span>
                Mouse
            </div>
        </div>

        <div class="badge">Inventory - Tracking - Secure System</div>
    </div>

    <!-- RIGHT PANEL -->
    <div class="right">

        <div class="form-box">
            <h2>Login</h2>

            <form action="process/login_process.php" method="POST">
                <input type="email" name="email" placeholder="Email Address" required>
                <input type="password" name="password" placeholder="Password" required>
                <div class="captcha-box">
                    <div class="g-recaptcha" data-sitekey="<?php echo RECAPTCHA_SITE_KEY; ?>"></div>
                </div>

                <button type="submit">Login</button>
            </form>

            <p>
                Don't have an account? <a href="register.php">Register</a>
            </p>
        </div>

    </div>

</div>

<?php include 'includes/footer.html'; ?>
