<?php
include 'connection.php';

$query = "SELECT * FROM products";
$result = mysqli_query($conn, $query);

if (isset($_POST['add_to_cart'])){
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image = $_POST['image'];
    $quantity=1;

    $select_cart = mysqli_query($conn, "SELECT * FROM 'cart' WHERE name='$name' ");
    if (mysqli_num_rows($select_cart)>0){
        $message[] = 'Product already added in your cart';
    }else{
        $query = "INSERT INTO 'cart' ('name', 'price', 'image','quantity') VALUES ('$name', '$price', '$image','$quantity')";
        $insert_query = mysqli_query($conn, $query);
        $message[] = 'Product added in your cart';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <title>Product Catalog</title>
    <style>
        :root {
            --color-primary: #333333;
            --color-secondary: #666666;
            --color-background: #f5f5f5;
            --color-white: #ffffff;
            --font-primary: 'Playfair Display', serif;
            --font-secondary: 'Inter', sans-serif;
            --container-width: 1200px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-secondary);
            line-height: 1.6;
            color: var(--color-primary);
            background-color: var(--color-background);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .message {
            max-width: 1000px;
            text-align: center;
            text-transform: uppercase;
            color: #8B6E5D;
            background-color: #000;
            word-spacing: 3px;
            margin: 2rem auto;
            padding: 1rem .5rem;
            border-radius: 5px;
        }

        .message i {
            font-size: 1.5rem;
            cursor: pointer;
            float: right;
            color: var(--color);
        }

        h1 {
            text-align: center;
            font-family: var(--font-primary);
            font-size: 2rem;
            margin-bottom: 2rem;
            color: #333;
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

        .products-header {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .product-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            text-align: center;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .product-card h3 {
            font-size: 18px;
            margin: 10px 0;
        }

        .product-card p {
            color: #555;
            margin: 5px 0;
        }

        .product-card .btn {
            background-color: #333333;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 10px;
        }

        .product-card .btn:hover {
            background-color: #666666;
        }
        
        .footer {
            background-color: #333333;
            color: var(--color-white);
            padding: 4rem 0 2rem;
            margin-top: 40px;
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
            margin-bottom: 20px;
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
    <header>
        <header class="header">
            <nav class="nav">
                <a href="/">
                    <img src="logo.jpg" alt="BakersBakes Logo" class="logo">
                </a>
                <div class="nav-links">
                    <a href="dashboard.html">MAIN</a>
                    <a href="catalog.html" class="active">PRODUCTS</a>
                    <a href="faq.html">FAQ</a>
                    <a href="profile.html">PROFILE</a>
                    <a href="signup.php">SIGN UP / LOGIN</a>
                    <a href="cart.php" class="cart-icon">
                        <img src="https://raw.githubusercontent.com/twbs/icons/main/icons/bag-heart.svg" alt="Cart" class="cart-icon">
                    </a>
                </div>
            </nav>
        </header>

    <div class="container">
        <h1>Products</h1>

        <div class="products-grid">
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="product-card">';
                    echo '<img src="image/' . $row['image'] . '" alt="' . $row['name'] . '">';
                    echo '<h3>' . $row['name'] . '</h3>';
                    echo '<p>RM' . number_format($row['price'], 2) . '</p>';
                    echo '<button class="btn add-to-cart" name="add_to_cart" value="add to cart" >Add to Cart</button>';
                    echo '</div>';
                }
            } else {
                echo '<p>No products available.</p>';
            }
            ?>
        </div>
    </div>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <img src="logo.jpg" alt="BakersBakes Logo" class="footer-logo">
            </div>
            
            <div class="footer-section">
                <h3>Contact</h3>
                <p>📍 Kuching, Sarawak, Malaysia</p>
                <p>📞 +60 18 980 3060</p>
                <p>📧 <a href="mailto: info@bakersbakes.com">info@bakersbakes.com</a></p>
            </div>

            <div class="footer-section">
                <h3>Social Media</h3>
                <div class="social-links">
                    <a href="https://wa.me/60189803060" class="social-link">
                        <img src="https://raw.githubusercontent.com/twbs/icons/main/icons/whatsapp.svg" alt="Whatsapp">
                    </a>
                    <a href="https://www.instagram.com/iam.bakersbakes?igsh=em4wb2RrcHNiYnpi" class="social-link">
                        <img src="https://raw.githubusercontent.com/twbs/icons/main/icons/instagram.svg" alt="Instagram">
                    </a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>