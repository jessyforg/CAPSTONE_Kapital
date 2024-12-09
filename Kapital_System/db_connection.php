<?php
$host = "localhost";        // Database host
$username = "root";         // Database username
$password = "";             // Database password
$dbname = "StartupConnect"; // Database name

// Create a MySQLi connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>