<?php
include 'connection.php';

$query = "SELECT * FROM products";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Product Catalog</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .container {
            padding: 20px 40px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
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
            background-color: #7a5230;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 10px;
        }
        .product-card .btn:hover {
            background-color: #5a3820;
        }
        footer {
            background-color: #333;
            color: #fff;
            padding: 20px 40px;
            margin-top: 40px;
        }
        footer .footer-content {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 50px;
        }
        footer .footer-content div {
            flex: 1;
            min-width: 200px;
        }
        footer .footer-content h3 {
            margin-bottom: 10px;
        }
        footer .footer-content a {
            color: #fff;
            text-decoration: none;
        }
        footer .footer-content a:hover {
            text-decoration: underline;
        }
        footer .logo img {
            width: 180px; /* Adjust the size as needed */
            height: auto;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

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
                    echo '<button class="btn add-to-cart">Add to Cart</button>';
                    echo '</div>';
                }
            } else {
                echo '<p>No products available.</p>';
            }
            ?>
        </div>
    </div>

    <footer>
        <div class="footer-content">
            <div class="logo">
                <img src="bakers.png" alt="Logo">
            </div>
            <div class="footer-info">
                <h3>Information</h3>
                
                    <a href="main.php">Main</a>
                    <a href="viewCatalog.php">Products</a>
                
            </div>
            <div class="footer-contact">
                <h3>Contacts</h3>
                <p>Kuching, Sarawak, Malaysia</p>
                <p>+60 18-980 3060</p>
                <p>info@bakersbakes.com</p>
            </div>
            <div class="footer-social">
                <h3>Social Media</h3>
                <a href="#"><i class="bi bi-whatsapp"></i></a>
                <a href="#"><i class="bi bi-instagram"></i></a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2023 All Rights Reserved</p>
        </div>
    </footer>

</body>
</html>
