<?php
require_once '../config/auth.php';
require_role('user');
include '../config/db.php';

$user_id = (int) $_SESSION['user_id'];
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// --- Pagination Setup ---
$limit = 5;
$p_rec = isset($_GET['p_rec']) ? (int)$_GET['p_rec'] : 1;
$off_rec = ($p_rec - 1) * $limit;

$p_log = isset($_GET['p_log']) ? (int)$_GET['p_log'] : 1;
$off_log = ($p_log - 1) * $limit;

// --- BORROW RECORDS ---
$where_rec = "WHERE br.user_id = $user_id";
if ($search) $where_rec .= " AND e.name LIKE '%$search%'";

$total_rec_res = $conn->query("
    SELECT COUNT(*) as total 
    FROM borrow_records br 
    JOIN equipment e ON br.equipment_id = e.id 
    $where_rec
");
$total_rec = $total_rec_res->fetch_assoc()['total'];
$pages_rec = ceil($total_rec / $limit);

$records = $conn->query("
    SELECT br.*, e.name, e.image
    FROM borrow_records br
    JOIN equipment e ON br.equipment_id = e.id
    $where_rec
    ORDER BY br.borrow_date DESC
    LIMIT $limit OFFSET $off_rec
");

// --- HISTORY LOGS ---
$where_log = "WHERE h.user_id = $user_id";
if ($search) $where_log .= " AND e.name LIKE '%$search%'";

$total_log_res = $conn->query("
    SELECT COUNT(*) as total 
    FROM history_logs h 
    LEFT JOIN equipment e ON h.equipment_id = e.id 
    $where_log
");
$total_log = $total_log_res->fetch_assoc()['total'];
$pages_log = ceil($total_log / $limit);

$logs = $conn->query("
    SELECT h.*, e.name AS equipment_name
    FROM history_logs h
    LEFT JOIN equipment e ON h.equipment_id = e.id
    $where_log
    ORDER BY h.created_at DESC
    LIMIT $limit OFFSET $off_log
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>My History</title>
    <link rel="stylesheet" href="../assets/css/global.css">

    <style>
        body {
            font-family: Arial;
        }

        .section { margin-bottom: 40px; }

        .card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        /* ✅ FORCE ALL TABLE TEXT TO BLACK */
        table, th, td {
            color: #000 !important;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #f3f4f6;
            text-align: left;
            font-size: 14px;
        }

        th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
        }

        /* override ALL inner elements too */
        td span,
        td small,
        td strong {
            color: #000 !important;
        }

        /* status styles (keep but force text black override inside) */
        .status {
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: bold;
            color: #000 !important;
        }

        .borrowed { background: #dcfce7; }
        .pending { background: #fef3c7; }
        .returned { background: #e0e7ff; }

        img {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            object-fit: cover;
        }

        .header-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .search-box input {
            padding: 8px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: 250px;
        }

        .pagination {
            display: flex;
            gap: 5px;
            margin-top: 15px;
            justify-content: flex-end;
        }

        .pagination a {
            padding: 5px 12px;
            border: 1px solid #ddd;
            text-decoration: none;
            color: #000;
            border-radius: 6px;
            font-size: 13px;
            background: white;
        }

        .pagination a.active {
            background: #1f2937;
            color: white;
        }
    </style>
</head>

<body>

<?php include '../partials/alert.php'; ?>
<?php include '../includes/sidebar-user.php'; ?>

<div class="main">

    <div class="header-flex">
        <h1>My History</h1>

        <form method="GET" class="search-box">
            <input type="text" name="search" placeholder="Search..." value="<?= htmlspecialchars($search) ?>">
        </form>
    </div>

    <!-- BORROW RECORDS -->
    <div class="section">
        <div class="header-flex">
            <h2>Borrow Records</h2>
            <small>Total: <?= $total_rec ?></small>
        </div>

        <div class="card">
            <table>
                <tr>
                    <th>Item</th>
                    <th>Name</th>
                    <th>Qty</th>
                    <th>Borrowed</th>
                    <th>Return</th>
                    <th>Status</th>
                </tr>

                <?php while($row = $records->fetch_assoc()): ?>
                <tr>
                    <td><img src="../assets/images/<?= $row['image'] ?>"></td>
                    <td><strong><?= htmlspecialchars($row['name']) ?></strong></td>
                    <td><?= $row['quantity'] ?></td>
                    <td><?= date("M d, Y", strtotime($row['borrow_date'])) ?></td>
                    <td><?= date("M d, Y", strtotime($row['return_date'])) ?></td>
                    <td><span class="status"><?= ucfirst($row['status']) ?></span></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>

    <!-- LOGS -->
    <div class="section">
        <div class="header-flex">
            <h2>Activity Logs</h2>
            <small>Total: <?= $total_log ?></small>
        </div>

        <div class="card">
            <table>
                <tr>
                    <th>Item</th>
                    <th>Action</th>
                    <th>Details</th>
                    <th>Date</th>
                </tr>

                <?php while($row = $logs->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['equipment_name'] ?? 'N/A') ?></td>
                    <td><strong><?= ucfirst($row['action']) ?></strong></td>
                    <td><?= htmlspecialchars($row['details']) ?></td>
                    <td><?= date("M d, Y | h:i A", strtotime($row['created_at'])) ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>

</div>

</body>
</html>