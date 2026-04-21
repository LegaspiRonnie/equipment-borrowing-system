<?php $id = $_GET['id']; ?>

<!DOCTYPE html>
<html>
<head>
<title>Delete Equipment</title>
<link rel="stylesheet" href="../assets/css/global.css">
</head>
<body>

<div class="sidebar">...</div>

<div class="main">
<h1>Delete Equipment</h1>

<p>Are you sure you want to delete this equipment?</p>

<a href="../process/equipment_delete_process.php?id=<?php echo $id; ?>">
<button class="btn btn-delete">Yes, Delete</button>
</a>

</div>
</body>
</html>