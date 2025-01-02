<?php
session_start();

// Database connection
$host = 'localhost';
$dbname = 'adminbaker';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare SQL statement to find user by email
    $stmt = $pdo->prepare("SELECT * FROM registeradmin WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Check if user exists and password is correct
    if ($user && $user['password'] === $password) {  // Direct password comparison for plain text
        // Login successful
        $_SESSION['email'] = $user['email'];  // Store email in session
        // No need to store password in session as it's unnecessary
        // Redirect to admin dashboard PHP page
        header("Location: dashboardAdmin.html");  // Assuming admin.php is the correct page
        exit();
    } else {
        // Login failed
        echo "<script>
            alert('Invalid email or password. Please try again.');
            window.location.href = 'loginAdmin.html';
        </script>";
    }
}
?>
