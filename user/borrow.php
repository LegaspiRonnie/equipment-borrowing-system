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

body {
    margin: 0;
    font-family: sans-serif;
    background: var(--bg);
}

/* ALERT */
.alert {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 8px;
    font-weight: bold;
    text-align: center;
    animation: slideDown 0.5s ease-out;
}
.alert-success { background: #dcfce7; color: #166534; }
.alert-error { background: #fee2e2; color: #991b1b; }

@keyframes slideDown {
    from { transform: translateY(-20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

/* Layout */
.layout {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 20px;
}

.panel,
.panel * {
    color: #000 !important;
}

/* Equipment cards */
.equip-card,
.equip-card * {
    color: #000 !important;
}

/* Request cards */
.req-card,
.req-card * {
    color: #000 !important;
}

/* Preview box */
#preview {
    color: #000 !important;
}

/* Small overrides for muted text */
small {
    color: #000 !important;
}

/* Equipment */
.equip-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 15px;
    margin-top: 15px;
}

.equip-card {
    border: 2px solid #f1f5f9;
    padding: 10px;
    border-radius: 10px;
    cursor: pointer;
    text-align: center;
}

.equip-card.selected {
    border-color: var(--accent);
    background: #eff6ff;
}

.equip-card img {
    width: 100%;
    height: 100px;
    object-fit: cover;
    border-radius: 5px;
}

/* FORM */
.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    font-size: 13px;
    margin-bottom: 5px;
}

/* Inputs */
.form-group input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    box-sizing: border-box;
}

/* BUTTON */
.btn-submit {
    width: 100%;
    padding: 12px;
    background: var(--primary);
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: bold;
}

.btn-submit:disabled {
    background: #ccc;
}

/* REQUEST */
.req-card {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px;
    border-bottom: 1px solid #eee;
}

.req-card img {
    width: 40px;
    height: 40px;
    border-radius: 4px;
}

/* ============================= */
/* ✅ FORCE ALL FORM TEXT BLACK */
/* ============================= */

.panel form,
.panel form * {
    color: #000 !important;
}

.form-group label {
    color: #000 !important;
}

.form-group input {
    color: #000 !important;
}

.form-group input::placeholder {
    color: #555 !important;
}

/* Fix browser autofill color */
input:-webkit-autofill {
    -webkit-text-fill-color: #000 !important;
}
</style>
</head>

<body>

<?php include '../partials/alert.php'; ?>
<?php include '../includes/sidebar-user.php'; ?>

<div class="main">

<div class="layout">

    <div class="panel">
        <h3>Available Equipment</h3>

        <div class="equip-grid">
            <?php while($row = $equipment->fetch_assoc()): ?>
                <div class="equip-card">
                    <img src="../assets/images/<?= $row['image'] ?>">
                    <div style="font-weight:bold;"><?= htmlspecialchars($row['name']) ?></div>
                    <small>Stock: <?= $row['quantity'] ?></small>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <div class="panel">
        <h3>Borrow Form</h3>

        <form action="../process/borrow_request_process.php" method="POST">

            <div class="form-group">
                <label>Quantity</label>
                <input type="number" name="quantity" placeholder="Enter quantity" required>
            </div>

            <div class="form-group">
                <label>Purpose</label>
                <input type="text" name="purpose" placeholder="Enter purpose" required>
            </div>

            <div class="form-group">
                <label>Return Date</label>
                <input type="date" name="return_date" required>
            </div>

            <button type="submit" class="btn-submit">Submit Request</button>
        </form>
    </div>

</div>

</div>

</body>
</html>