<?php
// Database connection file

$servername = "localhost"; 
$username = "root";        
$password = "";            // Your MySQL password (leave empty for default XAMPP)
$database = "theraflix";   

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}
?>
