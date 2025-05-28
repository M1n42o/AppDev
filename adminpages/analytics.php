<?php
// Include database connection
include '../config.php';

// Handle form submission to add new sale
if (isset($_POST['add_sale'])) {
    $item_name = $_POST['item_name'];
    $quantity_sold = (int)$_POST['quantity_sold'];
    $total = (float)$_POST['total'];
    $date = $_POST['date'];

    // Check if sale already exists for this item on this date
    $check_sql = "SELECT id, quantity_sold, total FROM monthly_sales WHERE item_name = ? AND date = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ss", $item_name, $date);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Merge with existing sale
        $existing = $result->fetch_assoc();
        $new_quantity = $existing['quantity_sold'] + $quantity_sold;
        $new_total = $existing['total'] + $total;

        $update_sql = "UPDATE monthly_sales SET quantity_sold = ?, total = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("idi", $new_quantity, $new_total, $existing['id']);
        $update_stmt->execute();
        $update_stmt->close();
    } else {
        // Insert new sale
        $insert_sql = "INSERT INTO monthly_sales (item_name, quantity_sold, total, date) VALUES (?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("sids", $item_name, $quantity_sold, $total, $date);
        $insert_stmt->execute();
        $insert_stmt->close();
    }

    $stmt->close();

    header("Location: analytics.php");
    exit();
}


// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM monthly_sales WHERE id = $delete_id";
    if ($conn->query($delete_sql) === TRUE) {
        echo "<p style='color: green; text-align: center;'>Sale deleted successfully!</p>";
    } else {
        echo "<p style='color: red; text-align: center;'>Error deleting record: " . $conn->error . "</p>";
    }
}

// Fetch all sales data
$sql = "SELECT id, item_name, quantity_sold, total, date FROM monthly_sales";
$result = $conn->query($sql);

// Get the most sold item for the current month
$top_sql = "
    SELECT item_name, SUM(quantity_sold) AS total_quantity 
    FROM monthly_sales 
    WHERE MONTH(date) = MONTH(CURRENT_DATE()) AND YEAR(date) = YEAR(CURRENT_DATE())
    GROUP BY item_name 
    ORDER BY total_quantity DESC 
    LIMIT 1
";

$top_result = $conn->query($top_sql);
$top_item = null;
if ($top_result->num_rows > 0) {
    $top_item = $top_result->fetch_assoc();
}


?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Monthly Sales Analytics</title>
    <style>
        .back-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease;
        }
        .back-btn:hover {
            background-color: #45a049;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }
        h2, h3 {
            text-align: center;
        }
        form {
            text-align: center;
            margin-bottom: 20px;
        }
        input, button {
            padding: 10px;
            margin: 5px;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .delete-btn {
            color: red;
            text-decoration: none;
            padding: 5px 10px;
            border: 1px solid red;
            border-radius: 5px;
        }
        .delete-btn:hover {
            background-color: red;
            color: white;
        }
    </style>
</head>
<body>

<h2>Monthly Sales Analytics</h2>

<!-- Most Sold Item Display -->
<?php if ($top_item): ?>
    <h3 style="color: #333;">
        📈 Top-Selling Item This Month: <span style="color: green;"><?= htmlspecialchars($top_item['item_name']) ?></span> 
        (<?= $top_item['total_quantity'] ?> sold)
    </h3>
<?php else: ?>
    <h3 style="color: #999;">No sales data for this month yet.</h3>
<?php endif; ?>

<!-- Add Sale Form -->
<form method="POST" action="analytics.php">
    <input type="text" name="item_name" placeholder="Item Name" required>
    <input type="number" name="quantity_sold" placeholder="Quantity Sold" required>
    <input type="number" step="0.01" name="total" placeholder="Total ($)" required>
    <input type="date" name="date" required>
    <button type="submit" name="add_sale">Add Sale</button>
</form>

<!-- Sales Data Table -->
<div class="container">
    <?php
    if ($result->num_rows > 0) {
        echo '<table>';
        echo '<tr><th>ID</th><th>Item Name</th><th>Quantity Sold</th><th>Total ($)</th><th>Date</th><th>Action</th></tr>';
        
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row['id'] . '</td>';
            echo '<td>' . htmlspecialchars($row['item_name']) . '</td>';
            echo '<td>' . $row['quantity_sold'] . '</td>';
            echo '<td>$' . number_format($row['total'], 2) . '</td>';
            echo '<td>' . date('Y-m-d', strtotime($row['date'])) . '</td>';
            echo '<td><a class="delete-btn" href="?delete_id=' . $row['id'] . '" onclick="return confirm(\'Are you sure you want to delete this sale?\')">Delete</a></td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo '<p style="text-align: center;">No data found in the table.</p>';
    }
    include('sidebar.php');
    // Close connection
    $conn->close();
    ?>
</div>

</body>
</html>