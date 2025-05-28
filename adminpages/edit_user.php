<?php
// Include database configuration
include('../config.php');

// Get the user ID from URL
if (!isset($_GET['id'])) {
    die('Invalid request.');
}

$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM login WHERE id = $id");

if ($result->num_rows == 0) {
    die('User not found.');
}

$user = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $usertype_id = intval($_POST['usertype_id']);

    $conn->query("UPDATE login SET username = '$username', usertype_id = $usertype_id WHERE id = $id");
    header("Location: manage_users.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <link rel="stylesheet" type="text/css" href="../design/manage_users.css">
</head>
<body>

    <h2>Edit User</h2>

    <form method="POST" action="">
        <label for="username">Username:</label>
        <input type="text" name="username" value="<?= htmlspecialchars($user['username']); ?>" required>

        <label for="usertype_id">Role:</label>
        <select name="usertype_id">
            <option value="1" <?= ($user['usertype_id'] == 1) ? 'selected' : ''; ?>>Admin</option>
            <option value="2" <?= ($user['usertype_id'] == 2) ? 'selected' : ''; ?>>User</option>
        </select>

        <button type="submit" class="btn save">Save Changes</button>
        <a href="manage_users.php" class="btn cancel">Cancel</a>
    </form>

</body>
</html>
