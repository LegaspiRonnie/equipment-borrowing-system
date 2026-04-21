<?php
include '../config/db.php';

$result = $conn->query("
SELECT 
    br.*, 
    u.name AS user_name, 
    e.name AS equipment_name,
    e.image AS equipment_image
FROM borrow_records br
JOIN users u ON br.user_id = u.id
JOIN equipment e ON br.equipment_id = e.id
WHERE br.status = 'pending'
ORDER BY br.id DESC
");
?>

<?php
include '../includes/header.html';
?>

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
            vertical-align: middle;
        }

        th {
            background: #2c3e50;
            color: white;
        }

        img {
            object-fit: cover;
            border-radius: 6px;
        }

        .btn {
            padding: 6px 10px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }

        .btn-approve {
            background: #27ae60;
            color: white;
        }

        .btn-delete {
            background: #e74c3c;
            color: white;
        }

        .main {
            margin-left: 220px;
            padding: 20px;
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
    </style>


<!-- SIDEBAR -->
<div class="sidebar">
    <h2>ADMIN</h2>
    <a href="dashboard.php">Dashboard</a>
    <a href="equipment.php">Equipment</a>
    <a href="borrow_requests.php">Borrow Requests</a>
    <a href="return_items.php">Returns</a>
    <a href="reports.php">Reports</a>
    <a href="history.php">History</a>
    <a href="users.php">Users</a>
</div>

<!-- MAIN -->
<div class="main">

<h1>Return Requests (Pending)</h1>

<table>
<tr>
    <th>User</th>
    <th>Equipment</th>
    <th>Image</th>
    <th>Quantity</th>
    <th>Return Date</th>
    <th>Action</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>

    <!-- USER -->
    <td><?php echo $row['user_name']; ?></td>

    <!-- EQUIPMENT -->
    <td><?php echo $row['equipment_name']; ?></td>

    <!-- IMAGE -->
    <td>
        <?php
        $img = !empty($row['equipment_image']) 
            ? "../assets/images/" . $row['equipment_image'] 
            : "../assets/images/default.png";
        ?>
        <img src="<?php echo $img; ?>" width="60" height="60">
    </td>

    <!-- QUANTITY -->
    <td>
        <?php echo isset($row['quantity']) ? $row['quantity'] : 1; ?>
    </td>

    <!-- RETURN DATE -->
    <td><?php echo $row['return_date']; ?></td>

    <!-- ACTION -->
    <td>
        <a href="../process/approve_return.php?id=<?php echo $row['id']; ?>">
            <button class="btn btn-approve">Approve</button>
        </a>

        <a href="../process/reject_return.php?id=<?php echo $row['id']; ?>">
            <button class="btn btn-delete">Reject</button>
        </a>
    </td>

</tr>
<?php endwhile; ?>

</table>

</div>

<?php
include '../includes/footer.html';
?>