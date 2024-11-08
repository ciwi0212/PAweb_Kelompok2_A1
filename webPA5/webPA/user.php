<?php
include 'koneksi.php';

session_start();
if (!isset($_SESSION['login'])) {
    echo "
    <script>
        alert('login dulu');
        window.location.href = 'login.php'; 
    </script>";
    exit;
}

$currentHour = date('H');
$sqlCurrent = "
    SELECT cuaca.*, negara.nama AS negara_nama, lokasi.alamat AS lokasi_alamat, HOUR(cuaca.jam) AS jam
    FROM cuaca
    JOIN negara ON cuaca.id_negara = negara.id
    JOIN lokasi ON cuaca.id_lokasi = lokasi.id
    WHERE HOUR(cuaca.jam) = HOUR(NOW())
";
$resultCurrent = mysqli_query($conn, $sqlCurrent);
$currentWeatherData = mysqli_fetch_all($resultCurrent, MYSQLI_ASSOC);

$sqlSummary = "
    SELECT cuaca.*, negara.nama AS negara_nama, lokasi.alamat AS lokasi_alamat, HOUR(cuaca.jam) AS jam
    FROM cuaca
    JOIN negara ON cuaca.id_negara = negara.id
    JOIN lokasi ON cuaca.id_lokasi = lokasi.id
    ORDER BY cuaca.jam
";
$resultSummary = mysqli_query($conn, $sqlSummary);

