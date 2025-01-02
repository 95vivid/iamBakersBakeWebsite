<?php
include 'connection.php';

if (isset($_POST['update-btn'])){
    $update_value = $_POST['update_quantity'];
    $update_id = $_POST['update_quantity_id'];

    $update_query = mysqli_query($conn, "UPDATE 'cart' SET quantity='$update_value' WHERE id='$update_id'")  or die ('query_failed');
    if($update_query){
        header('location:cart.php');
    }
}

if (isset($_GET['remove'])){
    $remove_id = $_GET['remove'];
    mysqli_query($conn, "DELETE FROM 'cart' WHERE id='$remove_id'");
    header('location:cart.php');
}

if (isset($_GET['delete_all'])){
    mysqli_query($conn, "DELETE FROM 'cart'");
    header('location:cart.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - BakersBakes</title>
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

        h1 {
            text-align: center;
            font-family: var(--font-primary);
            font-size: 2rem;
            margin-bottom: 2rem;
            color: #333;
        }

        .cart-container table{
            background-color: var(--color-white);
            width: 70vw;
            margin: 0 auto;
            text-align: center;
            border-radius: 10px;
        }

        .cart-container table tr{
            padding: 1rem 0;
            box-shadow: var(--outer-shadow);
        }

        .cart-container table tr td{
            margin: 1rem;
            border-bottom: 1px solid #333;
            font-size: 1rem;
            color: #555;
        }

        .cart-container input[type='number']{
            padding: .5rem 1rem;
            width: 5rem;
        }

        .cart-container input[type='submit']{
            width: 80px;
            border-radius: 5px;
            padding: .5rem;
            cursor: pointer;
            color: orange;
            text-transform: uppercase;
        }

        .cart-container .delete-btn,
        ..cart-container .option-btn{
            font-size: 15px;
            padding: .5rem 1rem;
            border-radius: 5px;
        }

        .cart-container .delete-btn:hover{
            box-shadow: var(--inner-shadow);
        }

        .table-bottom{
            padding: 1rem 2rem;
        }

        .table-bottom tr td{
            border-bottom: none;
        }

        .cart-container img{
            width: 100px;
            border-radius: 50%;
            pa.padding: .5rem;
            box-shadow: var(--inner-shadow);
        }

        .cart-container .checkout-btn{
            text-align: center;
            margin-top: 2rem;
        }

        .checkout-btn a{
            width: auto;
            margin: 0 auto;
            background-color: olive;
            color: #fff;
            border-radius: 5px;
            padding: .5rem 2rem;
        }

        .checkout-btn a.disabled{
            pointer-events: none;
            opacity: .5;
            user-select: none;
            background-color: red;
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
                <img src="logo.jpg" alt="BakersBakes Logo" class="logo">
            </a>
            <div class="nav-links">
                <a href="dashboard.html">MAIN</a>
                <a href="catalog.html">PRODUCTS</a>
                <a href="faq.html">FAQ</a>
                <a href="profile.html">PROFILE</a>
                <a href="signup.php">SIGN UP / LOGIN</a>
                <a href="cart.php" class="active">
                    <img src="https://raw.githubusercontent.com/twbs/icons/main/icons/bag-heart.svg" alt="Cart" class="cart-icon">
                </a>
            </div>
        </nav>
    </header>

    <div class="cart-container">
        <h1>Shopping Cart</h1>
        <table>
            <thead>
                <th>image</th>
                <th>name</th>
                <th>price</th>
                <th>quantity</th>
                <th>total price</th>
                <th>action</th>
            </thead>
            <tbody>
                <?php
                    $select_cart = mysqli_query($conn, "SELECT * FROM cart ");
                    $grand_total=0;
                    if(mysqli_num_rows($select_cart)>0) {
                        while($fetch_cart=mysqli_fetch_assoc($select_cart)){

                    ?>
                    <tr>
                        <td><img src="image/<?php echo $fetch_cart['image']; ?>"></td>
                        <td><?php echo $fetch_cart['name']; ?></td>
                        <td>$<?php echo $fetch_cart['price']; ?>/-</td>
                        <td class="quantity">
                            <form method="POST">
                                <input type="hidden" name="update_quantity_id" value="<?php echo $fetch_cart['id']; ?>">
                                <input type="number" min="1" name="update_quantity" value="<?php echo $fetch_cart['quantity']; ?>">
                                <input type="submit" name="update_btn" value="update">
                            </form>
                        </td>
                        <td>$<?php echo $sub_total = number_format($fetch_cart['price']*$fetch_cart['quantity']); ?></td>
                        <td><a href="cart.php?remove=<?php echo $fetch_cart['id']; ?>" onclick="return confirm('Remove item from cart');"> class="delete-btn">remove</a></td>
                    </tr>
                    <?php
                        $grand_total+=$sub_total;
                        }
                    }
                    ?>
                    <tr class="table-botom">
                        <td><a href="viewCatalog.php" class="option-btn">continue shopping</a></td>
                        <td colspan="3"><h1>total amount payable</h1></td>
                        <td style="font-weight: bold;">$<?php echo $grand_total; ?></td>
                        <td><a href="cart.php?delete_all" onclick="return confirm('are you sure you want to delete all item from cart');" class="delete-btn">delete all</a></td>
                    </tr>
            </tbody>
        </table>
        <div class="checkout-btn">
            <a href="checkout.php" class="btn <?=($grand_total>1)?'':'disabled'?>" >proceed to checkout</a>

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