<?php 
if (!isset($_GET['id'])) {
    header("Location: equipment.php");
    exit();
}

$id = intval($_GET['id']); // secure
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Equipment</title>
    <link rel="stylesheet" href="../assets/css/global.css">
</head>
<body>

<?php
include '../includes/sidebar.php';
?>


<div class="main">
    <h1>Delete Equipment</h1>

    <p>Are you sure you want to delete this equipment?</p>

    <!-- YES DELETE -->
    <a href="../process/equipment_delete_process.php?id=<?php echo $id; ?>">
        <button class="btn btn-delete">Yes, Delete</button>
    </a>

    <!-- CANCEL -->
    <a href="equipment.php">
        <button class="btn">Cancel</button>
    </a>
</div>

</body>
</html>