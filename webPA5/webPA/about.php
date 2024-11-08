<?php
session_start();
$backLink = 'index.php'; 
if (!empty($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        $backLink = "CRUD_admin.php";
    } elseif ($_SESSION['role'] === 'user') {
        $backLink = "index.php";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f7fcfe;
        }

        .heading {
            width: 90%;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            text-align: center;
            margin: 20px auto;
            margin-bottom: 0;
        }

        .heading h1 {
            font-size: 50px;
            color: #000;
            margin-bottom: 25px;
            position: relative;
        }

        .heading h1::after {
            content: "";
            position: absolute;
            width: 100%;
            height: 4px;
            display: block;
            margin: 0 auto;
            background-color: #93c9e2;
        }

        .heading p {
            font-size: 18px;
            color: #666;
        }

        .container {
            width: 90%;
            margin: 0 auto;
            padding: 10px 20px;
        }

        .about {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .about-image {
            flex: 1;
            margin-right: 40px;
            overflow: hidden;
        }

        .about-image img {
            max-width: 100%;
            height: auto;
            display: block;
            transition: 0.5s ease;
        }

        .about-image:hover img {
            transform: scale(1.2);
        }

        .about-content {
            flex: 1;
        }

        .about-content h2 {
            font-size: 23px;
            margin-bottom: 15px;
            color: #333;
        }

        .about-content p {
            font-size: 18px;
            line-height: 1.5;
            color: #666;
        }

        .read-more {
            display: inline-block;
            padding: 10px 20px;
            color: #fff;
            font-size: 18px;
            text-decoration: none;
            border-radius: 25px;
            background-color: #93c9e2;
            transition: 0.3s ease;
            margin-top: 15px;
        }

        .read-more:hover {
            background-color: #73a9c2;
        }

        .main {
            width: 100%;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #93c9e2;
        }

        .profile-card {
            position: relative;
            width: 250px;
            height: 250px;
            background: #fff;
            padding: 30px;
            border-radius: 50%;
            box-shadow: 0 0 22px #3336;
            transition: 0.6s;
            margin: 0 25px;
        }

        .profile-card:hover {
            border-radius: 10px;
            height: 260px;
        }

        .profile-card .img {
            position: relative;
            width: 100%;
            height: 100%;
            transition: 0.6s;
            z-index: 99;
            cursor: pointer;
        }

        .img img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            box-shadow: 0 0 22px #3336;
            transition: 0.6s;
        }

        .profile-card:hover .img {
            transform: translateY(-90px);
        }

        .profile-card:hover img {
            border-radius: 10px;
        }

        .caption {
            text-align: center;
            transform: translateY(-80px);
            opacity: 0;
            transition: 0.6s;
        }

        .profile-card:hover .caption {
            opacity: 1;
        }

        .caption h3 {
            font-size: 25px;
        }

        .caption h4 {
            font-size: 17px;
            color: black;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="heading">
        <h1>About Us</h1>
        <p>Selamat datang di situs cuaca terpercaya Anda. Di dunia yang penuh dengan ketidakpastian cuaca, kami memahami betapa pentingnya memiliki akses yang cepat dan tepat ke data cuaca yang relevan. 
            Kami hadir untuk menjawab kebutuhan Anda akan informasi cuaca yang dapat diandalkan, membantu Anda mempersiapkan diri dan melindungi orang-orang yang Anda cintai. 
            Kami berkomitmen untuk memberikan informasi cuaca terkini, akurat, dan dapat diandalkan untuk membantu Anda merencanakan aktivitas sehari-hari dengan lebih baik.</p>
    </div>

    <div class="container">
        <section class="about">
            <div class="about-image">
                <img src="pictures/kumpulanlangit.png" alt="Sky Collection">
            </div>
            <div class="about-content">
                <h2>Visi dan Misi Kami</h2>
                <p>Kami berkomitmen menjadi sumber utama informasi cuaca terpercaya, membantu masyarakat mempersiapkan diri menghadapi cuaca kapanpun dan di manapun. Kami menyediakan prakiraan cuaca harian, analisis tren iklim, serta peringatan dini untuk cuaca ekstrem. 
                    Visi kami adalah memastikan keselamatan dan kenyamanan pengguna melalui layanan yang andal, cepat, dan inovatif.</p>
                <a href="<?php echo $backLink; ?>" class="read-more">Back</a>
            </div>
        </section>
    </div>

    <div class="main">
        <div class="profile-card">
            <div class="img">
                <img src="pictures/cellia.png" alt="Profile Picture">
            </div>
            <div class="caption">
                <h3>Cellia Auzia Nugraha</h3>
                <h4>2309106005</h4>
            </div>
        </div>

        <div class="profile-card">
            <div class="img">
                <img src="pictures/me.png" alt="Profile Picture">
            </div>
            <div class="caption">
                <h3>Oktaria Indi Cahyani</h3>
                <h4>2309106015</h4>
            </div>
        </div>

        <div class="profile-card">
            <div class="img">
                <img src="pictures/Alhajj.jpg" alt="Profile Picture">
            </div>
            <div class="caption">
                <h3>Al Hajj Fauzan</h3>
                <h4>2309106019</h4>
            </div>
        </div>
    </div>
</body>
</html>