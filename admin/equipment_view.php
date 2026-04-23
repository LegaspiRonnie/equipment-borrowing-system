<?php
include '../config/db.php';

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM equipment WHERE id=$id");
$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
<title>View Equipment</title>
<link rel="stylesheet" href="../assets/css/global.css">
</head>
<body>

<?php
include '../includes/sidebar.php';
?>


<div class="main">
<h1>Equipment Details</h1>

<img src="../assets/images/<?php echo $data['image']; ?>" width="150"><br><br>

<p>Name: <?php echo $data['name']; ?></p>
<p>Category: <?php echo $data['category']; ?></p>
<p>Status: <?php echo $data['status']; ?></p>
<p>Quantity: <?php echo $data['quantity']; ?></p>
<p>Created: <?php echo $data['created_at']; ?></p>

</div>
</body>
</html>