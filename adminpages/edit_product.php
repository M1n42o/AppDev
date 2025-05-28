<?php
// Include database connection
include '../config.php';

// Check if ID is set
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM products WHERE id=$id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if (!$row) {
        echo "<script>alert('Product not found!'); window.location='manage_product.php';</script>";
        exit();
    }
}

// Handle Update Action
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST['product_name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $update_sql = "UPDATE products SET 
                    product_name='$product_name', 
                    category='$category', 
                    price='$price', 
                    stock='$stock' 
                    WHERE id=$id";

    if (mysqli_query($conn, $update_sql)) {
        echo "<script>alert('Product updated successfully!'); window.location='manage_product.php';</script>";
    } else {
        echo "Error updating product: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Product</title>
    <link rel="stylesheet" href="../design/manage.css">
</head>
<header class="header">
    <nav class="navbar">
        <a href="manage_product.php" class="btn-back"><i class="fas fa-arrow-left"></i> Back</a>
    </nav>
</header>
<body>

    <div class="container">
        <h2>Edit Product</h2>

        <form method="POST" action="edit_product.php?id=<?php echo $id; ?>">
            <label>Product Name:</label>
            <input type="text" name="product_name" value="<?php echo htmlspecialchars($row['product_name']); ?>" required>

            <label>Category:</label>
            <input type="text" name="category" value="<?php echo htmlspecialchars($row['category']); ?>" required>

            <label>Price:</label>
            <input type="number" name="price" step="0.01" value="<?php echo htmlspecialchars($row['price']); ?>" required>

            <label>Stock:</label>
            <input type="number" name="stock" value="<?php echo htmlspecialchars($row['stock']); ?>" required>

            <input type="submit" class="btn-add" value="Update Product">
        </form>
    </div>

</body>

</html>

<?php
// Close connection
mysqli_close($conn);
?>
