<?php
include '../config/db.php';

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM users WHERE id=$id");
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit User</title>
<link rel="stylesheet" href="../assets/css/global.css">
</head>
<body>

<?php
include '../includes/sidebar.php';
?>


<div class="main">
<h1>Edit User</h1>

<form action="../process/user_edit_process.php" method="POST">
<input type="hidden" name="id" value="<?php echo $user['id']; ?>">

<input type="text" name="name" value="<?php echo $user['name']; ?>"><br><br>
<input type="email" name="email" value="<?php echo $user['email']; ?>"><br><br>

<select name="role">
<option value="user" <?php if($user['role']=='user') echo 'selected'; ?>>User</option>
<option value="admin" <?php if($user['role']=='admin') echo 'selected'; ?>>Admin</option>
</select><br><br>

<button class="btn btn-edit">Update</button>
</form>

</div>
</body>
</html>