$weatherReports = [];
while ($row = mysqli_fetch_assoc($resultSummary)) {
    $weatherReports[] = $row;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Report</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-image: url('background.jpg');
            background-size: cover;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .container-navbar {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .navbar {
            top: 0;
            display: flex;
            justify-content: space-around;
            width: 100%;
            height: 90px;
            line-height: 100px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            align-items: center;
            z-index: 100;
        }
        .nav-menu ul {
            display: flex;
        }
        .nav-menu ul li {
            list-style-type: none;
        }
        .nav-menu ul li .link {
            font-weight: 500;
            color: black;
            text-decoration: none;
            margin: 0 23px;
            padding-bottom: 15px;
        }
        .link:hover,
        .active {
            border-bottom: 2px solid black;
        }

        .nav-logo {
            display: flex;
            align-items: center;
        }
        .nav-logo p {
            font-size: 24px;
            margin: 0;
            margin-right: 5px;
        }
        #logo {
            width: 60px;
            height: auto;
            color: black;
        }

        .searchbar {
            margin-bottom: 20px;
            display: flex;
            padding: 10px;
        }

        #search {
            width: 100%;
            padding: 10px;
            border: 1px solid #007acc;
            border-radius: 5px;
        }

        .current-weather {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            text-align: left;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .current-weather h2 {
            font-size: 2.5em;
            color: #007acc;
        }

        .current-weather p {
            font-size: 1.2em;
            margin: 5px 0;
        }

        .weather-summary {
            padding-top: 20px;
            border-top: 1px solid #007acc;
        }

        .weather-data {
            display: flex; 
            flex-wrap: nowrap; 
            overflow-x: auto; 
            padding: 10px 0; 
        }

        .weather-card {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            margin: 15px;
            padding: 20px;
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            min-width: 300px; 
            flex-shrink: 0; 
        }

        .weather-info {
            flex: 1; 
            margin-right: 15px;
            /* textext-align: left;  */
        }

        .weather-card h2 {
            font-size: 1.5em;
            color: #007acc;
            margin: 10px 0;
        }

        .weather-card h4 {
            font-size: 1em;
            color: #333;
            margin: 5px 0;
        }
        .hamburger{
            display: none;  
            cursor: pointer;
            flex-direction: column;
            z-index: 10;
        }
        .hamburger .garis {
            width: 25px;
            height: 3px;
            background-color: #000;
            margin: 2px 0; 
            transition: transform 0.4s ease-in-out, opacity 0.3s ease-in-out; 
        }
        .weather-icon {
            width: 100px; 
            height: 100px;
            transition: transform 0.3s ease;
            border-radius: 50%;
            background-color: #e0f7fa;
            padding: 10px;
            margin-right: 10px;
            /* margin-left: 300px; */
        }

        .weather-icon:hover {
            transform: scale(1.1);
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
        }

        /* .weather-icon img { */
            /* width: 100%; Supaya gambar ikuti ukuran ikon */
            /* height: auto; */
            /* border-radius: 50%; */
/* } */

        .nav-button .btn {
           width: 100%;
           height: 30px;
           font-weight: 500;
           background: rgba(255, 255, 255, 0.4);
           color: black; 
           border: 1px solid black; 
           border-radius: 30px; 
           cursor: pointer;
           transition: 0.3s ease;
           display: flex;
        }

        .btn.white-btn {
            background: rgba(255, 255, 255, 0.9);
            color: #000;
            align-items: center;
            text-align: center;
            display: flex;
        }
        .btn:hover {
            background: rgba(255, 255, 255, 0.6);
            color: #000;
        }
        .btn.btn.white-btn:hover {
            background: rgba(255, 255, 255, 0.8);
            color: #000;
        }

        .no-data {
            font-size: 1.2em;
            color: red;
            text-align: center;
            margin-top: 20px;
        }

        footer {
        background-color: #003366;
        color: white;
        text-align: center;
        padding: 20px;
        margin-top: 20px;
    }

        footer p a{
            color: white;
        }
        @media (max-width: 600px) {
            .weather-card {
                min-width: 200px;
            }
            .current-weather h2 {
                font-size: 1.5em;
            }
        }
        form {
            background: linear-gradient(to right, #ffffff, #e0e0e0); 
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            margin: 0 auto;
        }
        h2 {
            text-align: center;
            color: #00796b; 
        }
        label {
            display: block;
            font-weight: bold;
            margin-top: 15px;
            color: #004d40; 
        }
        input[type="text"],
        input[type="number"],
        input[type="file"],
        select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 2px solid #00796b; 
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus,
        input[type="number"]:focus,
        select:focus {
            border-color: #004d40; 
        }
        button {
            display: inline-block;
            background-color: #4caf50; 
            color: white;
            padding: 12px;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 9px;
            width: 100%;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #388e3c; 
        }
        
        select {
            background-color: #f9f9f9;
        }
        
        #mapFrame {
            width: 100%;
            height: 400px;
            margin-top: 20px;
            border: none;
            border-radius: 8px;
        }

        #alamatField, #koordinatField {
            margin-top: 15px;
        }

        
        label[for^="kondisi_"], label[for^="suhu_"], label[for^="kelembapan_"] {
            display: block;
            margin-top: 10px;
        }

        input[type="number"] {
            width: calc(100% - 24px);
            padding: 10px;
            margin-top: 5px;
            border: 2px solid #00796b;
            border-radius: 8px;
            transition: border-color 0.3s;
        }

        input[type="number"]:focus {
            border-color: #004d40;
        }

        a.btn {
            text-decoration: none;
            background-color: #f44336; 
            color: #fff;
            padding: 12px 20px;
            border-radius: 8px;
            display: inline-block;
            margin-top: 20px;
            text-align: center;
            transition: background-color 0.3s;
        }

        a.btn:hover {
            background-color: #d32f2f; 
        }
        .btn {
            background-color: #f44336;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #d32f2f; 
        }
        @media (max-width: 768px) {
    .nav-menu {
        display: none;
        flex-direction: column;
        position: absolute;
        top: 60px;
        left: 0;
        width: 100%;
        background-color: #eaf6f6;
        text-align: center;
    }
/*  */
    .nav-menu.active {
        display: flex;
    }
/*  */
    .nav-menu ul {
        flex-direction: column;
        gap: 1rem;
        padding: 1rem 0;
    }
/*  */
    .nav-button {
        display: none;
    }
/*  */
    .hamburger {
        display: flex;
        
    }
    .hamburger.active .garis:nth-child(1) {
        transform: translateY(7.2px) rotate(45deg) scale(1.1);
    }
/*  */
    .hamburger.active .garis:nth-child(2) {
        opacity: 0;
    }
/*  */
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
                <li><a href="user.php" class="link active">Home</a></li>
                <li><a href="about.php" class="link">About Us</a></li>
            </ul>
        </div>

        <div class="nav-button">
            <button class="btn white-btn" id="loginbtn" onclick="window.location.href = 'logout.php'">Logout</button>
        </div>

        <div class="hamburger">
                <span class="garis"></span>
                <span class="garis"></span>
                <span class="garis"></span>
        </div>
        </nav>
</div>
    <div class="searchbar">
        <input type="text" id="search" placeholder="Cari negara atau alamat..." onkeyup="searchWeather()" />
    </div>
    <div class="current-weather" id="currentWeather">
    <?php if ($currentWeatherData): ?>
        <?php foreach ($currentWeatherData as $currentWeather): ?>
            <div class="weather-entry">
                <h2>Cuaca Saat Ini di <?php echo htmlspecialchars($currentWeather['negara_nama'] . " - " . $currentWeather['lokasi_alamat']); ?></h2>
                <p>Kondisi: <?php echo htmlspecialchars($currentWeather['kondisi']); ?></p>
                <p>Suhu: <?php echo htmlspecialchars($currentWeather['suhu']);?> °C</p>
                <p>Kelembapan: <?php echo htmlspecialchars($currentWeather['kelembapan']); ?> %</p>
                <p>Jam: <?php echo htmlspecialchars($currentWeather['jam']); ?>:00</p>
                <?php
                $weatherIcon = 'pictures/default.png';
                switch (htmlspecialchars($currentWeather['kondisi'])) {
                    case 'Cerah':
                        $weatherIcon = 'pictures/cerah.png';
                        break;
                    case 'Berawan':
                        $weatherIcon = 'pictures/mendung.png';
                        break;
                    case 'Hujan':
                        $weatherIcon = 'pictures/hujan.png';
                        break;
                    case 'Salju':
                        $weatherIcon = 'pictures/salju.png';
                        break;
                }
                ?>
               
            </div>
            <img class="weather-icon" src="<?php echo $weatherIcon; ?>" alt="Weather Icon" data-condition="<?php echo htmlspecialchars($currentWeather['kondisi']); ?>">
        <?php endforeach; ?>
    <?php else: ?>
        <p>Data cuaca tidak tersedia.</p>
    <?php endif; ?>
    </div>
    <div class="weather-summary">
        <h2>Ringkasan Cuaca 24 Jam</h2>
        <div class="weather-data" id="weatherTable">
            <?php
            $groupedWeatherReports = [];
            foreach ($weatherReports as $weather) {
                $groupedWeatherReports[$weather['negara_nama']][] = $weather;
            }
            foreach ($groupedWeatherReports as $negaraNama => $reports): ?>
                <div class="country-section">
                    <?php foreach ($reports as $weather): ?>
                        <?php
                        $kondisi = htmlspecialchars($weather['kondisi']);
                        $suhu = htmlspecialchars($weather['suhu']);
                        $kelembapan = htmlspecialchars($weather['kelembapan']);
                        $lokasiAlamat = htmlspecialchars($weather['lokasi_alamat']);
                        $jam = htmlspecialchars($weather['jam']);

                        $weatherIcon = 'pictures/default.png';
                        switch ($kondisi) {
                            case 'Cerah':
                                $weatherIcon = 'pictures/cerah.png';
                                break;
                            case 'Berawan':
                                $weatherIcon = 'pictures/mendung.png';
                                break;
                            case 'Hujan':
                                $weatherIcon = 'pictures/hujan.png';
                                break;
                            case 'Salju':
                                $weatherIcon = 'pictures/salju.png';
                                break;
                        }
                        ?>
                        <div class="weather-card" data-negara="<?php echo $negaraNama; ?>" data-lokasi="<?php echo $lokasiAlamat; ?>">
                            <div class="weather-info">
                                <h2><?php echo "$negaraNama - $lokasiAlamat"; ?></h2>
                                <h4>Kondisi: <?php echo $kondisi; ?></h4>
                                <h4>Suhu: <?php echo $suhu; ?> °C</h4>
                                <h4>Kelembapan: <?php echo $kelembapan; ?> %</h4>
                                <h4>Jam: <?php echo $jam; ?>:00</h4>
                            </div>
                            <img class="weather-icon" src="<?php echo $weatherIcon; ?>" alt="Weather Icon" data-condition="<?php echo $kondisi; ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>

        </div>
        <div id="noDataMessage" class="no-data" style="display: none;">Data tidak ditemukan.</div>
    </div>

    <footer>

    <p>&copy; <?php echo date("Y"); ?> Weather Report. All Rights Reserved.</p>
    <p>Contact us at: <a href="mailto:info@weatherreport.com">info@weatherreport.com</a></p>

</footer>
    <script src="script.js"></script>
    <script>
        function searchWeather() {
            const input = document.getElementById('search');
            const filter = input.value.toLowerCase();
            const weatherCards = document.querySelectorAll('.weather-card');
            const currentWeatherDiv = document.getElementById('currentWeather');
            let hasResults = false;

            weatherCards.forEach(card => {
                const negara = card.getAttribute('data-negara').toLowerCase();
                const lokasi = card.getAttribute('data-lokasi').toLowerCase();
                if (negara.includes(filter) || lokasi.includes(filter)) {
                    card.style.display = ""; 
                    hasResults = true; 
                } else {
                    card.style.display = "none"; 
                }
            });

            if (hasResults) {
                currentWeatherDiv.style.display = ""; 
                document.getElementById('noDataMessage').style.display = "none"; 
            } else {
                currentWeatherDiv.style.display = "none"; 
                document.getElementById('noDataMessage').style.display = ""; 
            }
        }
    </script>
</body>
</html>