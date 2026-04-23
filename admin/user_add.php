<!DOCTYPE html>
<html>
<head>
    <title>Add User</title>
    <link rel="stylesheet" href="../assets/css/global.css">
</head>
<body>

<?php
include '../includes/sidebar.php';
?>


<div class="main">
<h1>Add User</h1>

<form action="../process/user_add_process.php" method="POST">
<input type="text" name="name" placeholder="Name" required><br><br>
<input type="email" name="email" placeholder="Email" required><br><br>
<input type="password" name="password" placeholder="Password" required><br><br>

<select name="role">
<option value="user">User</option>
<option value="admin">Admin</option>
</select><br><br>

<button class="btn btn-edit">Add</button>
</form>

</div>
</body>
</html>