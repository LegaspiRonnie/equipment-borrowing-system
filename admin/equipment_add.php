<!DOCTYPE html>
<html>
<head>
<title>Add Equipment</title>
<link rel="stylesheet" href="../assets/css/global.css">
</head>
<body>

<div class="sidebar">...</div>

<div class="main">
<h1>Add Equipment</h1>

<form action="../process/equipment_add_process.php" method="POST" enctype="multipart/form-data">

<input type="text" name="name" placeholder="Equipment Name" required><br><br>

<input type="text" name="category" placeholder="Category (Mouse, Keyboard...)" required><br><br>

<input type="number" name="quantity" placeholder="Quantity" required><br><br>

<select name="status">
<option value="available">Available</option>
<option value="maintenance">Maintenance</option>
</select><br><br>

<input type="file" name="image" required><br><br>

<button class="btn btn-edit">Add Equipment</button>

</form>

</div>
</body>
</html>