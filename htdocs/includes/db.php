<?php

// If you're testing on localhost and not using environment variables,
// you should directly assign the values (NOT using getenv()).

// $host = 'mysql-metromedia.alwaysdata.net'; // Alwaysdata's MySQL host
// $username = '419038_theinthan'; // Your Alwaysdata username
// $password = '$@!minHtike087482'; // Your Alwaysdata MySQL password
// $dbname = 'metromedia_db'; // Your database name
// $port = 3306; // Usually 3306, AlwaysData uses 3306 too

// $conn = new mysqli($host, $username, $password, $dbname, $port);

// // Check connection
// if ($conn->connect_error) {
//     error_log("Database Connection Failed: " . $conn->connect_error);
//     die("Error: Could not connect to the database. Please try again later.");
// }

// $conn->set_charset("utf8mb4");

// $host = "localhost";
// $dbname = "4633870_socialmedia";        
// $username = "root";      
// $password = ""; 

// $conn = new mysqli($host, $username, $password, $dbname);

// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

$host = 'sql102.ezyro.com'; // Replace with your DB Host
$username = 'ezyro_39297269'; // Your DB Username
$password = '$@!Min087482';
$dbname = 'ezyro_39297269_metromedia';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



?>
