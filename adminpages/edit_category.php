<?php
// Include the config file
include '../config.php';

// Check if ID is provided
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Fetch category details
    $sql = "SELECT * FROM categories WHERE id = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        // Execute the query
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            
            // Check if record is found
            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_assoc($result);
                $category_name = $row['category_name'];
                $description = $row['description'];
            } else {
                echo "Category not found.";
                exit();
            }
        } else {
            echo "Error fetching category.";
            exit();
        }
    }
} else {
    echo "Invalid category ID.";
    exit();
}

// Handle form submission for update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_name = trim($_POST["category_name"]);
    $description = trim($_POST["description"]);
    
    // Update query
    $sql = "UPDATE categories SET category_name = ?, description = ? WHERE id = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssi", $category_name, $description, $id);
        
        if (mysqli_stmt_execute($stmt)) {
            header("location: category.php?status=updated");
            exit();
        } else {
            echo "Error updating category.";
        }
    }
}

// Close connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Category</title>
    <link rel="stylesheet" href="../design/category.css">
</head>
<body>

<div class="container">
    <h2>Edit Category</h2>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $id; ?>" method="post">
        <div class="form-group">
            <label>Category Name</label>
            <input type="text" name="category_name" value="<?php echo $category_name; ?>" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="4" class="form-control" required><?php echo $description; ?></textarea>
        </div>
        <div class="form-group">
            <input type="submit" value="Update Category" class="btn btn-submit">
            <a href="category.php" class="btn btn-cancel">Cancel</a>
        </div>
    </form>
</div>

</body>
</html>
