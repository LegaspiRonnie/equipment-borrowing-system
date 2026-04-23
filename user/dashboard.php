<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: ../index.php?error=Please login first");
    exit();
}

// get equipment
$equipment = $conn->query("SELECT * FROM equipment");
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../assets/css/global.css">

    <style>
        .topbar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: 0.3s;
            text-align: center;
        }

        .card:hover {
            transform: translateY(-8px);
        }

        .card img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 10px;
        }

        .category {
            font-size: 12px;
            color: gray;
        }

        .qty {
            margin: 10px 0;
            font-weight: bold;
        }

        .status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            display: inline-block;
            margin-bottom: 10px;
        }

        .available {
            background: #d4edda;
            color: #155724;
        }

        .notavailable {
            background: #f8d7da;
            color: #721c24;
        }

        .btn {
            padding: 10px 15px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
        }

        .btn-borrow {
            background: #4CAF50;
            color: white;
        }

        .btn-disabled {
            background: gray;
            color: white;
            cursor: not-allowed;
        }

        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 60px 20px;
            color: #6b7280;
        }

        .empty-state h2 {
            margin-bottom: 8px;
        }
    </style>
</head>

<body>

<?php
include '../includes/sidebar-user.php';
?>

<div class="main">

<div class="topbar">
    <h1>Available Equipment</h1>
    <div id="datetime"></div>
</div>

<div class="grid">

<?php if ($equipment && $equipment->num_rows > 0): ?>

    <?php while($row = $equipment->fetch_assoc()): ?>

        <div class="card">

            <img src="../assets/images/<?php echo htmlspecialchars($row['image']); ?>" 
                 onerror="this.src='https://via.placeholder.com/120'">

            <h3><?php echo htmlspecialchars($row['name']); ?></h3>

            <div class="category"><?php echo htmlspecialchars($row['category']); ?></div>

            <div class="qty">Qty: <?php echo (int)$row['quantity']; ?></div>

            <?php if ($row['quantity'] > 0): ?>
                <div class="status available">Available</div>
                <br>
                <a href="borrow.php">
                    <button class="btn btn-borrow">Borrow</button>
                </a>
            <?php else: ?>
                <div class="status notavailable">Unavailable</div>
                <br>
                <button class="btn btn-disabled" disabled>Out of Stock</button>
            <?php endif; ?>

        </div>

    <?php endwhile; ?>

<?php else: ?>

    <!-- EMPTY STATE -->
    <div class="empty-state">
        <h2>No Equipment Available</h2>
        <p>Please wait for admin to add equipment.</p>
    </div>

<?php endif; ?>

</div>

</div>

<!-- LIVE TIME -->
<script>
function updateTime() {
    const now = new Date();
    document.getElementById("datetime").innerHTML =
        now.toLocaleDateString() + " | " + now.toLocaleTimeString();
}
setInterval(updateTime, 1000);
updateTime();
</script>

</body>
</html>