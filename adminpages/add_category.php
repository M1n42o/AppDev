<?php
// Include the config file for database connection
include '../config.php';

// Initialize variables
$category_name = $description = "";
$category_name_err = $description_err = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate category name
    if (empty(trim($_POST["category_name"]))) {
        $category_name_err = "Category name is required.";
    } else {
        $category_name = trim($_POST["category_name"]);
    }

    // Validate description
    if (empty(trim($_POST["description"]))) {
        $description_err = "Description cannot be empty.";
    } else {
        $description = trim($_POST["description"]);
    }

    // Insert data if no errors
    if (empty($category_name_err) && empty($description_err)) {
        $sql = "INSERT INTO categories (category_name, description) VALUES (?, ?)";
        
        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables
            mysqli_stmt_bind_param($stmt, "ss", $param_name, $param_desc);
            
            // Set parameters
            $param_name = $category_name;
            $param_desc = $description;

            // Execute statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to main page with success status
                header("location: category.php?status=success");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }
}

// Close connection
mysqli_close($conn);

// If there are errors or this is not a POST request, redirect back to the category page
if ($_SERVER["REQUEST_METHOD"] != "POST" || !empty($category_name_err) || !empty($description_err)) {
    header("location: category.php");
    exit();
}
?>
