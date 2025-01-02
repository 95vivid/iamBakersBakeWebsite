<?php
session_start();
include("db.php"); 

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Sanitize and validate input
    $fullname = htmlspecialchars(trim($_POST['fullname']));
    $username = htmlspecialchars(trim($_POST['username']));
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Initialize error array
    $errors = array();

    // Validate form fields
    if (empty($fullname) || empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        $errors[] = "All fields are required.";
    }

    // Validate email format
    if (!$email) {
        $errors[] = "Invalid email format.";
    }

    // Check if username already exists
    $check_username = "SELECT username FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $check_username);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) > 0) {
        $errors[] = "Username already exists.";
    }
    mysqli_stmt_close($stmt);

    // Check if email already exists
    $check_email = "SELECT email FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $check_email);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) > 0) {
        $errors[] = "Email already registered.";
    }
    mysqli_stmt_close($stmt);

    // Validate password
    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    // Check password requirements
    if (strlen($password) < 6 || strlen($password) > 8 || 
        !preg_match('/[A-Z]/', $password) || 
        !preg_match('/\d/', $password) || 
        !preg_match('/[\W_]/', $password) ||
        preg_match('/\s/', $password)) {
        $errors[] = "Password must be 6-8 characters long, contain one uppercase letter, one number, one special character, and no spaces.";
    }

    // If no errors, proceed with registration
    if (empty($errors)) {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert user into database
        $query = "INSERT INTO users (fullname, username, email, password) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ssss", $fullname, $username, $email, $hashedPassword);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['signup_success'] = true;
            echo "<script>
                    alert('Registration successful! Please login.');
                    window.location.href = 'login.php';
                  </script>";
            exit();
        } else {
            echo "<script>
                    alert('Error occurred during registration. Please try again.');
                    window.location.href = 'signup.html';
                  </script>";
        }
        mysqli_stmt_close($stmt);
    } else {
        // Display all errors
        echo "<script>
                alert('" . addslashes(implode("\\n", $errors)) . "');
                window.location.href = 'signup.html';
              </script>";
    }
}
?>