<?php

// Get user information for sidebar
$id = $_SESSION['id'];
$sql = "SELECT login.id, login.username,  
        profile.firstname, profile.lastname
        FROM login
        INNER JOIN profile ON login.id = profile.login_id
        WHERE login.id = '$id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
?>

<!-- Sidebar Toggle Button -->
<button id="open-btn" class="open-btn" onclick="toggleNav()">☰</button>

<!-- Sidebar -->
<div id="mySidebar" class="sidebar">
    <img src="../uploads/kuru.png" class="profile-pic"> <!-- Profile Picture -->
    <h2 style="color:white; text-align:center;"><?php echo $row['username']; ?></h2>
    <!-----------------------------General Menu Section ------------------------------------------->
    <a href="javascript:void(0)" class="submenu-toggle" onclick="toggleSubmenu('generalSubmenu')">
        <i class='bx bx-menu'></i> Menu
    </a>
    <div id="generalSubmenu" class="submenu">
        <a href="/mixednuts/home.php"><i class='bx bx-home'></i> Home</a>
        <a href="/mixednuts/profile.php"><i class='bx bx-user'></i> Profile</a>
        <a href="/mixednuts/signout.php"><i class='bx bx-log-out'></i> Sign Out</a>
    </div>
    <a href="../userpages/productuser.php"><i class='bx bx-list-ul'></i> Products list</a>
    <a href="../userpages/analyticsuser.php"><i class='bx bx-calendar-alt'></i> Analytics</a>
    <!-------------------------------- Admin-Only Buttons------------------------------------------------>
    <?php if ($_SESSION['usertype_id'] == 1) { ?>
    <!-- Separate Product Management Menu (Only for Admin) -->
    <a href="#" class="menu-item" onclick="toggleSubmenu('productSubmenu')">
        <i class='bx bx-box'></i>Management
    </a>
    <!-- Submenu for Product Management -->
    <div id="productSubmenu" class="submenu">
        <a href="manage_users.php"><i class='bx bx-user-check'></i> Manage Users</a>
        <a href="manage_product.php"><i class='bx bx-plus'></i> Manage Product</a>
        <a href="analytics.php"><i class='bx bx-calendar-alt'></i> Manage Analytics</a>
        <a href="category.php"><i class='bx bx-plus'></i> Manage Category</a>
    </div>
    <?php } ?>
</div>
<style>
    /* Sidebar styles */
/* 🔹 Sidebar - Fixed & Initially Hidden */
.sidebar {
    height: 100vh;
    width: 0;
    position: fixed;
    top: 0;
    left: 0;
    background-color: rgba(51, 51, 51, 0.8); /* Dark background with 80% transparency */
    overflow-x: hidden;
    transition: 0.4s;
    padding-top: 20px;
    z-index: 1000;
}

.sidebar a {
    padding: 10px 15px;
    text-decoration: none;
    font-size: 18px;
    color: white;
    display: block;
    transition: 0.3s;
}

.sidebar a:hover {
    background-color: #575757;
}

.sidebar .close-btn {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 25px;
    cursor: pointer;
    color: white;
}

.sidebar .profile-pic {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background-color: #555;
    margin: 10px auto;
    display: block;
}

/* 🔹 Sidebar Toggle Button */
.open-btn {
    font-size: 20px;
    cursor: pointer;
    background-color: transparent;
    border: none;
    color: black;
    padding: 10px 15px;
    position: fixed;
    left: 10px;
    top: 10px;
    z-index: 1100;
}

.open-btn:hover {
    color: #555;
}

.submenu {
    display: none;
    background-color: #333;
    padding-left: 20px;
}

.submenu a {
    color: #ddd;
    padding: 8px 20px;
    text-decoration: none;
    display: block;
}

.submenu a:hover {
    background-color: #444;
}

/* Main content container */
.main-content {
    margin-left: 20px;
    padding: 20px;
    transition: margin-left 0.4s;
}
</style>
<!-- SideBar Function-->
<script>
    function toggleNav() {
        let sidebar = document.getElementById("mySidebar");
        let mainContent = document.querySelector(".main-content");
        
        if (sidebar.style.width === "250px") {
            sidebar.style.width = "0"; // Close sidebar
            mainContent.style.marginLeft = "20px"; // Reset main content margin
            document.body.style.overflow = "auto"; // Enable scrolling
        } else {
            sidebar.style.width = "250px"; // Open sidebar
            mainContent.style.marginLeft = "270px"; // Move main content to right
            document.body.style.overflow = "hidden"; // Disable scrolling
        }
    }

    // Close all submenus when the page loads
    document.addEventListener("DOMContentLoaded", function () {
        var submenus = document.querySelectorAll('.submenu');
        submenus.forEach(function (menu) {
            menu.style.display = "none";
        });
    });

    // Toggle submenu and close others
    function toggleSubmenu(submenuId) {
        var submenu = document.getElementById(submenuId);

        // Close all other submenus before opening the clicked one
        var allSubmenus = document.querySelectorAll('.submenu');
        allSubmenus.forEach(function (menu) {
            if (menu.id !== submenuId) {
                menu.style.display = "none";
            }
        });

        // Toggle the clicked submenu
        submenu.style.display = (submenu.style.display === "block") ? "none" : "block";
    }
</script>