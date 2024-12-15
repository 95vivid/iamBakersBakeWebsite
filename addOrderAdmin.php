<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = ''; // Change this if you have a password
$database = 'orders_db';

// Create database connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $delivery = $_POST['delivery'];
    $payment = $_POST['payment'];
    $total = $_POST['total'];

    // SQL query to insert data into the database
    $sql = "INSERT INTO orders (name, quantity, price, delivery, payment, total)
            VALUES ('$name', '$quantity', '$price', '$delivery', '$payment', '$total')";

    // Execute query
    if ($conn->query($sql) === TRUE) {
        echo "New order added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close connection
$conn->close();
?>
