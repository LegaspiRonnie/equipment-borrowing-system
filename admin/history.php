<?php
include '../config/db.php';

// get all history logs with user and equipment info
$result = $conn->query("
SELECT h.*, u.name AS user_name, e.name AS equipment_name
FROM history_logs h
LEFT JOIN users u ON h.user_id = u.id
LEFT JOIN equipment e ON h.equipment_id = e.id
ORDER BY h.created_at DESC
");
?>

<?php
include '../includes/header.html';
?>

<div class="sidebar">
    <h2>ADMIN</h2>
    <a href="dashboard.php">Dashboard</a>
    <a href="equipment.php">Equipment</a>
    <a href="borrow_requests.php">Borrow Requests</a>
    <a href="return_items.php">Returns</a>
    <a href="reports.php">Reports</a>
    <a href="history.php">History</a>
    <a href="users.php">Users</a>
    <a href="settings.php">Settings</a>
</div>

<div class="main">

<h1>History Logs</h1>

<table>
<tr>
    <th>User</th>
    <th>Action</th>
    <th>Item</th>
    <th>Details</th>
    <th>Date</th>
</tr>

<?php if ($result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['user_name'] ?? 'N/A'; ?></td>

        <td>
            <?php
            if ($row['action'] == 'request') echo "Requested";
            elseif ($row['action'] == 'approved') echo "Approved";
            elseif ($row['action'] == 'rejected') echo "Rejected";
            elseif ($row['action'] == 'returned') echo "Returned";
            else echo ucfirst($row['action']);
            ?>
        </td>

        <td><?php echo $row['equipment_name'] ?? 'N/A'; ?></td>

        <td><?php echo $row['details']; ?></td>

        <td>
            <?php echo date("F d, Y h:i A", strtotime($row['created_at'])); ?>
        </td>
    </tr>
    <?php endwhile; ?>
<?php else: ?>
    <tr>
        <td colspan="5">No history found</td>
    </tr>
<?php endif; ?>

</table>

</div>

<?php
include '../includes/footer.html';
?>