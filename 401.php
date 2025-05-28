<?php
// Set the HTTP status code to 401 Unauthorized
http_response_code(401);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>401 Unauthorized</title>
    <style>
        /* Background style with blur */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background: url(uploads/401.png) no-repeat center center;
            background-size: cover;
            
            position: relative;
        }
        .container {
            text-align: center;
        }
        h1 {
            font-size: 3em;
            margin: 0.5em 0;
            color: #ff6f61;
        }
        p {
            font-size: 1.2em;
            margin: 0.5em 0;
        }
        a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
        .icon {
            font-size: 5em;
            color: #ff6f61;
        }
        
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">🚫</div>
        <h1>401 Unauthorized</h1>
        <p>Sorry, you are not authorized to view this page.</p>
        <p><a href="index.php">Return to Login</a> or <a href="home.php">Go to Home</a></p>
    </div>
</body>
</html>