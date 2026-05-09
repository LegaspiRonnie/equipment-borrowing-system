<?php
require_once '../config/auth.php';
require_role('admin');
if (!isset($_GET['id'])) {
    header("Location: equipment.php");
    exit();
}
$id = intval($_GET['id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Delete | Equipment Management</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <style>
        :root {
            --danger: #ef4444;
            --danger-hover: #dc2626;
            --gray-light: #f8fafc;
            --text-main: #334155;
        }

        body { font-family: 'Inter', sans-serif; background-color: #f1f5f9; }

        .main { padding: 40px; margin-left: 260px; display: flex; justify-content: center; }

        .delete-card {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            max-width: 450px;
            text-align: center;
        }

        .icon-box {
            width: 70px;
            height: 70px;
            background: #fef2f2;
            color: var(--danger);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2rem;
            font-weight: bold;
        }

        h1 { font-size: 1.5rem; color: #1e293b; margin-bottom: 10px; }
        p { color: #64748b; line-height: 1.5; margin-bottom: 30px; }

        .button-group { display: flex; gap: 12px; justify-content: center; }

        .btn {
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            font-size: 0.95rem;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }

        .btn-delete {
            background-color: var(--danger);
            color: white;
        }

        .btn-delete:hover { background-color: var(--danger-hover); box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3); }

        .btn-cancel {
            background-color: #e2e8f0;
            color: #475569;
        }

        .btn-cancel:hover { background-color: #cbd5e1; }
    </style>
</head>
<body>

<?php include '../includes/sidebar.php'; ?>

<div class="main">
    <div class="delete-card">
        <div class="icon-box">!</div>
        
        <h1>Confirm Deletion</h1>
        <p>Are you sure you want to delete this item? This action is permanent and cannot be undone.</p>

        <div class="button-group">
            <a href="equipment.php" class="btn btn-cancel">No, Keep it</a>

            <a href="../process/equipment_delete_process.php?id=<?php echo $id; ?>" class="btn btn-delete">Yes, Delete Item</a>
        </div>
    </div>
</div>

<?php include '../includes/footer.html'; ?>
