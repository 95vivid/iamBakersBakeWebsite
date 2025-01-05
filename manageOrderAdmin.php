<?php
include("db.php"); // Include your database connection file

// Fetch all orders (GET request)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_GET['order_id'])) {
    $sql = "SELECT * FROM orders";
    $result = $conn->query($sql);

    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }

    echo json_encode($orders);
    exit;
}

// Fetch a single order by ID (GET request)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    $sql = "SELECT * FROM orders WHERE order_id = '$order_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode([]);
    }
    exit;
}

// Handle POST request for adding or editing orders
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $order_id = $data['order_id'] ?? null;  // if null, new order, else existing order
    $customer_id = $data['customer_id'];
    $cust_name = $data['cust_name'];
    $cust_num = $data['cust_num'];
    $cust_email = $data['cust_email'];
    $address = $data['address'];
    $productName = $data['productName'];
    $productQuantity = $data['productQuantity'];
    $order_date = $data['order_date'];
    $payment_method = $data['payment_method'];
    $delivery_opt = $data['delivery_opt'];
    $total_amount = $data['total_amount'];

    if ($order_id) {
        // Update order
        $sql = "UPDATE orders SET 
                    customer_id = '$customer_id',
                    cust_name = '$cust_name',
                    cust_num = '$cust_num',
                    cust_email = '$cust_email',
                    address = '$address',
                    productName = '$productName',
                    productQuantity = '$productQuantity',
                    order_date = '$order_date',
                    payment_method = '$payment_method',
                    delivery_opt = '$delivery_opt',
                    total_amount = '$total_amount'
                WHERE order_id = '$order_id'";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => $conn->error]);
        }
    } else {
        // Insert new order
        $sql = "INSERT INTO orders (customer_id, cust_name, cust_num, cust_email, address, productName, productQuantity, order_date, payment_method, delivery_opt, total_amount)
                VALUES ('$customer_id', '$cust_name', '$cust_num', '$cust_email', '$address', '$productName', '$productQuantity', '$order_date', '$payment_method', '$delivery_opt', '$total_amount')";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => $conn->error]);
        }
    }
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    $order_id = $data['order_id'];

    $sql = "DELETE FROM orders WHERE order_id = '$order_id'";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $conn->error]);
    }
}

$conn->close();
?>
