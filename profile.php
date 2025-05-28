<?php
error_reporting(1);
session_start();
include("config.php");

$id = $_SESSION['id'];
$sql = "SELECT login.id, login.username,  
        profile.firstname, profile.lastname
        FROM login
        INNER JOIN profile ON login_id = profile.login_id
        WHERE login.id = '$id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
  

if (isset($_POST['save-button'])) {
    $firstname = trim($conn->real_escape_string($_POST['firstname']));
    $lastname = trim($conn->real_escape_string($_POST['lastname']));
    $uploaded_image = $image; // Default to existing image if no new file is uploaded

    // Handle file upload only if a new file is provided
    if (isset($_FILES['profile-pic']) && $_FILES['profile-pic']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['profile-pic']['tmp_name'];
        $file_name = basename($_FILES['profile-pic']['name']);
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        $upload_dir = 'uploads/';

        // Validate file type and size
        if (in_array($file_ext, $allowed_ext) && $_FILES['profile-pic']['size'] <= 2 * 1024 * 1024) { // 2MB limit
            $new_file_name = uniqid('profile_', true) . '.' . $file_ext;
            $upload_path = $upload_dir . $new_file_name;

            // Check if the file already exists in the uploads directory
            if (move_uploaded_file($file_tmp, $upload_path)) {
                // Delete the old file if it exists and is not the default
                if ($image !== 'kuru.png' && file_exists($upload_dir . $image)) {
                    unlink($upload_dir . $image);
                }
                $uploaded_image = $new_file_name; // Update with new image path
            } else {
                echo "Failed to upload the file.";
                exit;
            }
        } else {
            echo "Invalid file type or file size exceeds the 2MB limit.";
            exit;
        }
    }

    // Update the database
    if ($firstname && $lastname) {
        mysqli_query($conn, "UPDATE profile SET firstname = '$firstname', lastname = '$lastname', image = '$image' WHERE login_id = '$id'");
        header("Location: profile.php");
        exit;
    }
}
include('sidebar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="design/profiledesign.css">
    <title>Profile</title>
</head>
<body>
    

    <div class="profile-container">
        <script type="text/javascript" src="main.js"></script>
        <form action="profile.php" method="POST" enctype="multipart/form-data">
            <!-- Profile Picture -->
            <label for="pic-file">
                <img src="uploads/<?php echo (!empty($image) && file_exists("uploads/$image")) ? $image : 'kuru.png'; ?>" alt="Profile Picture" id="img-display">
            </label>
            
            
            <!-- Username -->
            <h1>@<?php echo $row['username']; ?></h1>
            
            <!-- Full Name -->
            <input type="text" id="firstname" name="firstname" value="<?php echo $row['firstname']; ?>" required><br>
            <input type="text" id="lastname" name="lastname" value="<?php echo $row['lastname']; ?>" required><br>
            
            <!-- Save Button -->
            <input id="save-button" type="submit" name="save-button" value="Save">
        </form>
    </div>

    <div class="background"></div>
</body>
</html>


