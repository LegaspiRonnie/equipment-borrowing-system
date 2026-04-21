<?php
session_start();
include '../config/db.php';

$result = $conn->query("SELECT * FROM equipment ORDER BY created_at DESC");
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

<h1>Equipment List</h1>

<a href="equipment_add.php">
    <button class="btn btn-edit">+ Add Equipment</button>
</a>

<br><br>

<table>
<tr>
<th>Image</th>
<th>Name</th>
<th>Category</th>
<th>Status</th>
<th>Quantity</th>
<th>Created</th>
<th>Action</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>
<td>
    <img src="../assets/images/<?php echo $row['image']; ?>" width="50">
</td>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['category']; ?></td>
<td><?php echo $row['status']; ?></td>
<td><?php echo $row['quantity']; ?></td>
<td><?php echo $row['created_at']; ?></td>
<td>
    <a href="equipment_view.php?id=<?php echo $row['id']; ?>"><button class="btn">View</button></a>
    <a href="equipment_edit.php?id=<?php echo $row['id']; ?>"><button class="btn btn-edit">Edit</button></a>
    <a href="equipment_delete.php?id=<?php echo $row['id']; ?>"><button class="btn btn-delete">Delete</button></a>
</td>
</tr>
<?php endwhile; ?>

</table>

</div>
<?php
include '../includes/footer.html';
?>