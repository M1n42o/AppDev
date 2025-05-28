<?php
// Include the config file
include '../config.php';

// Check if ID is provided
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Delete query
    $sql = "DELETE FROM categories WHERE id = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        // Execute the query
        if (mysqli_stmt_execute($stmt)) {
            // Redirect after deletion
            header("location: category.php?status=deleted");
            exit();
        } else {
            echo "Error deleting category.";
        }
    }
} else {
    echo "Invalid category ID.";
    exit();
}

// Close connection
mysqli_close($conn);



?>
