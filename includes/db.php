<?php
// Use environment variables if set, or fall back to localhost values
$host = getenv("DB_HOST") ?: "localhost";
$dbname = getenv("DB_NAME") ?: "4633870_socialmedia";
$username = getenv("DB_USER") ?: "root";
$password = getenv("DB_PASS") ?: "";
$port = getenv("DB_PORT") ?: 3306;

// Connect to MySQL
$conn = new mysqli($host, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8mb4");
?>
