<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php?error=Please login first");
    exit();
}

/* =====================
   STATS FROM DATABASE
===================== */
$totalEquipment = $conn->query("SELECT COUNT(*) AS total FROM equipment")->fetch_assoc()['total'];

$totalUsers = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];

$borrowed = $conn->query("SELECT COUNT(*) AS total FROM borrow_records WHERE status = 'borrowed'")->fetch_assoc()['total'];

$available = $conn->query("SELECT COUNT(*) AS total FROM equipment WHERE quantity > 0")->fetch_assoc()['total'];

/* =====================
   RECENT ACTIVITY
===================== */
$activity = $conn->query("
SELECT br.*, u.name AS user_name, e.name AS equipment_name
FROM borrow_records br
JOIN users u ON br.user_id = u.id
JOIN equipment e ON br.equipment_id = e.id
ORDER BY br.id DESC
LIMIT 5
");

/* =====================
   CHART DATA
===================== */
$chart = $conn->query("
SELECT e.name, COUNT(br.id) AS total
FROM borrow_records br
JOIN equipment e ON br.equipment_id = e.id
GROUP BY br.equipment_id
ORDER BY total DESC
LIMIT 6
");

$labels = [];
$values = [];

while ($row = $chart->fetch_assoc()) {
    $labels[] = $row['name'];
    $values[] = $row['total'];
}
?>



    <?php
include '../includes/header.html';
?>



    <style>
        body {
            margin: 0;
            font-family: Arial;
            background: #f4f6f8;
        }

        .sidebar {
            width: 200px;
            height: 100vh;
            background: #1f2937;
            position: fixed;
            padding-top: 20px;
        }

        .sidebar h2 {
            color: white;
            text-align: center;
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 12px;
            text-decoration: none;
        }

        .sidebar a:hover {
            background: #374151;
        }

        .main {
            margin-left: 220px;
            padding: 20px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .datetime {
            color: gray;
            font-size: 14px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-top: 15px;
        }

        .card {
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .icon {
            font-size: 25px;
        }

        .chart-box {
            background: white;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
            height: 320px;
        }

        .activity {
            margin-top: 20px;
        }

        .activity-item {
            background: white;
            padding: 12px;
            margin-bottom: 8px;
            border-left: 4px solid #10b981;
            border-radius: 6px;
        }

        canvas {
            max-height: 250px !important;
        }
    </style>


<!-- SIDEBAR -->
<?php
include '../includes/sidebar.php';
?>


<!-- MAIN -->
<div class="main">

    <!-- TOP -->
    <div class="topbar">
        <h1>Dashboard</h1>
        <div class="datetime" id="datetime"></div>
    </div>

    <!-- STATS -->
    <div class="grid">

        <div class="card">
            <div class="icon">📦</div>
            <p>Total Equipment</p>
            <h2><?php echo $totalEquipment; ?></h2>
        </div>

        <div class="card">
            <div class="icon">📥</div>
            <p>Borrowed</p>
            <h2><?php echo $borrowed; ?></h2>
        </div>

        <div class="card">
            <div class="icon">✅</div>
            <p>Available</p>
            <h2><?php echo $available; ?></h2>
        </div>

        <div class="card">
            <div class="icon">👥</div>
            <p>Users</p>
            <h2><?php echo $totalUsers; ?></h2>
        </div>

    </div>

    <!-- CHART -->
    <div class="chart-box">
        <h3>📊 Equipment Usage</h3>
        <canvas id="usageChart"></canvas>
    </div>

    <!-- ACTIVITY -->
    <h3>Recent Activity</h3>
    <div class="activity">

        <?php while($row = $activity->fetch_assoc()): ?>
            <div class="activity-item">
                🟢 <?php echo $row['user_name']; ?>
                borrowed <?php echo $row['equipment_name']; ?>
            </div>
        <?php endwhile; ?>

    </div>

</div>

<!-- CHART SCRIPT -->
<script>
const ctx = document.getElementById('usageChart');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($labels); ?>,
        datasets: [{
            label: 'Borrow Count',
            data: <?php echo json_encode($values); ?>,
            backgroundColor: '#3b82f6'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

/* LIVE TIME */
function updateTime() {
    const now = new Date();
    document.getElementById("datetime").innerHTML =
        now.toLocaleDateString() + " | " + now.toLocaleTimeString();
}
setInterval(updateTime, 1000);
updateTime();
</script>

<?php
include '../includes/footer.html';
?>