<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = (int) $_SESSION['user_id'];
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// --- Pagination Setup ---
$limit = 5; // Rows per table

// Page for Records
$p_rec = isset($_GET['p_rec']) ? (int)$_GET['p_rec'] : 1;
$off_rec = ($p_rec - 1) * $limit;

// Page for Logs
$p_log = isset($_GET['p_log']) ? (int)$_GET['p_log'] : 1;
$off_log = ($p_log - 1) * $limit;

// --- Queries ---

// 1. BORROW RECORDS
$where_rec = "WHERE br.user_id = $user_id";
if ($search) $where_rec .= " AND e.name LIKE '%$search%'";

$total_rec_res = $conn->query("SELECT COUNT(*) as total FROM borrow_records br JOIN equipment e ON br.equipment_id = e.id $where_rec");
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

// 2. HISTORY LOGS
$where_log = "WHERE h.user_id = $user_id";
if ($search) $where_log .= " AND e.name LIKE '%$search%'";

$total_log_res = $conn->query("SELECT COUNT(*) as total FROM history_logs h LEFT JOIN equipment e ON h.equipment_id = e.id $where_log");
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
        .section { margin-bottom: 40px; }
        .card { background: white; padding: 20px; border-radius: 12px; border: 1px solid #e5e7eb; box-shadow: 0 2px 5px rgba(0,0,0,0.02); }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; border-bottom: 1px solid #f3f4f6; text-align: left; font-size: 14px; }
        th { color: #6b7280; font-weight: 600; text-transform: uppercase; font-size: 12px; }
        
        .status { font-size: 11px; padding: 4px 10px; border-radius: 20px; font-weight: bold; }
        .borrowed { background: #dcfce7; color: #166534; }
        .pending { background: #fef3c7; color: #92400e; }
        .returned { background: #e0e7ff; color: #3730a3; }
        
        img { width: 40px; height: 40px; border-radius: 8px; object-fit: cover; }

        /* Pagination & Search UI */
        .header-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .search-box input { padding: 8px 15px; border: 1px solid #ddd; border-radius: 8px; width: 250px; }
        .pagination { display: flex; gap: 5px; margin-top: 15px; justify-content: flex-end; }
        .pagination a { 
            padding: 5px 12px; border: 1px solid #ddd; text-decoration: none; 
            color: #374151; border-radius: 6px; font-size: 13px; background: white;
        }
        .pagination a.active { background: #1f2937; color: white; border-color: #1f2937; }
    </style>
</head>
<body>

<?php include '../includes/sidebar-user.php'; ?>

<div class="main">
    <div class="header-flex">
        <h1>📜 My History</h1>
        <form method="GET" class="search-box">
            <input type="text" name="search" placeholder="Search by item name..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn" style="display:none">Search</button>
        </form>
    </div>

    <div class="section">
        <div style="display: flex; justify-content: space-between; align-items: baseline;">
            <h2>📦 Borrow Records</h2>
            <small>Total: <?= $total_rec ?></small>
        </div>
        <div class="card">
            <table>
                <tr>
                    <th>Item</th>
                    <th>Name</th>
                    <th>Qty</th>
                    <th>Borrowed</th>
                    <th>Return Date</th>
                    <th>Status</th>
                </tr>
                <?php if ($records->num_rows > 0): ?>
                    <?php while($row = $records->fetch_assoc()): ?>
                    <tr>
                        <td><img src="../assets/images/<?= $row['image']; ?>" onerror="this.src='https://via.placeholder.com/40'"></td>
                        <td><strong><?= htmlspecialchars($row['name']); ?></strong></td>
                        <td><?= $row['quantity']; ?></td>
                        <td><?= date("M d, Y", strtotime($row['borrow_date'])); ?></td>
                        <td><?= date("M d, Y", strtotime($row['return_date'])); ?></td>
                        <td>
                            <span class="status <?= $row['status'] ?>">
                                <?= ucfirst($row['status']) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6" style="text-align:center; padding: 30px;">No records found</td></tr>
                <?php endif; ?>
            </table>

            <?php if ($pages_rec > 1): ?>
            <div class="pagination">
                <?php for($i=1; $i<=$pages_rec; $i++): ?>
                    <a href="?p_rec=<?= $i ?>&p_log=<?= $p_log ?>&search=<?= urlencode($search) ?>" class="<?= ($p_rec==$i)?'active':'' ?>"><?= $i ?></a>
                <?php endfor; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="section">
        <div style="display: flex; justify-content: space-between; align-items: baseline;">
            <h2>🧾 Activity Logs</h2>
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
                <?php if ($logs->num_rows > 0): ?>
                    <?php while($row = $logs->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['equipment_name'] ?? 'N/A'); ?></td>
                        <td>
                            <span style="font-weight: 600; color: #4b5563;">
                                <?= ucfirst($row['action']) ?>
                            </span>
                        </td>
                        <td><small><?= htmlspecialchars($row['details']); ?></small></td>
                        <td style="color: #9ca3af; font-size: 12px;">
                            <?= date("M d, Y | h:i A", strtotime($row['created_at'])); ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="4" style="text-align:center; padding: 30px;">No activity found</td></tr>
                <?php endif; ?>
            </table>

            <?php if ($pages_log > 1): ?>
            <div class="pagination">
                <?php for($i=1; $i<=$pages_log; $i++): ?>
                    <a href="?p_rec=<?= $p_rec ?>&p_log=<?= $i ?>&search=<?= urlencode($search) ?>" class="<?= ($p_log==$i)?'active':'' ?>"><?= $i ?></a>
                <?php endfor; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>