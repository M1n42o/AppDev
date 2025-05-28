<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login Page</title>
    <link rel="stylesheet" href="design/index.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <!-- NAVBAR CREATION -->
   <header class="header">
    <nav class="navbar">
        <a href="index.php">Home</a>
        <a href="navbar/portfolio.php">Portfolio</a>
        <a href="navbar/about.php">About</a>
    </nav>

   </header>
    <!-- LOGIN FORM CREATION -->
    <div class="background"></div>
        <div class="container">
            <div class="item">
                <h2 class="logo"></i>Rico's Phone Accessories</h2>
                <div class="text-item">
                    <h2>Welcome! <br><span>
                    To our Accesories Shop
                    </span></h2>
                    <p>An inventory shop efficiently tracks and manages stock levels, ensuring products are available for customers</p>
                    <div class="social-icon">
                        <a href="https://www.facebook.com/"><i class='bx bxl-facebook'></i></a>
                    </div>
                </div>
            </div>

    <!----------------------------------------------------------------------------------------------->
  
            <!-- LOGIN AREA -->
            <div class="login-section">
                <div class="form-box login">
                    <form>

                        <h2>Sign In</h2>
                        <div class="input-box">
                            <span class="icon"><i class='bx bxs-user'></i></span>
                            <input class="center-input" type="text"  id="username" required >
                            <label >Username</label>
                        </div>

                        <div class="input-box">
                            <span class="icon"><i class='bx bxs-lock-alt' ></i></span>
                            <input class="center-input" type="password" id="password" required >
                            <label>Password</label>
                        </div>

                        
                        <!-- BUTTON AREA -->
                        <p id="log-message"></p>
                        <div class="button" style="display: inline;">
                            <input type="submit" class="btn" value="Sign In" name="signIn" id="loginBtn" onclick="return login()">
                        </div>

                        <div class="create-account">
                            <a href="signup.php" id="signup">Don't have an account?</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

<!----------------------------------------------------------------------------------------------->

    <script src="js/main.js"></script>
</body>

</html>