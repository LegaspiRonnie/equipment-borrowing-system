<?php
require_once '../config/auth.php';
require_role('admin');
include '../config/db.php';

$id = intval($_GET['id']);
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
    <title>Edit User | <?php echo htmlspecialchars($user['name']); ?></title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --bg-gray: #f1f5f9;
            --text-main: #1e293b;
            --border: #e2e8f0;
        }

        body { font-family: 'Inter', sans-serif; background: var(--bg-gray); margin: 0; }
        
        .main { padding: 40px; margin-left: 260px; display: flex; flex-direction: column; align-items: center; }

        .edit-card {
            background: white;
            padding: 32px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }

        .edit-header {
            border-bottom: 1px solid var(--border);
            margin-bottom: 25px;
            padding-bottom: 15px;
        }

        .edit-header h1 { margin: 0; font-size: 1.5rem; color: var(--text-main); }
        .edit-header p { margin: 5px 0 0; color: #64748b; font-size: 0.9rem; }

        .form-group { margin-bottom: 20px; }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 0.85rem;
            color: #475569;
            text-transform: uppercase;
        }

        input[type="text"],
        input[type="email"],
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 1rem;
            box-sizing: border-box;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        input:focus, select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .btn-update {
            width: 100%;
            background: var(--primary);
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-update:hover { background: var(--primary-dark); }

        .alert-info {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 12px;
            margin-bottom: 20px;
            font-size: 0.85rem;
            color: #1e40af;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<?php include '../includes/sidebar.php'; ?>

<div class="main">
    <div class="edit-card">
        <div class="edit-header">
            <h1>Edit User</h1>
            <p>Updating profile for User ID: #<?php echo $user['id']; ?></p>
        </div>

        <div class="alert-info">
            Changing the email address will update the user's login credentials.
        </div>

        <form action="../process/user_edit_process.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">

            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>

            <div class="form-group">
                <label>System Role</label>
                <select name="role">
                    <option value="user" <?php echo ($user['role'] == 'user') ? 'selected' : ''; ?>>Standard User</option>
                    <option value="admin" <?php echo ($user['role'] == 'admin') ? 'selected' : ''; ?>>Administrator</option>
                </select>
            </div>

            <button type="submit" class="btn-update">Update Settings</button>
        </form>
    </div>

    <a href="users.php" style="margin-top: 20px; text-decoration: none; color: #64748b; font-size: 0.9rem;">
        ← Cancel and Return
    </a>
</div>

<?php include '../includes/footer.html'; ?>
