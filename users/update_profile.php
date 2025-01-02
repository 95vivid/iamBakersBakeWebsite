<?php
session_start();
include("db.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $response = array('status' => 'error', 'message' => 'User not logged in');
    echo json_encode($response);
    exit();
}

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response = array('status' => 'error', 'message' => 'Invalid request method');
    echo json_encode($response);
    exit();
}

// Get user input
$user_id = $_SESSION['user_id'];
$fullname = trim($_POST['fullName']);
$username = trim($_POST['username']);
$email = trim($_POST['email']);
$password = trim($_POST['password']); // Only process if not empty

// Validate inputs
if (empty($fullname) || empty($username) || empty($email)) {
    $response = array('status' => 'error', 'message' => 'All fields except password are required');
    echo json_encode($response);
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $response = array('status' => 'error', 'message' => 'Invalid email format');
    echo json_encode($response);
    exit();
}

// Check if username or email already exists (excluding current user)
$check_sql = "SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?";
if ($check_stmt = $conn->prepare($check_sql)) {
    $check_stmt->bind_param("ssi", $username, $email, $user_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        $response = array('status' => 'error', 'message' => 'Username or email already exists');
        echo json_encode($response);
        $check_stmt->close();
        exit();
    }
    $check_stmt->close();
}

// Prepare the base update query
if (!empty($password)) {
    // Update including password
    $sql = "UPDATE users SET fullname = ?, username = ?, email = ?, password = ? WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bind_param("ssssi", $fullname, $username, $email, $hashed_password, $user_id);
    }
} else {
    // Update without password
    $sql = "UPDATE users SET fullname = ?, username = ?, email = ? WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssi", $fullname, $username, $email, $user_id);
    }
}

// Execute the update
if (isset($stmt)) {
    try {
        if ($stmt->execute()) {
            $response = array(
                'status' => 'success',
                'message' => 'Profile updated successfully',
                'data' => array(
                    'fullName' => $fullname,
                    'username' => $username,
                    'email' => $email
                )
            );
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to update profile');
        }
    } catch (Exception $e) {
        $response = array('status' => 'error', 'message' => 'Database error occurred');
    }
    $stmt->close();
} else {
    $response = array('status' => 'error', 'message' => 'Failed to prepare update statement');
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);

// Close database connection
$conn->close();
?>