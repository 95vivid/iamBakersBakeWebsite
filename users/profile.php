<?php
// Start the session to access session variables
session_start();

// Include the database connection file
include("db.php");

// Check if the user is logged in by checking the session for user_id
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id']; // Get the user ID from the session

    // Prepare a SQL query to fetch the user's data from the database using prepared statements
    $sql = "SELECT * FROM users WHERE id = ?";
    
    // Create a prepared statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind the parameter
        $stmt->bind_param("i", $user_id);
        
        // Execute the query
        $stmt->execute();
        
        // Get the result of the query
        $result = $stmt->get_result();
        
        // Check if the query returned any results
        if ($result->num_rows > 0) {
            // Fetch the user data from the result
            $row = $result->fetch_assoc();
            $user_fullname = $row['fullname']; // User's full name
            $user_username = $row['username']; // User's username
            $user_email = $row['email']; // User's email
        } else {
            echo "No user found.";
            exit();
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        echo "Error preparing the SQL statement.";
        exit();
    }
} else {
    // If the user is not logged in, redirect to the login page
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - BakersBakes</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital@0;1&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Add this script to pass PHP variables to JavaScript -->
    <script>
        const userData = {
            fullName: "<?php echo htmlspecialchars($user_fullname); ?>",
            username: "<?php echo htmlspecialchars($user_username); ?>",
            email: "<?php echo htmlspecialchars($user_email); ?>"
        };
    </script>
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
            background-color: var(--color-white);
            padding: 1rem 2rem;
            border-bottom: 1px solid #eee;
        }

        .nav {
            max-width: 1200px;
            margin: 0 auto;
            /*padding: 0 2rem;*/
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo img {
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

        .profile-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: var(--color-white);
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .profile-title {
            font-family: var(--font-primary);
            font-size: 2rem;
            margin-bottom: 2rem;
        }

        .profile-tabs {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            background-color: #f5f5f5;
            padding: 0.5rem;
            border-radius: 4px;
        }

        .tab-btn {
            padding: 0.5rem 2rem;
            border: none;
            background: none;
            cursor: pointer;
            font-family: var(--font-secondary);
            font-size: 0.9rem;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .tab-btn.active {
            background-color: var(--color-white);
            font-weight: 500;
        }

        .profile-actions {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            border-bottom: 1px solid #eee;
            padding-bottom: 1rem;
        }

        .action-btn {
            padding: 0.5rem 1.5rem;
            border: none;
            background: none;
            cursor: pointer;
            font-size: 0.9rem;
            color: var(--color-secondary);
            transition: color 0.3s ease;
        }

        .action-btn.active {
            color: var(--color-primary);
            font-weight: 500;
            border-bottom: 2px solid var(--color-primary);
        }

        .profile-form {
            max-width: 480px;
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
            transition: border-color 0.3s ease;
        }

        .form-group input[readonly] {
            background-color: #f5f5f5;
            cursor: not-allowed;
        }

        .form-group input.editable:focus {
            border-color: var(--color-primary);
            outline: none;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .orders-list {
            background-color: #f5f5f5;
            padding: 1rem;
            border-radius: 4px;
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

        .update-btn {
            background-color: var(--color-primary);
            color: var(--color-white);
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-family: var(--font-secondary);
            margin-top: 1rem;
            display: none;
            transition: background-color 0.3s ease;
        }

        .update-btn:hover {
            background-color: #444;
        }

        .update-btn.show {
            display: block;
        }

        @media (max-width: 768px) {
            .nav-links {
                gap: 1rem;
            }

            .profile-container {
                margin: 1rem;
                padding: 1rem;
            }

            .footer-content {
                grid-template-columns: 1fr;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <nav class="nav">
            <a href="/" class="logo">
                <img src="img/logo.jpg" alt="BakersBakes Logo">
            </a>
            <div class="nav-links">
                <a href="dashboard.html">MAIN</a>
                <a href="catalog.html">PRODUCTS</a>
                <a href="faq.html">FAQ</a>
                <a href="profile.html" class="active">PROFILE</a>
                <a href="signup.php">SIGN UP / LOGIN</a>
                <a href="cart.php">
                    <img src="https://raw.githubusercontent.com/twbs/icons/main/icons/bag-heart.svg" alt="Cart" class="cart-icon">
                </a>
            </div>
        </nav>
    </header>

    <main class="profile-container">
        <h1 class="profile-title">My Profile</h1>
        
        <div class="profile-tabs">
            <button class="tab-btn active" data-tab="account">ACCOUNT</button>
            <button class="tab-btn" data-tab="orders">ORDERS</button>
        </div>

        <div class="profile-content">
            <div class="tab-content active" id="account">
                <div class="profile-actions">
                    <button class="action-btn active">VIEW</button>
                    <button class="action-btn">UPDATE</button>
                    <button class="action-btn">DELETE</button>
                </div>

                <form method="POST" class="profile-form" id="profileForm">
                    <div class="form-group">
                        <label for="fullName">Full Name</label>
                        <input type="text" id="fullName" name="fullName" readonly>
                    </div>

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" readonly>
                    </div>

                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" readonly>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" readonly>
                    </div>

                    <button type="submit" class="update-btn" id="updateBtn">Save Changes</button>
                    <button type="button" class="update-btn" id="deleteBtn" style="background-color: #dc3545; margin-left: 10px; display: none;">Delete Account</button>
                </form>
            </div>

            <div class="tab-content" id="orders">
                <div class="orders-list">
                    <p>No orders found.</p>
                </div>
            </div>
        </div>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Populate form fields with user data
            document.getElementById('fullName').value = userData.fullName;
            document.getElementById('username').value = userData.username;
            document.getElementById('email').value = userData.email;
            
            // Tab switching functionality
            const tabBtns = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');

            tabBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    tabBtns.forEach(b => b.classList.remove('active'));
                    tabContents.forEach(c => c.classList.remove('active'));
                    btn.classList.add('active');
                    document.getElementById(btn.dataset.tab).classList.add('active');
                });
            });

            // Profile actions functionality
const actionBtns = document.querySelectorAll('.action-btn');
const updateBtn = document.getElementById('updateBtn');
const deleteBtn = document.getElementById('deleteBtn');
const form = document.getElementById('profileForm');

actionBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        actionBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        
        if (btn.innerText === "UPDATE") {
            // Enable input fields for editing
            document.getElementById('fullName').removeAttribute('readonly');
            document.getElementById('username').removeAttribute('readonly');
            document.getElementById('email').removeAttribute('readonly');
            document.getElementById('password').removeAttribute('readonly');
            updateBtn.classList.add('show'); // Show the Save Changes button
            deleteBtn.style.display = 'none'; // Hide delete button
        } else if (btn.innerText === "VIEW") {
            // Reset form to original values
            document.getElementById('fullName').value = userData.fullName;
            document.getElementById('username').value = userData.username;
            document.getElementById('email').value = userData.email;
            document.getElementById('password').value = '';
            
            // Disable input fields
            document.getElementById('fullName').setAttribute('readonly', true);
            document.getElementById('username').setAttribute('readonly', true);
            document.getElementById('email').setAttribute('readonly', true);
            document.getElementById('password').setAttribute('readonly', true);
            updateBtn.classList.remove('show'); // Hide the Save Changes button
            deleteBtn.style.display = 'none'; // Hide delete button
        } else if (btn.innerText === "DELETE") {
            // Disable input fields
            document.getElementById('fullName').setAttribute('readonly', true);
            document.getElementById('username').setAttribute('readonly', true);
            document.getElementById('email').setAttribute('readonly', true);
            document.getElementById('password').setAttribute('readonly', true);
            updateBtn.classList.remove('show'); // Hide the Save Changes button
            deleteBtn.style.display = 'inline-block'; // Show delete button
        }
    });
});

// Handle delete account
deleteBtn.addEventListener('click', function() {
    // Show confirmation dialog
    const confirmDelete = confirm('Are you sure you want to delete your account? This action cannot be undone.');
    
    if (confirmDelete) {
        // Send delete request
        fetch('deleteprofile.php', {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Account deleted successfully');
                // Redirect to login page
                window.location.href = 'login.php';
            } else {
                alert(data.message || 'Failed to delete account');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the account');
        });
    }
});

        });
    </script>
</body>
</html>
