<?php
$servername = "localhost";
$username = "root";
$password = "9536337196"; // Your MySQL password
$dbname = "admission_portal"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>