<?php

session_start();

include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if(!empty($username) && !empty($password)) {
        $query = "SELECT * FROM users WHERE username = '$username' limit 1";
        $result = mysqli_query($conn, $query);

        if($result){
            if($result && mysqli_num_rows($result) > 0)
            {
                $user_data = mysqli_fetch_assoc($result);

                if($user_data['password'] == $password){
                    header("location: dashboard.html");
                    die;
                }
            }
        }
        echo "<script type='text/javascript'> alert ('Wrong Username or Password')</script>";
    }
    else{
        echo "<script type='text/javascript'> alert ('Welcome User')</script>";
    }

    // Check if username and password are not empty
    if (!empty($username) && !empty($password)) {
        // Query the database for the user
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // Validate the user credentials
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                // Set session and redirect to dashboard
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                header("Location: dashboard.html");
                exit();
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "No user found with that username.";
        }
    } else {
        $error = "Please fill in both fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BakersBakes</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital@0;1&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --color-primary: #333333;
            --color-secondary: #666666;
            --color-background: #f5f5f5;
            --color-white: #ffffff;
            --font-primary: 'Playfair Display', serif;
            --font-secondary: 'Inter', sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-secondary);
            background-color: var(--color-background);
            color: var(--color-primary);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            padding: 1rem 2rem;
            background-color: var(--color-white);
            border-bottom: 1px solid #eee;
        }

        .nav {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--color-secondary);
            font-size: 0.9rem;
            text-transform: uppercase;
        }

        .nav-links a.active {
            color: var(--color-primary);
            font-weight: 500;
        }

        .cart-icon {
            width: 24px;
            height: 24px;
        }

        .main-content {
            max-width: 480px;
            margin: 4rem auto;
            padding: 2rem;
            background: var(--color-white);
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .login-title {
            font-family: var(--font-primary);
            font-size: 2rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--color-secondary);
            font-size: 0.9rem;
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: var(--font-secondary);
        }

        .button-group {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
        }

        .btn {
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-family: var(--font-secondary);
        }

        .btn-cancel {
            background-color: transparent;
            border: 1px solid #ddd;
        }

        .btn-submit {
            background-color: var(--color-primary);
            color: var(--color-white);
        }

        .signup-link {
            text-align: center;
            margin-top: 2rem;
            color: var(--color-secondary);
        }

        .signup-link a {
            color: var(--color-primary);
            text-decoration: underline;
        }

        .footer {
            background-color: #333;
            color: var(--color-white);
            padding: 4rem 0 2rem;
            margin-top: auto;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }

        .footer-logo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
        }

        .footer-section h3 {
            margin-bottom: 1rem;
        }

        .footer-section p {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 0.5rem;
        }

        .social-links {
            display: flex;
            gap: 1rem;
        }

        .social-link img {
            width: 24px;
            height: 24px;
            opacity: 0.8;
            transition: opacity 0.3s;
        }

        .social-link:hover img {
            opacity: 1;
        }
    </style>
</head>
<body>
    <header class="header">
        <nav class="nav">
            <a href="/">
                <img src="img/logo.jpg" alt="Patisserie Logo" class="logo">
            </a>
            <div class="nav-links">
                <a href="dashboard.html">MAIN</a>
                <a href="catalog.html">PRODUCTS</a>
                <a href="faq.html">FAQ</a>
                <a href="profile.php">PROFILE</a>
                <a href="/login" class="active">SIGN UP / LOGIN</a>
                <a href="cart.php">
                    <img src="https://raw.githubusercontent.com/twbs/icons/main/icons/bag-heart.svg" alt="Cart" class="cart-icon">
                </a>
            </div>
        </nav>
    </header>

    <main class="main-content">
        <h1 class="login-title">Login</h1>
        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter username" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter password" required>
            </div>

            <div class="button-group">
                <button type="button" class="btn btn-cancel">Cancel</button>
                <button type="submit" class="btn btn-submit">Submit</button>
            </div>

            <p class="signup-link">Don't have an account? <a href="signup.php">Create one here</a></p>
        </form>
    </main>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <img src="img/logo.jpg" alt="Patisserie Logo" class="footer-logo">
            </div>
            
            <div class="footer-section">
                <h3>Contact</h3>
                <p>📍 Kuching, Sarawak, Malaysia</p>
                <p>📞 +60 18 980 3060</p>
                <p>📧 <a href="mailto:irasyahirah@gmail.com">irasyahirah@gmail.com</a></p>
            </div>

            <div class="footer-section">
                <h3>Social Media</h3>
                <div class="social-links">
                    <a href="#" class="social-link">
                        <img src="https://raw.githubusercontent.com/twbs/icons/main/icons/facebook.svg" alt="Facebook">
                    </a>
                    <a href="#" class="social-link">
                        <img src="https://raw.githubusercontent.com/twbs/icons/main/icons/instagram.svg" alt="Instagram">
                    </a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>