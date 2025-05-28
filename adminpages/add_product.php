<?php
include '../config.php';

// Fetch available categories
$categories_result = mysqli_query($conn, "SELECT * FROM categories ORDER BY category_name ASC");
$categories_list = mysqli_fetch_all($categories_result, MYSQLI_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST['product_name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $sql = "INSERT INTO products (product_name, category, price, stock) 
            VALUES ('$product_name', '$category', '$price', '$stock')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Product added successfully!'); window.location='manage_product.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Products</title>
    <link rel="stylesheet" href="../design/manage.css">
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 10;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fff;
            
            margin: 8% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 400px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: red;
        }

        .add-product-btn {
            margin: 20px;
            padding: 10px 20px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .add-product-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>

    <button class="add-product-btn" onclick="document.getElementById('productModal').style.display='block'">+ Add Product</button>

    <div id="productModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('productModal').style.display='none'">&times;</span>
            <h2>Add New Product</h2>

            <form method="POST" action="add_product.php">
                <label>Product Name:</label>
                <input type="text" name="product_name" required>

                <label>Category:</label>
                <?php if (count($categories_list) > 0): ?>
                    <select name="category" required>
                        <option value="">-- Select Category --</option>
                        <?php foreach ($categories_list as $cat): ?>
                            <option value="<?= htmlspecialchars($cat['category_name']) ?>">
                                <?= htmlspecialchars($cat['category_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                <?php else: ?>
                    <p style="color:red;">⚠️ No categories found. Please add categories first.</p>
                <?php endif; ?>

                <label>Price:</label>
                <input type="number" name="price" step="0.01" required>

                <label>Stock:</label>
                <input type="number" name="stock" required>

                <input type="submit" class="btn-add" value="Add Product" <?= count($categories_list) == 0 ? 'disabled' : '' ?>>
            </form>
        </div>
    </div>

    <script>
        // Close modal on click outside of modal content
        window.onclick = function(event) {
            const modal = document.getElementById('productModal');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>

</body>
</html>
