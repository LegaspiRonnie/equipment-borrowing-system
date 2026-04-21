<?php
include '../config/db.php';

$id = $_GET['id'];

$conn->query("DELETE FROM equipment WHERE id=$id");

header("Location: ../admin/equipment.php");
?>