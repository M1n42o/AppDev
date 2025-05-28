<?php
// Start session and include configuration
session_start();
include('../config.php');

// Check if user is logged in and is admin
if (!isset($_SESSION['id']) || $_SESSION['usertype_id'] != 1) {
    header("Location: ../login.php");
    exit();
}

// Handle user deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM login WHERE id = $id");
    header("Location: manage_users.php");
    exit();
}

// Fetch all users from the login table
$users_result = $conn->query("SELECT id, username, usertype_id FROM login ORDER BY usertype_id ASC, username ASC");

if (!$users_result) {
    die("Error fetching users: " . $conn->error);
}

// Map usertype_id to roles
function getRole($usertype_id) {
    return ($usertype_id == 1) ? 'Admin' : 'User';
}


include('sidebar.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <link rel="stylesheet" type="text/css" href="../design/manage_users.css">
    <!-- Include Boxicons for icons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>


    <!-- Main Content -->
    <div class="main-content">
        <h2>Manage Users</h2>

        <!-- User List Table -->
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>

            <?php
            // Check if there are any users
            if ($users_result->num_rows > 0):
                while ($user = $users_result->fetch_assoc()):
            ?>
            <tr>
                <td><?= $user['id']; ?></td>
                <td><?= htmlspecialchars($user['username']); ?></td>
                <td><?= getRole($user['usertype_id']); ?></td>
                <td>
                    <a href="edit_user.php?id=<?= $user['id']; ?>" class="btn edit">Edit</a>
                    <a href="manage_users.php?delete=<?= $user['id']; ?>" onclick="return confirm('Are you sure you want to delete this user?')" class="btn delete">Delete</a>
                </td>
            </tr>
            <?php
                endwhile;
            else:
            ?>
            <tr>
                <td colspan="4">No users found.</td>
            </tr>
            <?php endif; ?>
        </table>
    </div>
</body>
</html>