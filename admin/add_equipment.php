<?php
require_once '../config/auth.php';
require_role('admin');
include '../includes/header.html';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Equipment</title>
    <link rel="stylesheet" href="../assets/css/global.css">
</head>
<body>
<?php include '../partials/alert.php'; ?>
<?php
include '../includes/sidebar.php';
?>


<div class="main">

<h1>Add Equipment</h1>

<div class="card">
<form>
    <label>Equipment Name</label><br>
    <input type="text"><br><br>

    <label>Upload Image</label><br>
    <input type="file"><br><br>

    <label>Status</label><br>
    <select>
        <option>Available</option>
        <option>Maintenance</option>
    </select><br><br>

    <button class="btn btn-edit">Add</button>
</form>
</div>

</div>

<?php include '../includes/footer.html'; ?>
