<?php
require_once '../config/auth.php';
require_role('admin');
include '../includes/header.html';
?>
<!-- SIDEBAR -->
<?php
include '../includes/sidebar.php';
?>


<!-- MAIN -->
<div class="main">

<h1>Settings</h1>

<div class="card">
    <label><b>Change Background Color</b></label><br><br>

    <input type="color" id="bgColor">

    <br><br>
    <button onclick="resetColor()">Reset</button>
</div>

</div>

<!-- SCRIPT -->
<?php
include '../includes/footer.html';
?>