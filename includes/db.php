<?php
$host = getenv("DB_HOST") ?: "localhost";
$dbname = getenv("DB_NAME") ?: "your_local_db_name";
$username = getenv("DB_USER") ?: "root";
$password = getenv("DB_PASS") ?: "";
$port = getenv("DB_PORT") ?: 3306;

$conn = new mysqli($host, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>
