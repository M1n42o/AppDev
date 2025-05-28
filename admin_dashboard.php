<?php
error_reporting(1);
include 'sidebar.php';
include 'navbar.php';
session_start();

$latest_items = mysqli_query($conn, "
    SELECT product_name, stock
    FROM products
    ORDER BY id DESC 
    LIMIT 5
");



if (!isset($_SESSION['id']) || $_SESSION['usertype_id'] != 1) {
    header("Location: 401.php");
    exit;
}

$conn = mysqli_connect('localhost', 'root', '', 'mixednuts');

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch counts
$category_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM categories"))['total'];
$user_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM login"))['total'];
$product_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM products"))['total'];
$low_stock_items = mysqli_query($conn, "
    SELECT product_name, stock, max_stock 
    FROM products
    WHERE stock <= (max_stock * 0.2)
");




$success_message = $_SESSION['success_message'] ?? '';
$error_message = $_SESSION['error_message'] ?? '';
unset($_SESSION['success_message'], $_SESSION['error_message']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="design/home.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        .stat-box {
            cursor: pointer;
            transition: 0.3s;
        }
        .stat-box:hover {
            background-color: #f0f0f0;
            transform: scale(1.02);
        }
    </style>
</head>
<body>

    <div class="dashboard-container">
        <div class="dashboard-summary">
            <div class="stat-box" onclick="location.href='adminpages/category.php'">
                <h3>Total Categories</h3>
                <p><?= $category_count ?></p>
            </div>

            <div class="stat-box" onclick="location.href='adminpages/manage_users.php'">
                <h3>Total Users</h3>
                <p><?= $user_count ?></p>
            </div>
            <div class="stat-box">
                <h3>Total Products</h3>
                <p><?= $product_count ?></p>
            </div>

            <div class="stat-box" onclick="location.href='adminpages/manage_product.php'">

                <h3>New Entries</h3>
                <p><?= mysqli_num_rows($latest_items) ?> Recent</p>
                <ul style="margin-top: 10px; font-size: 14px; padding-left: 20px; color: #333;">
                    <?php while ($item = mysqli_fetch_assoc($latest_items)): ?>
                        <li><?= htmlspecialchars($item['product_name']) ?> — <?= $item['stock'] ?> left</li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="background"></div>
<div class="dashboard-alerts" style="margin: 30px auto; max-width: 800px;">
    <?php if (mysqli_num_rows($low_stock_items) > 0): ?>
        <div style="background-color: #fff3cd; border: 1px solid #ffeeba; padding: 20px; border-radius: 8px; color: #856404;">
            <h4>⚠️ Low Stock Alert</h4>
            <ul style="list-style-type: disc; padding-left: 20px; margin-top: 10px;">
                <?php while ($item = mysqli_fetch_assoc($low_stock_items)): ?>
                    <li><strong><?= htmlspecialchars($item['product_name']) ?></strong> — <?= $item['stock'] ?> left</li>
                <?php endwhile; ?>
            </ul>
        </div>
    <?php else: ?>
        <div style="background-color: #d4edda; border: 1px solid #c3e6cb; padding: 20px; border-radius: 8px; color: #155724;">
            ✅ All products are sufficiently stocked.
        </div>
    <?php endif; ?>
</div>

</body>
</html>
