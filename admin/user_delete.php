<?php
require_once '../config/auth.php';
require_role('admin');
if (!isset($_GET['id'])) {
    header("Location: users.php");
    exit();
}
$id = intval($_GET['id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Delete | User Management</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <style>
        :root {
            --danger: #ef4444;
            --danger-hover: #dc2626;
            --bg-gray: #f1f5f9;
        }

        body { font-family: 'Inter', sans-serif; background-color: var(--bg-gray); margin: 0; }

        .main { 
            padding: 40px; 
            margin-left: 260px; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 80vh; 
        }

        .delete-card {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            text-align: center;
        }

        h1 { font-size: 1.5rem; color: #1e293b; margin-bottom: 10px; }
        p { color: #64748b; margin-bottom: 30px; line-height: 1.5; }

        .btn-group { display: flex; gap: 12px; justify-content: center; }

        .btn {
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
            display: inline-block;
        }

        .btn-confirm { background-color: var(--danger); color: white; }
        .btn-confirm:hover { background-color: var(--danger-hover); }
        .btn-cancel { background-color: #e2e8f0; color: #475569; }
    </style>
</head>
<body>
<?php include '../partials/alert.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="main">
    <div class="delete-card">
        <h1>Are you sure?</h1>
        <p>This will permanently delete the user account and redirect you back to the directory.</p>

        <div class="btn-group">
            <a href="users.php" class="btn btn-cancel">No, Go Back</a>
            <a href="../process/user_delete_process.php?id=<?php echo $id; ?>" class="btn btn-confirm">Yes, Delete User</a>
        </div>
    </div>
</div>

<?php include '../includes/footer.html'; ?>
