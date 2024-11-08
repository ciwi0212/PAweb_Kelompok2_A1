<?php
require 'koneksi.php';
session_start();
if (isset($_POST["submit"])) {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $cpassword = $_POST["cpassword"];
    $role = 'user';
    if ($password === $cpassword) {
        $sqlregis = "SELECT * FROM data_users WHERE username= '$username'";
        $checkResult = mysqli_query($conn, $sqlregis);
        $password = password_hash($password, PASSWORD_DEFAULT);
        if (mysqli_num_rows($checkResult) > 0) {
            echo "
            <script>
                alert('Username sudah ada');
                document.location.href = 'register.php';
            </script>
            ";
        } else {
            $query = "INSERT INTO data_users VALUES(DEFAULT, '$email','$username','$password', '$role')";
            if (mysqli_query($conn, $query)) {
                echo "
                <script>
                    alert('Berhasil Registrasi!');
                    document.location.href = 'login.php';
                </script>
                ";
            } else {
                echo "
                <script>
                    alert('gagal registrasi!');
                    document.location.href = 'register.php';
                </script>
                ";
            }

        }
    } else {
        echo "
        <script>
            alert('Password tidak sesuai');
            document.location.href = 'register.php';
        </script>
        ";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Register Page</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        .garis{
            display: block;
            width: 25px;
            height: 3px;
            margin: 5px auto;
            background-color: #D6EFD8;
        }

        body {
            background: url("pictures/image.png");
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .container-navbar {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: rgba(39, 39, 39, 0.4);
        }

        .navbar {
            position: fixed;
            top: 0;
            display: flex;
            justify-content: space-around;
            width: 100%;
            height: 100px;
            line-height: 100px;
            background: linear-gradient(rgba(39, 39, 39, 0.6), transparent);
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

        .nav-menu ul li .link {
            font-weight: 500;
            color: white;
            text-decoration: none;
            margin: 0 23px;
            padding-bottom: 15px;
        }

        .link:hover,
        .active {
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

        .btn.white-btn {
            background: rgba(255, 255, 255, 0.7);
        }

        .btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .btn.btn.white-btn:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        .hamburger{
            display: none;  
            cursor: pointer;
            flex-direction: column;
            z-index: 10;
            padding-top: 40px;
        }
        .hamburger .garis {
            width: 25px;
            height: 3px;
            background-color: #fff;
            margin: 2px 0; 
            transition: transform 0.4s ease-in-out, opacity 0.3s ease-in-out; 
        }

        .nav-logo {
            display: flex;
            align-items: center;
            filter: brightness(0) saturate(100%) invert(100%);
        }

        .nav-logo p {
            font-size: 24px;
            margin: 0;
            margin-right: 5px;
        }

        #logo {
            width: 60px;
            height: auto;
        }

        .form-box {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 512px;
            height: 420px;
            overflow: hidden;
            z-index: 2;
        }
        
        .container-register{
            width: 500px;
            display: flex;
            flex-direction: column;
            transition: .5s ease-in-out;
        }

        .top span{
            color: #fff;
            font-size: small;
            padding: 10px 0;
            display: flex;
            justify-content: center;
        }

        .top span a{
            font-weight: 500;
            color: #fff;
            margin-left: 5px;
        }

        header {
            color: #fff;
            font-size: 30px;
            text-align: center;
            padding: 10px 0 30px 0;
        }

        .two-forms {
            display: flex;
            gap: 10px;
        }

        .input-field{
            font-size: 15px;
            background: rgba(255, 255, 255, 0.3);
            color: white;
            height: 50px;
            width: 100%;
            padding: 0 10px 0 45px;
            border-radius: 26px;
            border: none;
            transition: .2s ease;
        }

        .input-field:hover, input-field:focus{
            background: rgba(255, 255, 255, 0.25);
        }

        ::-webkit-input-placeholder{
            color: white;
        }

        .input i {
            position: relative;
            top: -35px;
            left: 17px;
            color: white;
        }

        .submit {
            font-size: 15px;
            font-weight: 500;
            color: black;
            height: 45px;
            width: 100%;
            border: none;
            border-radius: 26px;
            outline: none;
            cursor: pointer;
            background: rgba(255, 255, 255, 0.7);
            transition: .3s ease-in-out;
        }

        .submit:hover{
            background: rgba(255, 255, 255, 0.5);
            box-shadow: 1px 5px 7px 1px rgba(0, 0, 0, 0.2);
        }

        .nav-menu .link {
            color: black;
            text-decoration: none;
            padding: 10px;
        }

        .nav-menu .link:hover {
            color: white; 
            transition: all 0.3s ease-in-out; 
            opacity: 0.8;  
            transform: scale(1.1);  
        }

        .nav-menu .link:active {
            color: yellow;
        }

        @media (max-width: 768px) {
        .nav-menu {
        display: none;
        flex-direction: column;
        position: absolute;
        top: 60px;
        left: 0;
        width: 100%;
        background-color: #333;
        text-align: center;
        align-items: center;
        }

        .nav-menu.active {
            display: flex;
        }

        .nav-menu ul {
            flex-direction: column;
            gap: 1rem;
            padding: 1rem 0;
        }

        .nav-button {
            display: none;
        }

        .hamburger {
            display: flex;
            align-items: center;
            
        }
        .hamburger.active .garis:nth-child(1) {
            transform: translateY(7.2px) rotate(45deg) scale(1.1);
        }

        .hamburger.active .garis:nth-child(2) {
            opacity: 0;
        }

        .hamburger.active .garis:nth-child(3) {
            transform: translateY(-7.2px) rotate(-45deg) scale(1.1);
        }
    }
    </style>
</head>

<body>
    <div class="container-navbar">
        <nav class="navbar">
            <div class="nav-logo">
                <p>Weather Report</p>
                <img src="pictures/logo-removebg-preview.png" id="logo">

            </div>
            <div class="nav-menu">
                <ul>
                    <li><a href="#" class="link active">Home</a></li>
                    <li><a href="about.php" class="link">About Us</a></li>
                </ul>
            </div>

            <div class="nav-button">
                <button class="btn white-btn" id="loginbtn" onclick="window.location.href = 'login.php'">Login</button>
            </div>

            <div class="hamburger">
                <span class="garis"></span>
                <span class="garis"></span>
                <span class="garis"></span>
            </div>
        </nav>


    <div class="form-box">
        <div class="container-register" id="register">
            <div class="top">
                <span>Already have an account? <a href="login.php">Login</a></span>
                <header>Sign Up</header>
            </div>
            <form action="register.php" method="post">
            <div class="two-forms">
                <div class="input">
                    <input type="email" class="input-field" placeholder="email" name= "email">
                    <i class="fa-regular fa-envelope"></i>
                </div>

                <div class="input">
                    <input type="text" class="input-field" placeholder="username" name = "username">
                    <i class="fa-regular fa-user"></i>
                </div>
            </div>

                <div class="input">
                    <input type="password" class="input-field" placeholder="password" name ="password">
                    <i class="fa-solid fa-lock"></i>
                </div>

                <div class="input">
                    <input type="password" class="input-field" placeholder=" confirm password" name ="cpassword">
                    <i class="fa-solid fa-lock"></i>
                </div>

                <div class="input">
                    <input type="submit" class="submit" value="submit" name="submit">
                </div>
            </form>

            
        </div>
    </div>
</div>
<script src="script.js"></script>
</body>
</html>