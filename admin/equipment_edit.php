<?php
include '../config/db.php';

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM equipment WHERE id=$id");
$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Equipment</title>
<link rel="stylesheet" href="../assets/css/global.css">
</head>
<body>

<?php
include '../includes/sidebar.php';
?>


<div class="main">
<h1>Edit Equipment</h1>

<form action="../process/equipment_edit_process.php" method="POST" enctype="multipart/form-data">

<input type="hidden" name="id" value="<?php echo $data['id']; ?>">

<input type="text" name="name" value="<?php echo $data['name']; ?>"><br><br>

<input type="text" name="category" value="<?php echo $data['category']; ?>"><br><br>

<input type="number" name="quantity" value="<?php echo $data['quantity']; ?>"><br><br>

<select name="status">
<option value="available" <?php if($data['status']=='available') echo 'selected'; ?>>Available</option>
<option value="borrowed" <?php if($data['status']=='borrowed') echo 'selected'; ?>>Borrowed</option>
<option value="maintenance" <?php if($data['status']=='maintenance') echo 'selected'; ?>>Maintenance</option>
</select><br><br>

<input type="file" name="image"><br><br>

<button class="btn btn-edit">Update</button>

</form>

</div>
</body>
</html>