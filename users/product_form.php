<?php
include 'connection.php';

if (isset($_POST['add_product'])) {
    $name = mysqli_real_escape_string($conn, $_POST['p_name']);
    $price = mysqli_real_escape_string($conn, $_POST['p_price']);
    $p_image = $_FILES['p_image']['name'];
    $p_image_temp_name = $_FILES['p_image']['tmp_name'];
    $p_image_folder = 'image/' . $p_image;

    $query = "INSERT INTO products (name, price, image) VALUES ('$name', '$price', '$p_image')";
    $insert_query = mysqli_query($conn, $query);

    if ($insert_query) {
        move_uploaded_file($p_image_temp_name, $p_image_folder);
        $message[] = 'Product added successfully';
        header('location: admin.php');
        exit();
    } else {
        $message[] = 'Product was not added successfully: ' . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Adding Products</title>
</head>
<body>
    <?php include 'header.php'; ?>
    <?php
    if (isset($message)) {
        foreach ($message as $msg) {
            echo '
                <div class="message">
                    <span>'.$msg.'<i class="bi bi-x" 
                    onclick="this.parentElement.style.display=\'none\'"></i></span>
                </div>
            ';
        }
    }
    ?>

    <div class="form">
        <form method="post" enctype="multipart/form-data">
            <h3>Add New Products</h3>
            <input type="text" name="p_name" placeholder="Enter product name" required>
            <input type="number" name="p_price" min="0" placeholder="Enter product price" required>
            <input type="file" name="p_image" accept="image/png, image/jpg, image/jpeg" required>
            <input type="submit" name="add_product" value="Add Product" class="btn">
        </form>
    </div>
</body>
</html>

