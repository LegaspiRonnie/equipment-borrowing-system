<?php
http_response_code(403);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Forbidden | Equipment Borrowing System</title>
    <link rel="stylesheet" href="assets/css/theme.css">
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
            color: white;
            background:
                radial-gradient(circle at 18% 18%, rgba(59, 130, 246, 0.28), transparent 26%),
                radial-gradient(circle at 82% 24%, rgba(20, 184, 166, 0.18), transparent 24%),
                linear-gradient(135deg, #07111f, #111827 48%, #0f172a) !important;
            padding: 24px;
            box-sizing: border-box;
        }

        .forbidden-box {
            width: min(520px, 100%);
            padding: 34px;
            border-radius: 14px;
            background: rgba(15, 23, 42, 0.94);
            border: 1px solid rgba(255, 255, 255, 0.16);
            box-shadow: 0 24px 70px rgba(0, 0, 0, 0.38);
            text-align: center;
        }

        h1 {
            margin: 0 0 12px;
            font-size: 44px;
        }

        p {
            color: #cbd5e1;
            line-height: 1.5;
        }

        a {
            display: inline-block;
            margin-top: 16px;
            padding: 11px 18px;
            border-radius: 8px;
            background: #3b82f6;
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php include '../partials/alert.php'; ?>
    <div class="forbidden-box">
        <h1>403</h1>
        <p>You are not authorized to access this page.</p>
        <a href="index.php">Back to Login</a>
    </div>
</body>
</html>
