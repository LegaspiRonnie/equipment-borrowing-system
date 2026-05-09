<?php
require_once '../config/auth.php';
require_role('admin');
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

<div class="card">
<form>
    <label>Name</label><br>
    <input type="text" value="Mouse"><br><br>

    <label>Change Image</label><br>
    <input type="file"><br><br>

    <label>Status</label><br>
    <select>
        <option selected>Available</option>
        <option>Borrowed</option>
    </select><br><br>

    <button class="btn btn-edit">Update</button>
</form>
</div>

</div>

<?php include '../includes/footer.html'; ?>
