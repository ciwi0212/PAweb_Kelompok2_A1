<?php
 require 'koneksi.php';

 session_start();
 $login = isset($_SESSION["login"]) && $_SESSION["login"] === true;
 $roles = isset($_SESSION["roles"]) ? $_SESSION["roles"] : '';

 if (isset($_POST["submit"])) {
    if (!empty($_POST["email"]) && !empty($_POST["password"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];
        $sql = "SELECT * FROM data_users WHERE email='$email'"; 
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($password, $user['password'])) {
                $_SESSION['email'] = $email;
                $_SESSION["login"] = true;
                $_SESSION['roles'] = $user['roles'];
                if ($user['roles'] === 'admin') {
                    header("Location: CRUD_admin.php");
                    exit;
                } else if ($user['roles'] === 'user') {
                    header("Location: index.php");
                    exit;
                }
            } else {
                echo "<script>alert('Password salah');</script>";

            }
        } else {
            echo "<script>alert('Email tidak ditemukan');</script>";
        }
    } else {
        echo "<script>alert('Mohon isi email dan password');</script>";
    }
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Landing Page</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap');
      
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        body {
            background: url("pictures/image.png");
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: rgba(40, 40, 40, 0.4);
        }

        .navbar {
            position: fixed;
            top: 0;
            display: flex;
            justify-content: space-around;
            width: 100%;
            height: 100px;
            line-height: 100px;
            background: linear-gradient(rgba(40, 40, 40, 0.7), transparent);
            z-index: 100;
        }

        .nav-logo p {
            color: white;
            font-size: 22px;
            font-weight: 500;
        }

        .nav-menu ul {
            display: flex;
        }

        .nav-menu ul li {
            list-style-type: none;
        }

        .nav-menu ul li .link{
            font-weight: 500;
            color: white;
            text-decoration: none;
            margin: 0 23px;
            padding-bottom: 15px;
        }

        .link:hover, .active{
            border-bottom: 2px solid #fff;
        }

        .nav-button .btn {
            width: 115px;
            height: 30px;
            font-weight: 500;
            background: rgba(255, 255, 255, 0.4);
            border: none;
            border-radius: 30px;
            cursor: pointer;
            transition: .3s ease;
        }

        #signupbtn {
            margin-left: 14px;
        }

        .btn.white-btn{
            background: rgba(255, 255, 255, 0.7);
        }

        .btn:hover{
            background: rgba(255, 255, 255, 0.3);
        }

        .btn.btn.white-btn:hover{
            background: rgba(255, 255, 255, 0.5);
        }

        .hamburger{
            display: none;
        }

        .header{
            text-align: center;
            position: absolute;
            top: 300px;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .header h1{
            color: #fff;
            text-align: center;
            font-family: 'Noto Serif JP';
            font-size: 50px;
            line-height: 1.3;
            margin-bottom: 40px;
        }

        .content-header{
            position: absolute;
            transform: translate(-50%, -50%);
            text-align: center;
            top: 390px;
            left: 50%;
        }

        .content-header p {
            color: #fff;
            line-height: 1.5;
        }

        .nav-logo{
            display: flex;
            align-items: center;
            filter: brightness(0) saturate(100%) invert(100%);
        }

        .nav-logo p{
            font-size: 24px;
            margin: 0;
            margin-right: 5px;
        }

        #logo {
            width: 60px;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <nav class="navbar">
            <div class="nav-logo">
                <p>Weather Report</p>
                <img src="pictures/logo-removebg-preview.png" id="logo">
                
            </div>

            <div class="nav-menu">
                <ul>
                    <li><a href="#" class="link active">Home</a></li>
                    <li><a href="about.php" class="link">About Us</a></li>
                    <li><a href="user.php" class="link">Weather</a></li>
                </ul>
            </div>

            <div class="nav-button">
            <?php if ($login): ?>
                <button class="btn white-btn" id="logoutbtn" onclick="window.location.href = 'logout.php'">Logout</button>
            <?php else: ?>
                <button class="btn white-btn" id="loginbtn" onclick="window.location.href = 'login.php'">Login</button>
                <button class="btn" id="signupbtn">Sign Up</button>
            <?php endif; ?>
            </div>

            <div class="hamburger">
                <i class="fa-solid fa-bars"></i>
            </div>

            <div class="header">
                <h1>Welcome to Our Weather Website</h1>
            </div>

            <div class="content-header">
                <p>
                    From scorching heat to heavy rain, our service provides daily weather forecasts to meet all your needs. Enjoy the convenience of planning your activities, travels, and daily routines with the latest weather information.
                </p>
            </div>
        </nav>
    </div>
    <script>
        document.getElementById('loginbtn').addEventListener('click', function() {
            window.location.href = 'login.php';
        });

        document.getElementById('signupbtn').addEventListener('click', function() {
            window.location.href = 'register.php';
        });

    </script>
</body>
</html>