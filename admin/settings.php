
<?php
include '../includes/header.html';
?>
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
    <a href="settings.php">Settings</a>
</div>

<!-- MAIN -->
<div class="main">

<h1>Settings</h1>

<div class="card">
    <label><b>Change Background Color</b></label><br><br>

    <input type="color" id="bgColor">

    <br><br>
    <button onclick="resetColor()">Reset</button>
</div>

</div>

<!-- SCRIPT -->
<?php
include '../includes/footer.html';
?>