<?php
require_once '../config/auth.php';
require_role('admin');
include '../config/db.php';
$id = (int)$_GET['id'];
$result = $conn->query("SELECT * FROM equipment WHERE id=$id");
$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Equipment | <?php echo htmlspecialchars($data['name']); ?></title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --bg-main: #f1f5f9;
            --text-muted: #64748b;
        }

        body { font-family: 'Inter', sans-serif; background-color: var(--bg-main); }
        
        .main { padding: 40px; margin-left: 260px; }

        .form-container {
            background: #ffffff;
            padding: 32px;
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            max-width: 600px;
        }

        .form-header { margin-bottom: 24px; border-bottom: 1px solid #e2e8f0; padding-bottom: 16px; }
        .form-header h1 { margin: 0; font-size: 1.5rem; color: #1e293b; }

        .form-group { margin-bottom: 20px; }
        
        label { 
            display: block; 
            margin-bottom: 8px; 
            font-weight: 600; 
            font-size: 0.875rem; 
            color: #475569; 
        }

        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.2s;
            box-sizing: border-box; /* Ensures padding doesn't break width */
        }

        input:focus, select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .current-image-preview {
            display: flex;
            align-items: center;
            gap: 15px;
            background: #f8fafc;
            padding: 10px;
            border-radius: 8px;
            margin-top: 8px;
        }

        .btn-update {
            background-color: var(--primary);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            font-size: 1rem;
            transition: background 0.2s;
        }

        .btn-update:hover { background-color: var(--primary-hover); }
    </style>
</head>
<body>

<?php include '../includes/sidebar.php'; ?>

<div class="main">
    <div class="form-container">
        <div class="form-header">
            <h1>Edit Equipment</h1>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Modify the details for asset ID: #<?php echo $data['id']; ?></p>
        </div>

        <form action="../process/equipment_edit_process.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $data['id']; ?>">

            <div class="form-group">
                <label>Equipment Name</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($data['name']); ?>" required>
            </div>

            <div class="form-row" style="display: flex; gap: 15px;">
                <div class="form-group" style="flex: 2;">
                    <label>Category</label>
                    <input type="text" name="category" value="<?php echo htmlspecialchars($data['category']); ?>" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Quantity</label>
                    <input type="number" name="quantity" value="<?php echo $data['quantity']; ?>" min="0" required>
                </div>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="available" <?php echo ($data['status'] == 'available') ? 'selected' : ''; ?>>Available</option>
                    <option value="borrowed" <?php echo ($data['status'] == 'borrowed') ? 'selected' : ''; ?>>Borrowed</option>
                    <option value="maintenance" <?php echo ($data['status'] == 'maintenance') ? 'selected' : ''; ?>>Maintenance</option>
                </select>
            </div>

            <div class="form-group">
                <label>Equipment Image</label>
                <input type="file" name="image" accept="image/*">
                <div class="current-image-preview">
                    <img src="../assets/images/<?php echo $data['image']; ?>" width="50" height="50" style="border-radius: 4px; object-fit: cover;">
                    <span style="font-size: 0.8rem; color: var(--text-muted);">Current: <?php echo $data['image']; ?></span>
                </div>
            </div>

            <button type="submit" class="btn-update">Save Changes</button>
        </form>
    </div>
</div>

<?php include '../includes/footer.html'; ?>
