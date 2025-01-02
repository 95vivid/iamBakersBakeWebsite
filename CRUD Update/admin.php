<?php
    session_start();
    include 'connection.php';

    // Delete product
    if (isset($_GET['delete'])) {
        $delete_id = $_GET['delete'];
        $delete_query = mysqli_query($conn, "DELETE FROM products WHERE id = $delete_id") or die('query failed');
        if ($delete_query) {
            $_SESSION['message'] = 'Product deleted successfully';
        } else {
            $_SESSION['message'] = 'Product could not be deleted';
        }
        header('location: admin.php#product-list');
        exit();
    }

    // Update product
    if (isset($_POST['update_product'])) {
        $update_p_id = $_POST['update_p_id'];
        $update_p_name = mysqli_real_escape_string($conn, $_POST['update_p_name']);
        $update_p_price = mysqli_real_escape_string($conn, $_POST['update_p_price']);
        $update_p_img = $_FILES['update_p_image']['name'];
        $update_p_img_tmp_name = $_FILES['update_p_image']['tmp_name'];
        $update_p_folder = 'image/'.$update_p_img;

        $update_query = "UPDATE products SET 
            name='$update_p_name', 
            price='$update_p_price'";
        
        if ($update_p_img != '') {
            $update_query .= ", image='$update_p_img'";
        }
        
        $update_query .= " WHERE id = '$update_p_id'";

        $update_result = mysqli_query($conn, $update_query) or die('query failed');

        if ($update_result) {
            if ($update_p_img != '') {
                move_uploaded_file($update_p_img_tmp_name, $update_p_folder);
            }
            $_SESSION['message'] = 'Product has been updated successfully';
        } else {
            $_SESSION['message'] = 'Product could not be updated';
        }
        header('location: admin.php#product-list');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        .show-product table img {
            border-radius: 50%;
            box-shadow: var(--outer-shadow);
            width: 50px;
            height: 50px;
            object-fit: cover;
        }
        .edit-form {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(236, 242, 249, 0.95);
            padding: 2rem;
            display: none;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            z-index: 1100;
        }

        .edit-form form {
            width: 300px;
            padding: 2rem;
            background: var(--bg-color);
            border-radius: 10px;
            box-shadow: var(--outer-shadow);
        }

        .edit-form form img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin: 1rem auto;
            display: block;
            border-radius: 50%;
            box-shadow: var(--outer-shadow);
        }

        .edit-form form input {
            margin: 1rem 0;
            padding: 0.5rem 1rem;
        }

        .edit-form form .btn-update,
        .edit-form form .btn-cancel {
            width: 48%;
            padding: 0.5rem 1rem;
            margin: 0.5rem;
            cursor: pointer;
            border-radius: 5px;
        }

        .edit-form form .btn-update {
            background: var(--color);
            color: white;
        }

        .edit-form form .btn-cancel {
            background: #dc3545;
            color: white;
        }
        .add-product-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: var(--color);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .add-product-btn:hover {
            background-color: #45a049;
        }
    </style>
    <title>Admin Panel - Products</title>
</head>
<body>
    <header>
        <div class="flex">
            <img src="bakers.png" alt="Baker's Logo" class="header-logo">
            <?php include 'header.php'; ?>
        </div>
    </header>
    
    <div class="add-product-button" style="text-align: right; margin: 20px;">
        <a href="product_form.php" class="btn add-product-btn">
            <i class="bi bi-plus-circle"></i> Add Product
        </a>
    </div>

    <?php
    if (isset($_SESSION['message'])) {
        echo '
            <div class="message">
                <span>'.$_SESSION['message'].'<i class="bi bi-x"
                    onclick="this.parentElement.style.display=\'none\'"></i></span>
            </div>
        ';
        unset($_SESSION['message']);
    }
    ?>

    <section id="product-list" class="show-product">
        <table>
            <thead>
                <tr>
                    <th>Product Image</th>
                    <th>Product Name</th>
                    <th>Product Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $select_products = mysqli_query($conn, "SELECT * FROM products") or die('query failed');
                if (mysqli_num_rows($select_products) > 0) {
                    while ($row = mysqli_fetch_assoc($select_products)) {
                ?>
                <tr>
                    <td><img src="image/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>"></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['price']; ?>/-</td>
                    <td>
                        <a href="admin.php?delete=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this product?')">
                            <i class="bi bi-trash"></i> Delete
                        </a>
                        <a href="admin.php?edit=<?php echo $row['id']; ?>" class="edit-btn">
                            <i class="bi bi-pencil"></i> Update
                        </a>
                    </td>
                </tr>
                <?php
                    }
                } else {
                    echo '<tr><td colspan="4">No products found</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </section>

    <section class="edit-form">
        <?php
        if (isset($_GET['edit'])) {
            $edit_id = $_GET['edit'];
            $edit_query = mysqli_query($conn, "SELECT * FROM products WHERE id = $edit_id") or die("query failed");

            if (mysqli_num_rows($edit_query) > 0) {
                $fetch_edit = mysqli_fetch_assoc($edit_query);
        ?>
        <form method="post" enctype="multipart/form-data">
            <h3>Update Product</h3>
            <img src="image/<?php echo $fetch_edit['image']; ?>" alt="Product Image">
            <input type="hidden" name="update_p_id" value="<?php echo $fetch_edit['id']; ?>">
            <input type="text" name="update_p_name" value="<?php echo $fetch_edit['name']; ?>" required>
            <input type="number" name="update_p_price" value="<?php echo $fetch_edit['price']; ?>" min="0" required>
            <input type="file" name="update_p_image" accept="image/png, image/jpg, image/jpeg">
            <div style="display: flex; justify-content: space-between;">
                <input type="submit" name="update_product" value="Update Product" class="btn-update">
                <input type="button" value="Cancel" class="btn-cancel" id="close-edit">
            </div>
        </form>
        <?php
            }
            echo "<script>document.querySelector('.edit-form').style.display = 'flex';</script>";
        }
        ?>
    </section>
    
    <footer class="footer">
        <div class="footer-content">
            <div>
                <img src="bakers.png" alt="Baker's Logo" class="footer-logo">
            </div>
            <div>
                <h3>Information</h3>
                <ul class="footer-links">
                    <li><a href="#">Main</a></li>
                    <li><a href="#">Products</a></li>
                    
                </ul>
            </div>
            <div>
                <h3>Contacts</h3>
                <div class="footer-contact">
                    <p>1234 Sample Street</p>
                    <p>Austin Texas 78704</p>
                    <p>+60 18-980 3060</p>
                    <p>irasyahirah0104@gmail.com</a></p>
                </div>
            </div>
            <div>
                <h3>Social Media</h3>
                <div class="footer-social">
                    <a href="#"><i class="bi bi-facebook"></i></a>
                    <a href="#"><i class="bi bi-instagram"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2021 All Rights Reserved</p>
        </div>
    </footer>

    <script type="text/javascript">
        document.getElementById('close-edit').addEventListener('click', () => {
            document.querySelector('.edit-form').style.display = 'none';
            window.location.href = 'admin.php#product-list';
        });
    </script>

</body>
</html>
