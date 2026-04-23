<?php
include '../config/db.php';

/* TOTAL COUNTS */
$totalBorrowed = $conn->query("SELECT COUNT(*) AS total FROM borrow_records")->fetch_assoc()['total'];

$totalReturned = $conn->query("SELECT COUNT(*) AS total FROM borrow_records WHERE status = 'returned'")->fetch_assoc()['total'];

$totalPending = $conn->query("SELECT COUNT(*) AS total FROM borrow_requests WHERE status = 'pending'")->fetch_assoc()['total'];

/* MOST BORROWED */
$most = $conn->query("
SELECT e.name, COUNT(*) AS total
FROM borrow_records br
JOIN equipment e ON br.equipment_id = e.id
GROUP BY br.equipment_id
ORDER BY total DESC
LIMIT 1
")->fetch_assoc();

/* LEAST BORROWED */
$least = $conn->query("
SELECT e.name, COUNT(*) AS total
FROM borrow_records br
JOIN equipment e ON br.equipment_id = e.id
GROUP BY br.equipment_id
ORDER BY total ASC
LIMIT 1
")->fetch_assoc();

/* CHART DATA */
$chartData = $conn->query("
SELECT e.name, COUNT(br.id) AS total
FROM borrow_records br
JOIN equipment e ON br.equipment_id = e.id
GROUP BY br.equipment_id
ORDER BY total DESC
LIMIT 10
");

$labels = [];
$values = [];

while ($row = $chartData->fetch_assoc()) {
    $labels[] = $row['name'];
    $values[] = $row['total'];
}
?>

<?php
include '../includes/header.html';
?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="stylesheet" href="../assets/css/global.css">

    <style>
        body {
            margin: 0;
            overflow: hidden;
            font-family: Arial;
        }

        .sidebar {
            width: 200px;
            height: 100vh;
            background: #2c3e50;
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
            background: #34495e;
        }

        .main {
            margin-left: 200px;
            height: 100vh;
            padding: 15px;
            box-sizing: border-box;
            overflow: hidden;
        }

        h1 {
            margin: 5px 0;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .card {
            background: white;
            padding: 10px;
            border-radius: 6px;
            box-shadow: 0px 2px 5px rgba(0,0,0,0.1);
            text-align: center;
        }

        .chart-box {
            background: white;
            padding: 10px;
            margin-top: 10px;
            border-radius: 6px;
            height: 320px; /* SHORTER CHART */
        }

        canvas {
            max-height: 260px !important;
        }
    </style>


<!-- SIDEBAR -->
<?php
include '../includes/sidebar.php';
?>


<!-- MAIN -->
<div class="main">

<h1>Reports</h1>

<!-- TOP STATS -->
<div class="grid">

    <div class="card">
        <h3>Borrowed</h3>
        <h2><?php echo $totalBorrowed; ?></h2>
    </div>

    <div class="card">
        <h3>Returned</h3>
        <h2><?php echo $totalReturned; ?></h2>
    </div>

    <div class="card">
        <h3>Pending</h3>
        <h2><?php echo $totalPending; ?></h2>
    </div>

</div>

<!-- MOST / LEAST -->
<div class="grid" style="margin-top:10px;">

    <div class="card">
        <h3>Most Borrowed</h3>
        <h2><?php echo $most['name'] ?? 'N/A'; ?></h2>
    </div>

    <div class="card">
        <h3>Least Borrowed</h3>
        <h2><?php echo $least['name'] ?? 'N/A'; ?></h2>
    </div>

</div>

<!-- CHART -->
<div class="chart-box">
    <h3>📊 Borrow Chart</h3>
    <canvas id="borrowChart"></canvas>
</div>

</div>

<!-- CHART SCRIPT -->
<script>
const ctx = document.getElementById('borrowChart');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($labels); ?>,
        datasets: [{
            label: 'Borrow Count',
            data: <?php echo json_encode($values); ?>,
            backgroundColor: 'rgba(52, 152, 219, 0.7)',
            borderWidth: 1
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
</script>

<?php
include '../includes/footer.html';
?>