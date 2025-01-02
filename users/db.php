<?php
$host = "localhost";
$user = "root";
$password = ""; // Default password for XAMPP is empty
$dbname = "register"; // Use the database name you created

$conn = mysqli_connect($host, $user, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
