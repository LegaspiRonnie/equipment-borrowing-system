<?php
$id = $_GET['id'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Delete User</title>
<link rel="stylesheet" href="../assets/css/global.css">
</head>
<body>

<div class="sidebar">...</div>

<div class="main">
<h1>Delete User</h1>

<p>Are you sure you want to delete this user?</p>

<a href="../process/user_delete_process.php?id=<?php echo $id; ?>">
<button class="btn btn-delete">Yes, Delete</button>
</a>

</div>
</body>
</html>