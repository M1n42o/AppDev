<?php
// Include the config file for database connection
include '../config.php';

// Include the sidebar before any HTML output
include 'sidebar.php';

// Query to fetch categories
$sql = "SELECT id, category_name, description FROM categories";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>TrioDevProject</title>
    <link rel="stylesheet" href="../design/category.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Category Management</h2>

        <!-- Add Category Button - Modified to open modal -->
        <button class="btn btn-primary mb-3" onclick="openModal()">Add New Category</button>

        <!-- Category Table -->
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Category Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Check if rows exist
                if (mysqli_num_rows($result) > 0) {
                    // Output data for each row
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . htmlspecialchars($row['category_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                        echo "<td>
                                <a href='edit_category.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm'>Edit</a>
                                <a href='delete_category.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this category?\");'>Delete</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' class='text-center'>No categories found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal for Adding Category -->
    <div id="categoryModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Add New Category</h3>
                <button class="close-modal" onclick="closeModal()">&times;</button>
            </div>
            
            <!-- Form to Add Category -->
            <form action="add_category.php" method="post" class="form-container" id="categoryForm">
                <!-- Category Name Input -->
                <div class="form-group">
                    <label for="category_name">Category Name</label>
                    <input type="text" id="category_name" name="category_name" class="form-control">
                    <span class="error" id="category_name_err"></span>
                </div>

                <!-- Description Input -->
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="4" class="form-control"></textarea>
                    <span class="error" id="description_err"></span>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn btn-submit">Add Category</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript for Modal Functionality -->
    <script>
        // JavaScript for Modal Functionality
        function openModal() {
            document.getElementById('categoryModal').classList.add('show');
            document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
        }
        
        function closeModal() {
            document.getElementById('categoryModal').classList.remove('show');
            document.body.style.overflow = ''; // Restore scrolling
            
            // Clear form fields and errors
            document.getElementById('categoryForm').reset();
            document.getElementById('category_name_err').textContent = '';
            document.getElementById('description_err').textContent = '';
        }
        
        // Form validation on the client side
        document.getElementById('categoryForm').addEventListener('submit', function(event) {
            let valid = true;
            const categoryName = document.getElementById('category_name').value.trim();
            const description = document.getElementById('description').value.trim();
            
            // Clear previous errors
            document.getElementById('category_name_err').textContent = '';
            document.getElementById('description_err').textContent = '';
            
            // Validate category name
            if (categoryName === '') {
                document.getElementById('category_name_err').textContent = 'Category name is required.';
                valid = false;
            }
            
            // Validate description
            if (description === '') {
                document.getElementById('description_err').textContent = 'Description cannot be empty.';
                valid = false;
            }
            
            if (!valid) {
                event.preventDefault(); // Prevent form submission if validation fails
            }
        });
        
        // Close modal when clicking outside the modal content
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('categoryModal');
            if (event.target === modal) {
                closeModal();
            }
        });

        // Auto-close success messages after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const successMsg = document.querySelector('.success-msg');
                if (successMsg) {
                    successMsg.style.opacity = '0';
                    setTimeout(function() {
                        successMsg.remove();
                    }, 500);
                }
            }, 5000);
        });
    </script>

    <!-- Display success messages -->
    <?php
    if (isset($_GET['status'])) {
        if ($_GET['status'] == 'updated') {
            echo "<div class='success-msg'>✅ Category updated successfully!</div>";
        } elseif ($_GET['status'] == 'deleted') {
            echo "<div class='success-msg'>🗑️ Category deleted successfully!</div>";
        } elseif ($_GET['status'] == 'success') {
            echo "<div class='success-msg'>✅ Category added successfully!</div>";
        }
    }
    ?>

    <!-- jQuery and other scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Close the connection
mysqli_close($conn);
?>
