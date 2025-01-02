<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - BakersBakes</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital@0;1&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        /* Your existing CSS remains the same */
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
            margin: 2rem auto;
            padding: 2rem;
            background: var(--color-white);
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .signup-title {
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

        .password-hint {
            font-size: 0.8rem;
            color: var(--color-secondary);
            margin-top: 0.25rem;
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

        .login-link {
            text-align: center;
            margin-top: 2rem;
            color: var(--color-secondary);
        }

        .login-link a {
            color: var(--color-primary);
            text-decoration: underline;
        }

        .error-message {
            background-color: #ffebee;
            color: #c62828;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 1rem;
            border: 1px solid #ffcdd2;
        }

        .success-message {
            background-color: #e8f5e9;
            color: #2e7d32;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 1rem;
            border: 1px solid #c8e6c9;
        }

        /* Footer styles remain the same */
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
    </style>
</head>
<body>
    <header class="header">
        <nav class="nav">
            <a href="/">
                <img src="img/logo.jpg" alt="BakersBakes Logo" class="logo">
            </a>
            <div class="nav-links">
                <a href="dashboard.html">MAIN</a>
                <a href="catalog.html">PRODUCTS</a>
                <a href="faq.html">FAQ</a>
                <a href="profile.html">PROFILE</a>
                <a href="signup.php" class="active">SIGN UP / LOGIN</a>
                <a href="cart.php">
                    <img src="https://raw.githubusercontent.com/twbs/icons/main/icons/bag-heart.svg" alt="Cart" class="cart-icon">
                </a>
            </div>
        </nav>
    </header>
    
    <main class="main-content">
        <h1 class="signup-title">Sign Up</h1>
        
        <?php
        // Display error messages if any
        if (isset($_SESSION['errors'])) {
            echo '<div class="error-message">';
            foreach ($_SESSION['errors'] as $error) {
                echo htmlspecialchars($error) . '<br>';
            }
            echo '</div>';
            unset($_SESSION['errors']);
        }

        // Display success message if any
        if (isset($_SESSION['success'])) {
            echo '<div class="success-message">' . htmlspecialchars($_SESSION['success']) . '</div>';
            unset($_SESSION['success']);
        }
        ?>

        <form action="signup_process.php" method="POST">
            <div class="form-group">
                <label for="fullname">Full Name</label>
                <input type="text" id="fullname" name="fullname" placeholder="Enter full name" 
                       value="<?php echo isset($_SESSION['old_input']['fullname']) ? htmlspecialchars($_SESSION['old_input']['fullname']) : ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter username" 
                       value="<?php echo isset($_SESSION['old_input']['username']) ? htmlspecialchars($_SESSION['old_input']['username']) : ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" placeholder="Enter e-mail" 
                       value="<?php echo isset($_SESSION['old_input']['email']) ? htmlspecialchars($_SESSION['old_input']['email']) : ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter password" required>
                <p class="password-hint">Your password must be 6-8 characters long, contain one uppercase, one numeric, one special character, and no spaces.</p>
            </div>

            <div class="form-group">
                <label for="confirmPassword">Confirm Password</label>
                <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm password" required>
            </div>

            <div class="button-group">
                <button type="reset" class="btn btn-cancel">Cancel</button>
                <button type="submit" class="btn btn-submit">Submit</button>
            </div>

            <p class="login-link">Already have an account? <a href="login.php">Login here</a></p>
        </form>
    </main>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <img src="img/logo.jpg" alt="BakersBakes Logo" class="footer-logo">
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
                    <a href="https://www.facebook.com/login" class="social-link">
                        <img src="https://raw.githubusercontent.com/twbs/icons/main/icons/facebook.svg" alt="Facebook">
                    </a>
                    <a href="https://www.instagram.com/iam.bakersbakes?igsh=em4wb2RrcHNiYnpi" class="social-link">
                        <img src="https://raw.githubusercontent.com/twbs/icons/main/icons/instagram.svg" alt="Instagram">
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <?php
    // Clear old input after displaying
    if (isset($_SESSION['old_input'])) {
        unset($_SESSION['old_input']);
    }
    ?>
</body>
</html>
