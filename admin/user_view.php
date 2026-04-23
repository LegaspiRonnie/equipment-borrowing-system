<?php
include '../config/db.php';

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM users WHERE id=$id");
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
<title>View User</title>
<link rel="stylesheet" href="../assets/css/global.css">
</head>
<body>

<?php
include '../includes/sidebar.php';
?>


<div class="main">
<h1>User Details</h1>

<p>Name: <?php echo $user['name']; ?></p>
<p>Email: <?php echo $user['email']; ?></p>
<p>Role: <?php echo $user['role']; ?></p>
<p>Created: <?php echo $user['created_at']; ?></p>

</div>
</body>
</html>