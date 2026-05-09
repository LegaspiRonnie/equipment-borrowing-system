<?php
require_once '../config/auth.php';
require_role('admin');
include '../config/db.php';

$id = intval($_GET['id']); // Secure integer casting
$result = $conn->query("SELECT * FROM users WHERE id=$id");
$user = $result->fetch_assoc();

if (!$user) {
    header("Location: users.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile | <?php echo htmlspecialchars($user['name']); ?></title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <style>
        :root {
            --primary: #2563eb;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --bg-gray: #f8fafc;
        }

        body { font-family: 'Inter', sans-serif; background: var(--bg-gray); margin: 0; }
        
        .main { padding: 40px; margin-left: 260px; display: flex; flex-direction: column; align-items: center; }

        .profile-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            overflow: hidden;
            text-align: center;
        }

        .profile-header {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            padding: 40px 20px;
            color: white;
        }

        .avatar-circle {
            width: 80px;
            height: 80px;
            background: var(--primary);
            color: white;
            font-size: 2rem;
            font-weight: bold;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            border: 4px solid rgba(255, 255, 255, 0.2);
        }

        .profile-header h1 { margin: 0; font-size: 1.5rem; letter-spacing: -0.5px; }
        .role-badge {
            display: inline-block;
            margin-top: 10px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 700;
            background: rgba(255, 255, 255, 0.2);
        }

        .profile-body { padding: 30px; }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .info-row:last-child { border-bottom: none; }

        .info-label { font-weight: 600; color: var(--text-muted); font-size: 0.9rem; }
        .info-value { color: var(--text-main); font-weight: 500; }

        .btn-back {
            margin-top: 25px;
            text-decoration: none;
            color: var(--primary);
            font-weight: 600;
            font-size: 0.9rem;
            transition: opacity 0.2s;
        }
        .btn-back:hover { text-decoration: underline; }
    </style>
</head>
<body>

<?php include '../includes/sidebar.php'; ?>

<div class="main">
    <div class="profile-card">
        <div class="profile-header">
            <div class="avatar-circle">
                <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
            </div>
            <h1><?php echo htmlspecialchars($user['name']); ?></h1>
            <span class="role-badge"><?php echo $user['role']; ?></span>
        </div>

        <div class="profile-body">
            <div class="info-row">
                <span class="info-label">Email Address</span>
                <span class="info-value"><?php echo htmlspecialchars($user['email']); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">User ID</span>
                <span class="info-value">#<?php echo $user['id']; ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Account Created</span>
                <span class="info-value"><?php echo date('M d, Y', strtotime($user['created_at'])); ?></span>
            </div>
        </div>
    </div>

    <a href="users.php" class="btn-back">← Back to User Directory</a>
</div>

<?php include '../includes/footer.html'; ?>
