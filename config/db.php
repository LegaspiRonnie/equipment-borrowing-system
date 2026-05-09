<?php

$host = getenv('DB_HOST') ?: "127.0.0.1";
$username = getenv('DB_USERNAME') ?: "root";
$password = getenv('DB_PASSWORD') ?: "";
$database = getenv('DB_DATABASE') ?: "equipment_borrowing_system";

// Create connection
try {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn = new mysqli($host, $username, $password, $database);
} catch (mysqli_sql_exception $e) {
    die("Database connection failed. Please check that MySQL/MariaDB is running and that config/db.php has the correct host, username, password, and database name.");
}

// Optional: set charset
$conn->set_charset("utf8");

?>
