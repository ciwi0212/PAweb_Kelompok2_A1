<?php
require "koneksi.php";
session_start();
if (!isset($_SESSION['login'])) {
    echo "
        <script>
            alert('login dulu');
            window.location.href = 'login.php'; 
        </script>";
    exit;
}
$sql1 = mysqli_query($conn, "SELECT * FROM negara");
$sql2 = mysqli_query($conn, "SELECT * FROM lokasi");
$sql3 = mysqli_query($conn, "SELECT * FROM cuaca");

$weatherReport = [];
while ($row1 = mysqli_fetch_assoc($sql1)) {
    $weatherReport1[] = $row1;
}
while ($row2 = mysqli_fetch_assoc($sql2)) {
    $weatherReport2[] = $row2;
}
while ($row3 = mysqli_fetch_assoc($sql3)) {
    $weatherReport3[] = $row3;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Document</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        body {
            background-color: #e0f7fa;
        }

        .garis {
            display: block;
            width: 25px;
            height: 3px;
            margin: 5px auto;
            background-color: black;
            color: black;
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

        .nav-button .btn {
            width: 115px;
            height: 30px;
            font-weight: 500;
            background: rgba(255, 255, 255, 0.4);
            color: black;
            border: 1px solid black;
            border-radius: 30px;
            cursor: pointer;
            transition: 0.3s ease;
        }

        .btn.white-btn {
            background: rgba(255, 255, 255, 0.9);
            color: #000;
        }

        .btn:hover {
            background: rgba(255, 255, 255, 0.6);
            color: #000;
        }

        .btn.btn.white-btn:hover {
            background: rgba(255, 255, 255, 0.8);
            color: #000;
        }

        .hamburger {
            display: none;
            cursor: pointer;
            flex-direction: column;
            z-index: 10;
            align-items: center;
        }

        .hamburger .garis {
            width: 25px;
            height: 3px;
            background-color: #fff;
            margin: 2px 0;
            transition: transform 0.4s ease-in-out, opacity 0.3s ease-in-out;
            color: black;
        }

        .table-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 16px;
            border-radius: 26px;
            border: none;

        }

        .table-container table {
            width: 90%;
            border-collapse: collapse;
        }

        .table-container th,
        .table-container td {
            padding: 10px;
            text-align: center;
        }

        .tomboltambah a {
            display: flex;
            padding: 10px 20px 10px;
            font-size: 15px;
            font-weight: 500;
            color: black;
            text-decoration: none;
            width: 140px;
        }

        .tomboltambah {
            display: flex;
            width: 140px;
            margin: 10px 65px 10px;
            border: 1px solid black;
            border-radius: 30px;
        }

        @media (max-width: 600px) {

            .nav-menu {
                display: none;
                flex-direction: column;
                position: absolute;
                top: 60px;
                left: 0;
                width: 100%;
                background-color: #333;
                text-align: center;
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

            .table-container table {
                width: 95%;
            }

            .table-container th,
            .table-container td {
                padding: 8px;
                font-size: 14px;
            }
        }




        form {
            display: flex;
            justify-content: center;
        }

        .searchbar {
            width: max-content;
            display: flex;
            align-items: center;
            padding: 14px;
            border-radius: 27px;
            background: #f6f6f6;
            border: 1px solid black;
            margin-bottom: 16px;
        }

        .search {
            font-size: 15px;
            font-family: 'Roboto', sans-serif;
            color: #333333;
            margin-left: 14px;
            outline: none;
            border: none;
            background: transparent;
            width: 300px;
        }

        h4 {
            padding-top: 100px;
            font-size: 26px;
            text-align: center;
        }

        td,
        i {
            text-decoration: none;
        }
    </style>
    <script>
        function searchLoc() {
            const input = document.getElementById('search');
            const filter = input.value.toLowerCase();
            const table = document.getElementById('loctable');
            const tr = table.getElementsByTagName('tr');
            for (let i = 1; i < tr.length; i++) {
                const tdNama = tr[i].getElementsByTagName('td')[2];
                const tdOrdo = tr[i].getElementsByTagName('td')[3];
                if (tdNama || tdOrdo) {
                    const txtValueNama = tdNama.textContent || tdNama.innerText;
                    const txtValueOrdo = tdOrdo.textContent || tdOrdo.innerText;
                    if (txtValueNama.toLowerCase().indexOf(filter) > -1 || txtValueOrdo.toLowerCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>
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
                    <li><a href="CRUD_admin.php" class="link active">Home</a></li>
                    <li><a href="about.php" class="link">About Us</a></li>
                </ul>
            </div>

            <div class="nav-button">
                <button class="btn white-btn" id="logoutbtn"
                    onclick="window.location.href = 'logout.php'">Logout</button>
            </div>

            <div class="hamburger">
                <i class="fa-solid fa-bars"></i>
            </div>

        </nav>
    </div>

    <h4>Data Cuaca</h4>
    <br>

    <form>
        <div class="searchbar">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input class="search" type=text id="search" placeholder="Cari Lokasi" onkeyup="searchLoc()">
        </div>
    </form>

    </div>
    <div class="tomboltambah">
        <a href="create.php">Tambah Data</a>
    </div>



    <?php
    if (mysqli_num_rows($sql1) > 0) {
        ?>
        <div class="table-container">
            <table border="1" id="loctable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>Kondisi</th>
                        <th>Jam</th>
                        <th>Suhu</th>
                        <th>Kelembapan</th>
                        <th>Alamat</th>
                        <th>Koordinat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    $sql = mysqli_query($conn, "
            SELECT cuaca.*, negara.*, lokasi.*, 
           ST_X(lokasi.koordinat) AS lat, 
           ST_Y(lokasi.koordinat) AS lng    
            FROM cuaca 
            INNER JOIN negara ON cuaca.id_negara = negara.id
            INNER JOIN lokasi ON cuaca.id_lokasi = lokasi.id
        ");
                    $weatherReport = [];
                    while ($row = mysqli_fetch_assoc($sql)) {
                        $weatherReport[] = $row;
                    }
                    $i = 1;
                    $j = 1;
                    $id_r = 1;
                    foreach ($weatherReport as $wr):
                        $gambar = $wr["foto"];
                        ?>
                        <tr>
                            <td><?= $i ?></td>
                            <td>
                                <center>
                                    <?php if ($wr["foto"] == "") {
                                        echo "foto belum ada";
                                    } else {
                                        echo "<img src='img/$gambar' alt='foto' width='50px' height='50px'>";
                                    } ?>
                                </center>
                            </td>
                            <td><?= htmlspecialchars($wr["nama"]) ?></td>
                            <td><?= htmlspecialchars($wr["kondisi"]) ?></td>
                            <td><?= htmlspecialchars($wr["jam"]) ?></td>
                            <td><?= htmlspecialchars($wr["suhu"]) ?></td>
                            <td><?= htmlspecialchars($wr["kelembapan"]) ?></td>
                            <td><?= htmlspecialchars($wr["alamat"]) ?></td>
                            <td><?= htmlspecialchars($wr["lat"]) ?>, <?= htmlspecialchars($wr["lng"]) ?></td>

                            <td>
                                <?php if ($i % 24 == 0): ?>
                                    <a href="edit.php?id_lokasi=<?= $wr["id_lokasi"] ?>"><i class="fa fa-pencil"
                                            style="color: blue"></i></a> |
                                    <a href="delete.php?id_lokasi=<?= $wr['id_lokasi'] ?>"
                                        onclick="return confirm('Yakin ingin menghapus data?');">
                                        <i class="fa fa-trash" style="color: red"></i>
                                    </a> <?php
                                    $id_r += 24;
                                    ?>
                                </td>
                            <?php endif; ?>
                        </tr>
                        <?php
                        $i++;
                    endforeach;
                    ?>
                </tbody>
            </table>
        </div>
        <?php
    } else {
        ?>
        <div style="display: flex; justify-content: center;">
            <p>Data cuaca tidak tersedia.</p>
        </div>
        <?php
    }
    ?>


    <script src="script.js"></script>
</body>

</html>