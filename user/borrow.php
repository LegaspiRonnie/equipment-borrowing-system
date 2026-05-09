<?php
require_once '../config/auth.php';
require_role('user');
include '../config/db.php';

$user_id = (int) $_SESSION['user_id'];

// Search Logic
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$equip_query = "SELECT * FROM equipment WHERE quantity > 0";
if (!empty($search)) {
    $equip_query .= " AND (name LIKE '%$search%' OR category LIKE '%$search%')";
}
$equip_query .= " ORDER BY name ASC";
$equipment = $conn->query($equip_query);

// Pending Requests
$requests = $conn->query("
    SELECT br.*, e.name, e.image 
    FROM borrow_requests br
    LEFT JOIN equipment e ON br.equipment_id = e.id
    WHERE br.user_id = $user_id AND br.status = 'pending'
    ORDER BY br.id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Borrow System</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <style>
        :root { --primary: #1e293b; --accent: #3b82f6; --bg: #f8fafc; }
        body { margin: 0; font-family: sans-serif; background: var(--bg); }
        .main { margin-left: 260px; padding: 25px; }

        /* ALERT BANNER STYLE */
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-weight: bold;
            text-align: center;
            animation: slideDown 0.5s ease-out;
        }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        @keyframes slideDown { from { transform: translateY(-20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        /* Layout */
        .layout { display: grid; grid-template-columns: 1fr 380px; gap: 20px; }
        .panel { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        
        .equip-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 15px; margin-top: 15px; }
        .equip-card { border: 2px solid #f1f5f9; padding: 10px; border-radius: 10px; cursor: pointer; text-align: center; }
        .equip-card.selected { border-color: var(--accent); background: #eff6ff; }
        .equip-card img { width: 100%; height: 100px; object-fit: cover; border-radius: 5px; }

        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-size: 13px; margin-bottom: 5px; color: #64748b; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; }
        
        .btn-submit { width: 100%; padding: 12px; background: var(--primary); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; }
        .btn-submit:disabled { background: #ccc; }

        .req-card { display: flex; align-items: center; gap: 10px; padding: 10px; border-bottom: 1px solid #eee; }
        .req-card img { width: 40px; height: 40px; border-radius: 4px; }
    </style>
</head>
<body>

<?php include '../includes/sidebar-user.php'; ?>

<div class="main">

    <?php if (isset($_SESSION['msg'])): ?>
        <div class="alert alert-<?= $_SESSION['msg_type']; ?>" id="alert-box">
            <?= $_SESSION['msg']; ?>
        </div>
        <?php 
            unset($_SESSION['msg']); 
            unset($_SESSION['msg_type']); 
        ?>
        <script>
            // Automatically hide the alert after 4 seconds
            setTimeout(() => {
                const box = document.getElementById('alert-box');
                if(box) box.style.display = 'none';
            }, 4000);
        </script>
    <?php endif; ?>

    <div class="layout">
        
        <div class="panel">
            <div style="display:flex; justify-content: space-between; align-items: center;">
                <h3>Available Equipment</h3>
                <form method="GET">
                    <input type="text" name="search" placeholder="Search..." value="<?= htmlspecialchars($search) ?>" style="padding:8px; border-radius:5px; border:1px solid #ddd;">
                </form>
            </div>

            <div class="equip-grid">
                <?php while($row = $equipment->fetch_assoc()): ?>
                    <div class="equip-card" onclick="selectItem(this)" 
                         data-id="<?= $row['id'] ?>" data-name="<?= htmlspecialchars($row['name']) ?>" data-qty="<?= $row['quantity'] ?>">
                        <img src="../assets/images/<?= $row['image'] ?>" onerror="this.src='https://via.placeholder.com/150'">
                        <div style="font-weight:bold; margin-top:5px;"><?= htmlspecialchars($row['name']) ?></div>
                        <small>Stock: <?= $row['quantity'] ?></small>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

        <div>
            <div class="panel">
                <h3>Borrow Form</h3>
                <div id="preview" style="background:#f8fafc; padding:10px; border-radius:8px; margin-bottom:15px; border:1px dashed #cbd5e1; text-align:center;">
                    Select an item
                </div>

                <form action="../process/borrow_request_process.php" method="POST" onsubmit="return confirmSubmit()">
                    <input type="hidden" name="equipment_id" id="equipment_id">
                    
                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="number" name="quantity" id="qty" min="1" required>
                    </div>

                    <div class="form-group">
                        <label>Purpose</label>
                        <input type="text" name="purpose" required>
                    </div>

                    <div class="form-group">
                        <label>Return Date</label>
                        <input type="date" name="return_date" min="<?= date('Y-m-d') ?>" required>
                    </div>

                    <button type="submit" id="btn" class="btn-submit" disabled>Submit Request</button>
                </form>
            </div>

            <div class="panel" style="margin-top:20px;">
                <h3>Pending Requests</h3>
                <?php while($r = $requests->fetch_assoc()): ?>
                    <div class="req-card">
                        <img src="../assets/images/<?= $r['image'] ?>">
                        <div>
                            <div style="font-size:14px; font-weight:bold;"><?= htmlspecialchars($r['name']) ?></div>
                            <small style="color:orange;">Pending Approval</small>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

    </div>
</div>

<script>
let itemName = "";

function selectItem(card) {
    document.querySelectorAll('.equip-card').forEach(c => c.classList.remove('selected'));
    card.classList.add('selected');

    const {id, name, qty} = card.dataset;
    itemName = name;

    document.getElementById('equipment_id').value = id;
    document.getElementById('qty').max = qty;
    document.getElementById('btn').disabled = false;
    document.getElementById('preview').innerHTML = `<strong>${name}</strong><br><small>Stock: ${qty}</small>`;
}

function confirmSubmit() {
    return confirm("Send borrow request for " + itemName + "?");
}
</script>

<?php include '../includes/footer.html'; ?>
