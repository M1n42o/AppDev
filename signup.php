<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sign Up</title>
    <link rel="stylesheet" href="design/index.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script type="text/javascript" src="js/main.js"></script>
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
            <h2 class="logo"></i>Sign Up</h2>
            <div class="text-item">
                <h2> <br><span>
                Please Create An Account
                </span></h2>
                <p>Signing up allows users to create an account and access personalized features.</p>
                <div class="social-icon">
                    <a href="https://www.facebook.com/"><i class='bx bxl-facebook'></i></a>
                </div>
            </div>
        </div>

    <!-- LOGIN AREA -->
        <div class="login-section">
            <div class="form-box login">
                <form>

                    <h2>Sign Up</h2>
                    <div class="input-box">
                        <span class="icon"><i class='bx bxs-user'></i></span>
                        <input class="overlay-input" type="text" id="username_su" required>
                        <label>Username</label>
                    </div>

                    <div class="input-box">
                        <span class="icon"><i class='bx bxs-user'></i></span>
                        <input class="overlay-input" type="text" id="firstname" required>
                        <label>First name</label>
                    </div>

                    <div class="input-box">
                        <span class="icon"><i class='bx bxs-user'></i></span>
                        <input class="overlay-input" type="text" id="lastname" required>
                        <label>Last name</label>
                    </div>

                    <div class="input-box">
                        <span class="icon"><i class='bx bxs-lock-alt' ></i></span>
                        <input class="overlay-input" type="password" id="password_su" required>
                        <label>Password</label>
                    </div>

                    <div class="overlay-btn">
                        <input id="signupBtn" class="btn" type="submit" value="Sign Up" onclick="return signup()">
                    </div>

                    <div class="create-account">
                    <a href="index.php" id="signup">Already have an account?</a>
                    </div>

                </form>
            </div>
        </div>
    <script src="js/main.js"></script>
    </body>
</html>














