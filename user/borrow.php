<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: ../index.php?error=Please login first");
    exit();
}

$user_id = (int) $_SESSION['user_id'];

// Equipment
$equipment = $conn->query("SELECT * FROM equipment WHERE quantity > 0");

// Requests (history_logs action = request)
$requests = $conn->query("
    SELECT hl.*, e.name, e.image, e.category
    FROM history_logs hl
    LEFT JOIN equipment e ON hl.equipment_id = e.id
    WHERE hl.user_id = $user_id
    AND hl.action = 'request'
    ORDER BY hl.id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Borrow System</title>
    
    <link rel="stylesheet" href="../assets/css/global.css">

<style>
body {
    margin:0;
    font-family:Arial;
    background:#f5f5f5;
}



.sidebar a:hover {
    color:white;
}

/* MAIN */
.main {
    margin-left:250px;
    padding:20px;
}

/* 3 PANEL GRID */
.layout {
    display:grid;
    grid-template-columns: 2fr 1fr;
    grid-template-rows: auto auto;
    gap:15px;
}

/* 1 EQUIPMENT */
.equipment {
    grid-column:1/2;
    grid-row:1/3;
    background:white;
    padding:15px;
    border-radius:10px;
    height:85vh;
    overflow-y:auto;
}

.equip-grid {
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(180px,1fr));
    gap:10px;
}

.equip-card {
    background:#fff;
    border:1px solid #ddd;
    padding:10px;
    border-radius:10px;
    cursor:pointer;
}

.equip-card img {
    width:100%;
    height:100px;
    object-fit:cover;
}

/* 2 FORM */
.form-box {
    grid-column:2/3;
    grid-row:1/2;
    background:white;
    padding:15px;
    border-radius:10px;
    height:45vh;
}

/* 3 REQUESTS */
.request-box {
    grid-column:2/3;
    grid-row:2/3;
    background:white;
    padding:15px;
    border-radius:10px;
    height:38vh;
    overflow-y:auto;
}

.req-card {
    display:flex;
    gap:10px;
    padding:8px;
    border-bottom:1px solid #eee;
}

.req-card img {
    width:40px;
    height:40px;
    object-fit:cover;
    border-radius:5px;
}
</style>
</head>

<body>

<!-- SIDEBAR -->
<?php
include '../includes/sidebar-user.php';
?>


<!-- MAIN -->
<div class="main">

<div class="layout">

<!-- 1 EQUIPMENT -->
<div class="equipment">

<h3>Equipment</h3>

<div class="equip-grid">

<?php if ($equipment && $equipment->num_rows > 0): ?>

<?php while($row = $equipment->fetch_assoc()): ?>

<div class="equip-card"
     onclick="selectItem(this)"
     data-id="<?= $row['id']; ?>"
     data-name="<?= htmlspecialchars($row['name']); ?>"
     data-category="<?= htmlspecialchars($row['category']); ?>"
     data-qty="<?= $row['quantity']; ?>">

    <img src="../assets/images/<?= htmlspecialchars($row['image']); ?>"
         onerror="this.src='https://via.placeholder.com/150'">

    <strong><?= htmlspecialchars($row['name']); ?></strong><br>
    <small><?= htmlspecialchars($row['category']); ?></small><br>
    <small>Stock: <?= (int)$row['quantity']; ?></small>

</div>

<?php endwhile; ?>

<?php else: ?>
<p>No equipment available</p>
<?php endif; ?>

</div>

</div>

<!-- 2 FORM -->
<div class="form-box">

<h3>Borrow Form</h3>

<div id="preview">Select item</div>

<form action="../process/borrow_request_process.php" method="POST">

<input type="hidden" name="equipment_id" id="equipment_id">

<input type="number" name="quantity" id="qty" placeholder="Quantity">
<br><br>

<input type="text" name="purpose" placeholder="Purpose">
<br><br>

<input type="date" name="return_date">
<br><br>

<button type="submit" id="btn" disabled>Submit</button>

</form>

</div>

<!-- 3 REQUESTS -->
<div class="request-box">

<h3>Requested Borrow</h3>

<?php if ($requests && $requests->num_rows > 0): ?>

<?php while($r = $requests->fetch_assoc()): ?>

<div class="req-card">

    <img src="../assets/images/<?= htmlspecialchars($r['image']); ?>"
         onerror="this.src='https://via.placeholder.com/40'">

    <div>
        <strong><?= htmlspecialchars($r['name']); ?></strong><br>
        <small><?= htmlspecialchars($r['category']); ?></small><br>
        <small style="color:orange;">Pending Request</small>
    </div>

</div>

<?php endwhile; ?>

<?php else: ?>

<p>No requests yet</p>

<?php endif; ?>

</div>

</div>

</div>

<script>
function selectItem(card){
    document.querySelectorAll('.equip-card').forEach(c=>c.style.border='1px solid #ddd');
    card.style.border='2px solid blue';

    const {id,name,category,qty} = card.dataset;

    document.getElementById('equipment_id').value = id;
    document.getElementById('btn').disabled = false;

    document.getElementById('qty').max = qty;

    document.getElementById('preview').innerHTML =
        `<b>${name}</b><br>${category}<br>Stock: ${qty}`;
}
</script>

</body>
</html>