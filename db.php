<?php


// Database configuration
$db_host = "localhost"; // Change this to your database host
$db_username = "root"; // Change this to your database username
$db_password = ""; // Change this to your database password
$db_name = "test_1"; // Change this to your database name

// Establish database connection
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
