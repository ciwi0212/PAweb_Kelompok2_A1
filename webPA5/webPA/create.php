<?php
session_start();
    if (!isset($_SESSION['login'])) {
        echo "
        <script>
            alert('login dulu');
            window.location.href = 'login.php'; 
        </script>";
        exit;
    }
require "koneksi.php";
if (isset($_POST["submit"])){
    $negara = $_POST["negara"];
    $tmp_name = $_FILES["foto"]["tmp_name"];
    $files = $_FILES["foto"]["name"];
    $ukuran_file = $_FILES["foto"]["size"];
    if (!empty($files)) {
        $ekstensi = explode(".", $files);
        if (count($ekstensi) > 1) {
            $ekstensi = strtolower(end($ekstensi));
            $batas_ukuran = 2 * 1024 * 1024; 
            $ekstensi_boleh_masuk = ['png', 'jpg', 'jpeg'];
            if (in_array($ekstensi, $ekstensi_boleh_masuk) && $ukuran_file <= $batas_ukuran){
                date_default_timezone_set('Asia/Makassar');
                $tanggal = date('Y-m-d H.i.s'); 
                $ekstensi2 = $tanggal . '.' . $ekstensi;
                if (move_uploaded_file($tmp_name, 'img/' . $ekstensi2)) {
                    $sql1 = "INSERT INTO negara (nama, foto) VALUES ('$negara', '$ekstensi2')";
                    if (mysqli_query($conn, $sql1)) {
                        $id_negara = mysqli_insert_id($conn);
                        $longitude = $_POST["longitude"];
                        $latitude = $_POST["latitude"];
                        $kondisi = $_POST["kondisi"];
                        $suhu = $_POST["suhu"];
                        $kelembapan = $_POST["kelembapan"];
                        $alamat = $_POST["alamat"];
                        $sql2 = "INSERT INTO lokasi (alamat, koordinat) VALUES ('$alamat', ST_PointFromText('POINT($longitude $latitude)'))";
                        mysqli_query($conn, $sql2);
                        $id_lokasi = mysqli_insert_id($conn);
                        $processed_hours = []; 
                        foreach ($_POST['kondisi'] as $jam => $kondisi){
                            if (in_array($jam, $processed_hours)){
                                continue; 
                            }
                            $processed_hours[] = $jam;
                            $suhu = $_POST['suhu'][$jam];
                            $kelembapan = $_POST['kelembapan'][$jam];
                            if (!empty($kondisi) && isset($suhu) && isset($kelembapan)){
                                $sql_check = "SELECT * FROM cuaca WHERE jam = '$jam' AND id_negara = '$id_negara' AND id_lokasi = '$id_lokasi'";
                                $result_check = mysqli_query($conn, $sql_check);
                                if (mysqli_num_rows($result_check) == 0){
                                    $sql3 = "INSERT INTO cuaca (jam, kondisi, suhu, kelembapan, id_negara, id_lokasi) 
                                            VALUES ('$jam', '$kondisi', '$suhu', '$kelembapan', '$id_negara', '$id_lokasi')";
                                    mysqli_query($conn, $sql3);
                                    echo "<script>alert('Data berhasil disimpan'); document.location.href = 'CRUD_admin.php';</script>";
                                }
                            }
                        }
                    } else {
                        echo "<script>alert('Kesalahan saat menyimpan data negara: " . mysqli_error($conn) . "');</script>";
                    }
                } else {
                    echo "<script>alert('Gagal mengunduh gambar');</script>";
                }
            } else {
                if ($ukuran_file > $batas_ukuran) {
                    echo "<script>alert('Ukuran file terlalu besar. Maksimal 2MB.');</script>";
                } else {
                    echo "<script>alert('Tipe file hanya bisa berupa png, jpg/jpeg');</script>";
                }
            }
        } else {
            echo "<script>alert('File tidak ada');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WeatherReport|Create</title>
    <style>
        
        body {
            font-family: Arial, sans-serif;
            background-color: #e0f7fa;
            margin: 0;
            padding: 20px;
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
            margin-top: 20px;
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
    </style>
</head>

<body>
<form action="create.php" method="post" enctype="multipart/form-data" id="lokasiForm"> 
    <div>
        <label for="foto">Upload Gambar Negara</label>
        <input type="file" name="foto" id="foto" required>
    </div>
    <div>
        <label for="negara">Negara</label>
        <select name="negara" id="negara" required>
            <option value="">Pilih Negara</option>
        </select>
    </div>
    <div>
        <p>Pilih salah satu:</p>
        <label>
            <input type="radio" name="pilihan" value="alamat" onclick="pemisah()" checked> Alamat
        </label>
        <label>
            <input type="radio" name="pilihan" value="koordinat" onclick="pemisah()"> Koordinat
        </label>
    </div>
    <div id="alamatField">
        <label for="alamat">Alamat</label>
        <input type="text" name="alamat" id="alamat" placeholder="Masukkan alamat">
    </div>
    <div id="koordinatField" style="display: none;">
        <label for="longitude">Longitude</label>
        <input type="text" name="longitude" id="longitude" placeholder="Masukkan Longitude">
        <label for="latitude">Latitude</label>
        <input type="text" name="latitude" id="latitude" placeholder="Masukkan Latitude">
    </div>
    <div>
        <iframe id="mapFrame" width="100%" height="500" src=""></iframe> 
    </div>
   <button type="button" onclick="updateform_map()" style="font-family: 'roman'; background-color: black"><b>Lokasikan</b></button>
   <div>
    <?php
    for ($jam = 0; $jam < 24; $jam++){
        $label_jam = str_pad($jam, 2, '0', STR_PAD_LEFT) . ":00";
        echo "<label for='kondisi_$jam'>Kondisi Cuaca Jam $label_jam:</label>";
        echo "<select name='kondisi[$label_jam]' id='kondisi_$label_jam' required>";
        echo "<option value=''>Pilih Kondisi</option>";
        echo "<option value='Cerah'>Cerah</option>";
        echo "<option value='Hujan'>Hujan</option>";
        echo "<option value='Berawan'>Berawan</option>";
        echo "<option value='Salju'>Salju</option>";
        echo "</select>";
        echo "<label for='suhu_$jam'>Suhu:</label>";
        echo "<input type='number' name='suhu[$label_jam]' id='suhu_$label_jam' required min='-50' max='50'> °C";
        echo "<label for='kelembapan_$jam'>Kelembapan:</label>";
        echo "<input type='number' name='kelembapan[$label_jam]' id='kelembapan_$label_jam' required> %<br><br>";
    }
    ?>
    
    <button type="submit" name="submit">Simpan</button>
</form>
<a href="CRUD_admin.php" class="btn">Ke Halaman CRUD Admin</a>

<script>
    var negara = ["Afghanistan", "Albania", "Algeria", "Andorra", "Angola", 
                   "Argentina", "Armenia", "Australia", "Austria", "Azerbaijan",
                   "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus",
                   "Belgium", "Belize", "Benin", "Bhutan", "Bolivia",
                   "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", "Bulgaria",
                   "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada",
                   "Cape Verde", "Central African Republic", "Chad", "Chile", "China",
                   "Colombia", "Comoros", "Congo", "Costa Rica", "Croatia",
                   "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti",
                   "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador",
                   "Equatorial Guinea", "Eritrea", "Estonia", "Eswatini", "Ethiopia",
                   "Fiji", "Finland", "France", "Gabon", "Gambia",
                   "Georgia", "Germany", "Ghana", "Greece", "Grenada",
                   "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti",
                   "Honduras", "Hungary", "Iceland", "India", "Indonesia",
                   "Iran", "Iraq", "Ireland", "Israel", "Italy",
                   "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya",
                   "Kiribati", "Kuwait", "Kyrgyzstan", "Laos", "Latvia",
                   "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein",
                   "Lithuania", "Luxembourg", "Madagascar", "Malawi", "Malaysia",
                   "Maldives", "Mali", "Malta", "Marshall Islands", "Mauritania",
                   "Mauritius", "Mexico", "Micronesia", "Moldova", "Monaco",
                   "Mongolia", "Montenegro", "Morocco", "Mozambique", "Myanmar",
                   "Namibia", "Nauru", "Nepal", "Netherlands", "New Zealand",
                   "Nicaragua", "Niger", "Nigeria", "North Macedonia", "Norway",
                   "Oman", "Pakistan", "Palau", "Panegara", "Papua New Guinea",
                   "Paraguay", "Peru", "Philippines", "Poland", "Portugal",
                   "Qatar", "Romania", "Russia", "Rwanda", "Saint Kitts and Nevis",
                   "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe",
                   "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone",
                   "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia",
                   "South Africa", "South Korea", "South Sudan", "Spain", "Sri Lanka",
                   "Sudan", "Suriname", "Sweden", "Switzerland", "Syria",
                   "Taiwan", "Tajikistan", "Tanzania", "Thailand", "Timor-Leste",
                   "Togo", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey",
                   "Turkmenistan", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates",
                   "United Kingdom", "United States", "Uruguay", "Uzbekistan", "Vanuatu",
                   "Vatican City", "Venezuela", "Vietnam", "Yemen", "Zambia", "Zimbabwe"];

    const selectElement = document.getElementById("negara");
    negara.forEach(negara =>{
        const option = document.createElement("option");
        option.value = negara;
        option.text = negara;
        selectElement.add(option);   
    });
    function pemisah(){
        const alamatField = document.getElementById("alamatField");
        const koordinatField = document.getElementById("koordinatField");
        const pilihan = document.querySelector('input[name="pilihan"]:checked').value;
        if (pilihan === "alamat") {
            alamatField.style.display = "block";
            koordinatField.style.display = "none";
        } else {
            alamatField.style.display = "none";
            koordinatField.style.display = "block";
        }
    }
    function updateform_map(){
        const negara = document.getElementById("negara").value;
        const alamat = document.getElementById("alamat").value;
        const longitude = document.getElementById("longitude").value;
        const latitude = document.getElementById("latitude").value;
        let mapQuery = '';

        if (alamat){
            mapQuery = `${negara} ${alamat}`;
        } else if (longitude && latitude) {
            mapQuery = `${latitude},${longitude}`;
        }
        document.getElementById("mapFrame").src = `https://maps.google.com/maps?q=${mapQuery}&output=embed`;
    }
</script>
</body>
</html>