<?php
// Include database connection
include '../config.php';

// Handle Delete Action
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM products WHERE id=$id";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Product deleted successfully.'); window.location='manage_product.php';</script>";
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}

// Fetch Products
$sql = "SELECT * FROM products ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="design/manage.css">
</head>

<body>

    <!-- Page Header -->
    <div class="container">
        <h2>Manage Products</h2>

        <!-- Add New Product Button -->
        <a href="add_product.php" class="btn-add">Add New Product</a>

        <!-- Products Table -->
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
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                                <td>" . $row['id'] . "</td>
                                <td>" . htmlspecialchars($row['product_name']) . "</td>
                                <td>" . htmlspecialchars($row['category']) . "</td>
                                <td>" . htmlspecialchars($row['price']) . "</td>
                                <td>" . htmlspecialchars($row['stock']) . "</td>
                                <td class='actions'>
                                    <a href='edit_product.php?id=" . $row['id'] . "' class='btn-edit'>Edit</a>
                                    <a href='manage_product.php?delete=" . $row['id'] . "' onclick='return confirm(\"Are you sure you want to delete this product?\");' class='btn-delete'>Delete</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='no-data'>No products found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>

</html>

<?php
// Close connection
mysqli_close($conn);
?>
