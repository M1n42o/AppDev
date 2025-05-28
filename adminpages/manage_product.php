<?php
include '../config.php'; // Keep this at the very top

// Handle Delete Action
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "DELETE FROM products WHERE id = $id";
    mysqli_query($conn, $sql);
    header("Location: manage_product.php");
    exit();
}

// Handle Add Product POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $stock = mysqli_real_escape_string($conn, $_POST['stock']);

    $sql = "INSERT INTO products (product_name, category, price, stock) 
            VALUES ('$product_name', '$category', '$price', '$stock')";

    if (mysqli_query($conn, $sql)) {
        $new_id = mysqli_insert_id($conn);
        header("Location: manage_product.php?added_id=" . $new_id);
        exit();
    }
}

// After processing all logic, now include the HTML parts
include('sidebar.php');

// Check for success redirect
$added_success = false;
$new_product = null;
if (isset($_GET['added_id'])) {
    $new_id = intval($_GET['added_id']);
    $result = mysqli_query($conn, "SELECT * FROM products WHERE id = $new_id");
    if ($result && mysqli_num_rows($result) > 0) {
        $new_product = mysqli_fetch_assoc($result);
        $added_success = true;
    }
}

// Fetch data for display
$result = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
$categories_result = mysqli_query($conn, "SELECT * FROM categories ORDER BY category_name ASC");
$categories_list = mysqli_fetch_all($categories_result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products</title>
    <link rel="stylesheet" href="../design/manage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>

<div class="container">
    <h2><i class="fas fa-boxes"></i> Manage Products</h2>

    <!-- Trigger Modal -->
    <button class="btn-add-product" onclick="document.getElementById('productModal').style.display='block'">
        <i class="fas fa-plus"></i> Add New Product
    </button>

    <!-- Products Table -->
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Price ($)</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($added_success && $new_product): ?>
                    <tr class="highlight">
                        <td><?= htmlspecialchars($new_product['id']) ?></td>
                        <td><?= htmlspecialchars($new_product['product_name']) ?></td>
                        <td><?= htmlspecialchars($new_product['category']) ?></td>
                        <td><?= number_format((float)$new_product['price'], 2) ?></td>
                        <td><?= htmlspecialchars($new_product['stock']) ?></td>
                        <td class="actions">
                            <a href='edit_product.php?id=<?= $new_product['id'] ?>' class='btn-edit'><i class='fas fa-edit'></i> Edit</a>
                            <a href='javascript:void(0)' onclick='confirmDelete(<?= $new_product['id'] ?>)' class='btn-delete'><i class='fas fa-trash-alt'></i> Delete</a>
                        </td>
                    </tr>
                <?php endif; ?>

                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <?php if ($added_success && $row['id'] == $new_product['id']) continue; ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars($row['product_name']) ?></td>
                            <td><?= htmlspecialchars($row['category']) ?></td>
                            <td><?= number_format($row['price'], 2) ?></td>
                            <td><?= htmlspecialchars($row['stock']) ?></td>
                            <td class="actions">
                                <a href='edit_product.php?id=<?= $row['id'] ?>' class='btn-edit'><i class='fas fa-edit'></i> Edit</a>
                                <a href='javascript:void(0)' onclick='confirmDelete(<?= $row['id'] ?>)' class='btn-delete'><i class='fas fa-trash-alt'></i> Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan='6' class='no-data'>No products found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ✅ Modal moved OUTSIDE container -->
<div id="productModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('productModal').style.display='none'">&times;</span>
        <h2>Add New Product</h2>

        <form id="addProductForm" method="POST" action="manage_product.php">
            <input type="hidden" name="add_product" value="1">
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

            <label>Price ($):</label>
            <input type="number" name="price" step="0.01" required>

            <label>Stock:</label>
            <input type="number" name="stock" required>

            <button type="submit" class="btn-add" <?= count($categories_list) == 0 ? 'disabled' : '' ?>>
                Add Product
            </button>
        </form>
    </div>
</div>

<script>
    window.onclick = function(event) {
        const modal = document.getElementById('productModal');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    function confirmDelete(id) {
        if (confirm('⚠️ Are you sure you want to delete this product?')) {
            window.location.href = 'manage_product.php?delete=' + id;
        }
    }
</script>

</body>
</html>

<?php mysqli_close($conn); ?>
