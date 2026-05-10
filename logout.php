<?php
session_start();

// destroy session
session_unset();
session_destroy();

// start fresh session for alert
session_start();

$_SESSION['alert'] = [
    'type' => 'success',
    'message' => 'Logged out successfully.'
];

header("Location: index.php");
exit();
?>