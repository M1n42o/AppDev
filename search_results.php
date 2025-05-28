<?php
include 'config.php';

$query = isset($_GET['query']) ? trim($_GET['query']) : '';
$results = [];

if ($query !== '') {
    // Search in products
    $stmt = $conn->prepare("SELECT id, product_name AS title, category AS type FROM products WHERE product_name LIKE ?");
    $like = "%$query%";
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $productResult = $stmt->get_result();
    while ($row = $productResult->fetch_assoc()) {
        $results[] = $row;
    }
    $stmt->close();

    // Search in categories
    $stmt = $conn->prepare("SELECT id, category_name AS title, 'category' AS type FROM categories WHERE category_name LIKE ?");
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $categoryResult = $stmt->get_result();
    while ($row = $categoryResult->fetch_assoc()) {
        $results[] = $row;
    }
    $stmt->close();
}

header('Content-Type: application/json');
echo json_encode($results);
?>
