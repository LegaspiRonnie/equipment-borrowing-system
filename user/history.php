<?php
session_start();
include '../config/db.php';

$user_id = $_SESSION['user_id'];

/* HISTORY LOGS */
$logs = $conn->query("
SELECT h.*, e.name AS equipment_name
FROM history_logs h
LEFT JOIN equipment e ON h.equipment_id = e.id
WHERE h.user_id = $user_id
ORDER BY h.created_at DESC
");

/* BORROW RECORDS */
$records = $conn->query("
SELECT br.*, e.name, e.image
FROM borrow_records br
JOIN equipment e ON br.equipment_id = e.id
WHERE br.user_id = $user_id
ORDER BY br.borrow_date DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>My History</title>
    <link rel="stylesheet" href="../assets/css/global.css">

    <style>
        .section {
            margin-bottom: 30px;
        }

        .card {
            background: white;
            padding: 15px;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        .status {
            font-size: 12px;
            padding: 4px 8px;
            border-radius: 6px;
        }

        .borrowed { background: #d1fae5; color: #065f46; }
        .pending { background: #fef3c7; color: #92400e; }
        .returned { background: #e0e7ff; color: #3730a3; }

        img {
            width: 40px;
            height: 40px;
            border-radius: 6px;
            object-fit: cover;
        }
    </style>
</head>

<body>

<?php
include '../includes/sidebar-user.php';
?>

<div class="main">

<h1>📜 My History</h1>

<!-- ================= BORROW RECORDS ================= -->
<div class="section">
    <h2>📦 Borrow Records</h2>

    <div class="card">
        <table>
            <tr>
                <th>Item</th>
                <th>Name</th>
                <th>Quantity</th>
                <th>Borrow Date</th>
                <th>Return Date</th>
                <th>Status</th>
            </tr>

            <?php if ($records->num_rows > 0): ?>
                <?php while($row = $records->fetch_assoc()): ?>
                <tr>
                    <td>
                        <img src="../assets/images/<?php echo $row['image']; ?>"
                             onerror="this.src='https://via.placeholder.com/40'">
                    </td>

                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['quantity']; ?></td>

                    <td><?php echo $row['borrow_date']; ?></td>
                    <td><?php echo $row['return_date']; ?></td>

                    <td>
                        <?php if($row['status'] == 'borrowed'): ?>
                            <span class="status borrowed">Borrowed</span>
                        <?php elseif($row['status'] == 'pending'): ?>
                            <span class="status pending">Pending</span>
                        <?php else: ?>
                            <span class="status returned">Returned</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No borrow records found</td>
                </tr>
            <?php endif; ?>

        </table>
    </div>
</div>


<!-- ================= HISTORY LOGS ================= -->
<div class="section">
    <h2>🧾 Activity Logs</h2>

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
                    <td><?php echo $row['equipment_name'] ?? 'N/A'; ?></td>

                    <td>
                        <?php
                        if ($row['action'] == 'request') echo "Requested";
                        elseif ($row['action'] == 'approved') echo "Approved";
                        elseif ($row['action'] == 'rejected') echo "Rejected";
                        elseif ($row['action'] == 'returned') echo "Returned";
                        else echo ucfirst($row['action']);
                        ?>
                    </td>

                    <td><?php echo $row['details']; ?></td>

                    <td>
                        <?php echo date("F d, Y h:i A", strtotime($row['created_at'])); ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No history logs found</td>
                </tr>
            <?php endif; ?>

        </table>
    </div>
</div>

</div>

</body>
</html>