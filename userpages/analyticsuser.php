<?php
// Include database connection
include '../config.php';
include 'sidebar.php';
// SQL query to fetch data from monthly_sales
// Pagination setup
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Fetch paginated sales
$sql = "SELECT id, item_name, quantity_sold, total, date 
        FROM monthly_sales 
        ORDER BY date DESC 
        LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

// Get total number of sales
$total_result = $conn->query("SELECT COUNT(*) as total FROM monthly_sales");
$total_items = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_items / $limit);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Monthly Sales Analytics</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color:rgb(170, 170, 172);
            margin: 0;
            padding: 20px;
        }
        h2 {
            text-align: center;
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
        .container {
            text-align: center;
        }
    </style>
</head>
<body>


<h2>Monthly Sales Analytics</h2>

<div class="container">
    <?php
    if ($result->num_rows > 0) {
        echo '<table>';
        echo '<tr><th>ID</th><th>Item Name</th><th>Quantity Sold</th><th>Total ($)</th><th>Date</th></tr>';
        
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row['id'] . '</td>';
            echo '<td>' . $row['item_name'] . '</td>';
            echo '<td>' . $row['quantity_sold'] . '</td>';
            echo '<td>$' . number_format($row['total'], 2) . '</td>';
            echo '<td>' . date('Y-m-d', strtotime($row['date'])) . '</td>';
            echo '</tr>';
        }
        echo '</table>';
        echo '<div style="margin-top: 20px;">';
        for ($i = 1; $i <= $total_pages; $i++) {
            $isActive = $i == $page ? 'font-weight: bold; background-color: #4CAF50; color: white;' : 'background-color: #e0e0e0;';
            echo "<a href='?page=$i' style='padding: 8px 14px; margin: 5px; text-decoration: none; border-radius: 4px; $isActive display: inline-block;'>$i</a>";
        }
        echo '</div>';

    } else {
        echo '<p>No data found in the table.</p>';
    }

    // Close connection
    $conn->close();
    ?>
</div>

</body>
</html>
