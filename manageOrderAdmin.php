<?php
// Disable error display in output
ini_set('display_errors', 0);
error_reporting(E_ALL);
// Log errors instead
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

// CORS headers
header("Access-Control-Allow-Origin: http://localhost");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "register_db";

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    http_response_code(500);
    echo json_encode(["success" => false, "error" => "Database connection failed"]);
    exit();
}

try {
    // Handle GET request (fetch orders)
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $sql = "SELECT order_id, customer_id, productName, productQuantity, order_date, total_amount FROM orders";
        $result = $conn->query($sql);

        if (!$result) {
            throw new Exception("Database query failed: " . $conn->error);
        }

        $orders = [];
        while ($row = $result->fetch_assoc()) {
            // Map customer_id to user_id for frontend compatibility
            $row['user_id'] = $row['customer_id'];
            unset($row['customer_id']);
            $orders[] = $row;
        }

        echo json_encode($orders);
        exit();
    }

    // Handle POST request (add/update order)
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        if ($data === null) {
            throw new Exception("Invalid JSON data: " . json_last_error_msg());
        }

        $order_id = $data['order_id'] ?? '';
        $customer_id = $data['customer_id'] ?? '';
        $productName = $data['productName'] ?? '';
        $productQuantity = $data['productQuantity'] ?? '';
        $order_date = $data['order_date'] ?? '';
        $total_amount = $data['total_amount'] ?? '';

        // Validate required fields
        if (empty($customer_id) || empty($productName) || empty($productQuantity) || empty($order_date) || empty($total_amount)) {
            throw new Exception("All fields are required");
        }

        // Sanitize inputs
        $customer_id = $conn->real_escape_string($customer_id);
        $productName = $conn->real_escape_string($productName);
        $productQuantity = $conn->real_escape_string($productQuantity);
        $order_date = $conn->real_escape_string($order_date);
        $total_amount = $conn->real_escape_string($total_amount);

        if (!empty($order_id)) {
            // Update existing order
            $order_id = $conn->real_escape_string($order_id);
            $sql = "UPDATE orders SET 
                    customer_id = '$customer_id',
                    productName = '$productName',
                    productQuantity = '$productQuantity',
                    order_date = '$order_date',
                    total_amount = '$total_amount'
                    WHERE order_id = '$order_id'";
        } else {
            // Insert new order
            $sql = "INSERT INTO orders 
                    (customer_id, productName, productQuantity, order_date, total_amount) 
                    VALUES 
                    ('$customer_id', '$productName', '$productQuantity', '$order_date', '$total_amount')";
        }

        if (!$conn->query($sql)) {
            throw new Exception("Database error: " . $conn->error);
        }

        echo json_encode(["success" => true]);
        exit();
    }

    // Handle DELETE request
    elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        if ($data === null) {
            throw new Exception("Invalid JSON data: " . json_last_error_msg());
        }

        $order_id = $data['order_id'] ?? '';
        if (empty($order_id)) {
            throw new Exception("Order ID is required");
        }

        $order_id = $conn->real_escape_string($order_id);
        $sql = "DELETE FROM orders WHERE order_id='$order_id'";

        if (!$conn->query($sql)) {
            throw new Exception("Database error: " . $conn->error);
        }

        echo json_encode(["success" => true]);
        exit();
    }

    // Handle unsupported methods
    else {
        http_response_code(405);
        echo json_encode(["success" => false, "error" => "Method not allowed"]);
        exit();
    }

} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ]);
    exit();
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?>
