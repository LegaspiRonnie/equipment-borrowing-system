<?php
require_once '../config/auth.php';
require_role('admin');
include '../config/db.php';

// Quick Security Note: Always cast ID to int to prevent SQL Injection
$id = (int)$_GET['id']; 
$result = $conn->query("SELECT * FROM equipment WHERE id=$id");
$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Equipment | <?php echo htmlspecialchars($data['name']); ?></title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <style>
        :root {
            --primary: #2563eb;
            --bg-gray: #f8fafc;
            --text-dark: #1e293b;
            --border: #e2e8f0;
        }

        body { font-family: 'Inter', system-ui, sans-serif; background: var(--bg-gray); margin: 0; }
        
        .main { padding: 40px; margin-left: 260px; /* Adjust based on sidebar width */ }

        .equipment-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            display: flex;
            gap: 30px;
            padding: 30px;
            max-width: 800px;
        }

        .image-container img {
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid var(--border);
        }

        .details h1 { margin-top: 0; color: var(--text-dark); font-size: 1.8rem; }

        .info-grid {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 12px 24px;
            margin-top: 20px;
        }

        .label { font-weight: 600; color: #64748b; text-transform: uppercase; font-size: 0.75rem; }
        .value { color: var(--text-dark); font-size: 1rem; }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            background: #dcfce7;
            color: #166534;
        }

        .btn-back {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            color: var(--primary);
            font-weight: 500;
        }
    </style>
</head>
<body>
<?php include '../partials/alert.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="main">
    <a href="equipment.php" class="btn-back">← Back to Inventory</a>

    <div class="equipment-card">
        <div class="image-container">
            <img src="../assets/images/<?php echo $data['image']; ?>" width="220" height="220" alt="Equipment Image">
        </div>

        <div class="details">
            <h1><?php echo htmlspecialchars($data['name']); ?></h1>
            <span class="status-badge"><?php echo $data['status']; ?></span>

            <div class="info-grid">
                <div class="label">Category</div>
                <div class="value"><?php echo htmlspecialchars($data['category']); ?></div>

                <div class="label">Quantity</div>
                <div class="value"><?php echo $data['quantity']; ?> Units</div>

                <div class="label">Date Added</div>
                <div class="value"><?php echo date('M d, Y', strtotime($data['created_at'])); ?></div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.html'; ?>
