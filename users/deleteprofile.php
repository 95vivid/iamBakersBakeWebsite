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

$user_id = $_SESSION['user_id'];

// Prepare the delete query
$sql = "DELETE FROM users WHERE id = ?";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $user_id);
    
    try {
        if ($stmt->execute()) {
            // Clear the session
            session_destroy();
            
            $response = array(
                'status' => 'success',
                'message' => 'Account deleted successfully'
            );
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to delete account');
        }
    } catch (Exception $e) {
        $response = array('status' => 'error', 'message' => 'Database error occurred');
    }
    
    $stmt->close();
} else {
    $response = array('status' => 'error', 'message' => 'Failed to prepare delete statement');
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);

// Close database connection
$conn->close();
?>