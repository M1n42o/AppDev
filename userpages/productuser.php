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
// Pagination settings
$limit = 10; // Number of products per page
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

// Count total products
$total_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM products");
$total_row = mysqli_fetch_assoc($total_result);
$total_pages = ceil($total_row['total'] / $limit);

// Fetch paginated products
$result = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC LIMIT $limit OFFSET $offset");
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
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan='6' class='no-data'>No products found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <!-- Pagination Controls -->
<div class="pagination" style="text-align:center; margin-top:20px;">
    <?php if ($total_pages > 1): ?>
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?= $i ?>" style="
                display:inline-block;
                margin: 0 5px;
                padding: 8px 12px;
                background-color: <?= $i == $page ? '#4CAF50' : '#eee' ?>;
                color: <?= $i == $page ? 'white' : '#333' ?>;
                border-radius: 5px;
                text-decoration: none;
            ">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    <?php endif; ?>
</div>

</div>
</div>
</script>
</body>
</html>

<?php mysqli_close($conn); ?>
