<?php
error_reporting(1);
session_start();

$conn = mysqli_connect('localhost', 'root', '', 'mixednuts');

// Login Logic
if (isset($_POST['loginBtn'])) {
        $username =  $conn->real_escape_string($_POST['username']);
        $password =  $conn->real_escape_string($_POST['password']);


        $sql = "SELECT login.id, login.username, login.usertype_id 
            FROM login 
            WHERE login.username='$username' AND login.password='$password'";		
            $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) === 1) {
		    $row = mysqli_fetch_assoc($result);
            $_SESSION['id'] = $row['id'];
            $_SESSION['usertype_id'] = $row['usertype_id'];
            echo "";
        } else {
            echo "<p>Invalid username or password</p>";
        }
}

// Signup Logic
if (isset($_POST['signupBtn'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);
    $firstname = $conn->real_escape_string($_POST['firstname']);
    $lastname = $conn->real_escape_string($_POST['lastname']);

    // Check if username already exists
    $check_query = $conn->prepare("SELECT id FROM login WHERE username = ?");
    $check_query->bind_param('s', $username);
    $check_query->execute();
    $check_query->store_result();

    if ($check_query->num_rows == 0) {
        // Insert into login table using prepared statement
        $stmt = $conn->prepare("INSERT INTO login (username, password) VALUES (?, ?)");
        $stmt->bind_param('ss', $username, $password);
        if ($stmt->execute()) {
            // Get the login_id
            $login_id = $conn->insert_id;

            // Insert into profile table using prepared statement
            $addrow = "INSERT INTO profile (login_id, firstname, lastname) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($addrow);
            $stmt->bind_param('iss', $login_id, $firstname, $lastname);
            if ($stmt->execute()) {
                echo json_encode(['success' => 'Profile saved successfully']);
            } else {
                echo json_encode(['error' => 'Error saving profile']);
            }

            $stmt->close();
        } else {
            echo json_encode(['error' => 'Error creating account']);
        }
    } else {
        echo json_encode(['error' => 'Account already exists']);
    }

    $check_query->close();
}


if(isset($_POST['save_button'])){
    $firstname =  $conn->real_escape_string($_POST['firstname']);
    $lastname = $conn->real_escape_string($_POST['lastname']);
    $bio = $conn->real_escape_string($_POST['bio']);
    $query="UPDATE profile SET firstname = '$firstname', lastname = '$lastname', bio = '$bio' WHERE login_id='$login_id'";
    mysqli_query($conn, $query);
    echo "profile saved";
}

?>
