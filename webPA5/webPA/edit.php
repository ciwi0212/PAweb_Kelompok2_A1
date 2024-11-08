<?php
// session_start();
// if (!isset($_SESSION['login'])) {
//     echo "
//     <script>
//         alert('Login dulu');
//         window.location.href = 'login.php'; 
//     </script>";
//     exit;
// }
require "koneksi.php";

$id_lokasi = $_GET['id_lokasi'];
echo "id_lokasi =  $id_lokasi";

// TABLE NEGARA
$id_negara = mysqli_query($conn, "SELECT id_negara FROM cuaca WHERE id_lokasi = $id_lokasi LIMIT 1");
$row = mysqli_fetch_assoc($id_negara);
$id_negara = $row['id_negara'];
echo "id_negara = $id_negara";
$result_negara = mysqli_query($conn, "SELECT * FROM negara WHERE id = $id_negara");
$weatherReport1 = mysqli_fetch_assoc($result_negara);

// TABLE CUACA
$result_cuaca = mysqli_query($conn, "SELECT * FROM cuaca WHERE id_negara = $id_negara");

$weatherData = [];
while ($row = mysqli_fetch_assoc($result_cuaca)) {
    $weatherData[$row['jam']] = [
        'kondisi' => $row['kondisi'],
        'suhu' => $row['suhu'],
        'kelembapan' => $row['kelembapan']
    ];
}

// TABLE LOKASI
$result_lokasi = mysqli_query($conn, "SELECT alamat, ST_X(koordinat) AS latitude, ST_Y(koordinat) AS longitude FROM lokasi WHERE id = $id_lokasi");
$weatherReport3 = mysqli_fetch_assoc($result_lokasi);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $negara = $_POST['negara'];
    $alamat = $_POST['alamat'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $kondisi = $_POST['kondisi'];
    $suhu = $_POST['suhu'];
    $kelembapan = $_POST['kelembapan'];

    // Handle image upload
    $foto = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['foto']['tmp_name'];
        $fileName = $_FILES['foto']['name'];
        $fileSize = $_FILES['foto']['size'];
        $fileType = $_FILES['foto']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedfileExtensions = ['jpg', 'jpeg', 'png'];
        if (in_array($fileExtension, $allowedfileExtensions)) {
            // Generate a unique name for the file before saving it
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $uploadFileDir = 'img/';
            $dest_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $foto = $newFileName;
            } else {
                echo "Error moving the uploaded file";
            }
        } else {
            echo "Upload failed. Allowed file types: " . implode(',', $allowedfileExtensions);
        }
    }

    // Update the `lokasi` table
    $stmt = $conn->prepare("UPDATE lokasi SET alamat = ?, koordinat = POINT(?, ?) WHERE id = ?");
    $stmt->bind_param("sddi", $alamat, $latitude, $longitude, $id_lokasi);
    $stmt->execute();

    $new_country_name = $_POST['negara'];
    $stmt_country = $conn->prepare("UPDATE negara SET nama = ? WHERE id = ?");
    $stmt_country->bind_param("si", $new_country_name, $id_negara);
    $stmt_country->execute();
    $stmt_country->close();

    // Update the `negara` table (if a new image was uploaded)
    if ($foto) {
        $stmt = $conn->prepare("UPDATE negara SET foto = ? WHERE id = ?");
        $stmt->bind_param("si", $foto, $id_negara);
        $stmt->execute();
    }

    // Update the `cuaca` table for each hour
    foreach ($kondisi as $jam_string => $kondisi_cuaca) {
        // Ensure `jam_string` is formatted as `HH:00`
        $jam_formatted = $jam_string;

        $suhu_val = $suhu[$jam_string];
        $kelembapan_val = $kelembapan[$jam_string];

        // Prepare and execute the update statement
        $stmt = $conn->prepare("UPDATE cuaca SET kondisi = ?, suhu = ?, kelembapan = ? WHERE id_negara = ? AND jam = ?");
        $stmt->bind_param("sdiss", $kondisi_cuaca, $suhu_val, $kelembapan_val, $id_negara, $jam_formatted);
        $stmt->execute();
    }


    echo "
    <script>
        alert('Weather data updated successfully');
        window.location.href = 'CRUD_admin.php'; // Redirect to a view page or the updated location page
    </script>";
}
?>

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Weather Report</title>
    <style>
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
    </style>
    <script>
        function toggleInput() {
            var alamatField = document.getElementById("alamatField");
            var koordinatField = document.getElementById("koordinatField");
            var selectedOption = document.querySelector('input[name="pilihan"]:checked').value;
            if (selectedOption === "alamat") {
                alamatField.style.display = "block";
                koordinatField.style.display = "none";
            } else {
                alamatField.style.display = "none";
                koordinatField.style.display = "block";
            }
        }
    </script>
</head>

<body>
    <form action="" method="post" enctype="multipart/form-data">
        <div>
            <label for="foto">Upload Gambar Negara</label>
            <input type="file" name="foto" id="foto">
            <?php if (!empty($weatherReport1["foto"])): ?>
                <div>
                    <img src="img/<?= $weatherReport1["foto"] ?>" alt="Gambar" width="100">
                </div>
            <?php endif; ?>
        </div>
        <div>
            <label for="negara">Negara</label>
            <select name="negara" id="negara" required>
                <option value="negara">Pilih Negara</option>
                <?php if ($weatherReport1): ?>
                    <option value="<?= $weatherReport1['nama'] ?>" selected><?= $weatherReport1['nama'] ?></option>
                <?php endif; ?>
            </select>
            <script>
                const selectedCountry = "<?= isset($weatherReport1['nama']) ? $weatherReport1['nama'] : '' ?>";
            </script>
        </div>
        <div>
            <div id="alamatInput">
                <label>Alamat:
                    <input type="text" name="alamat" id="alamat" value="<?= $weatherReport3['alamat'] ?>"
                        placeholder="Masukkan alamat" oninput="toggleInput()">
                </label>
            </div>
            <div id="koordinatInput">
                <label>Koordinat(Latitude, Longitude):
                    <input type="text" name="latitude" id="latitude" value="<?= $weatherReport3['latitude'] ?>"
                        placeholder="Latitude" oninput="toggleInput()">
                    <input type="text" name="longitude" id="longitude" value="<?= $weatherReport3['longitude'] ?>"
                        placeholder="Longitude" oninput="toggleInput()">
                </label>
            </div>
        </div>

        <div>
            <?php
            for ($jam = 0; $jam < 24; $jam++) {
                $label_jam = str_pad($jam, 2, '0', STR_PAD_LEFT) . ":00";

                // Ambil data cuaca untuk jam tertentu, atau gunakan data default jika tidak ada
                $data_baru = $weatherData[$label_jam] ?? ['kondisi' => '', 'suhu' => '', 'kelembapan' => ''];

                // Nilai kondisi, suhu, dan kelembapan, jika kosong gunakan nilai default
                $value_kondisi = isset($data_baru['kondisi']) ? $data_baru['kondisi'] : '';
                $value_suhu = isset($data_baru['suhu']) ? $data_baru['suhu'] : '';
                $value_kelembapan = isset($data_baru['kelembapan']) ? $data_baru['kelembapan'] : '';
                ?>

                <label for="kondisi_<?php echo $label_jam; ?>">Kondisi Cuaca Jam <?php echo $label_jam; ?>:</label>
                <select name="kondisi[<?php echo $label_jam; ?>]" id="kondisi_<?php echo $label_jam; ?>" required>
                    <option value="" <?php echo ($value_kondisi == '' ? 'selected' : ''); ?>>Pilih Kondisi</option>
                    <option value="Cerah" <?php echo ($value_kondisi == 'Cerah' ? 'selected' : ''); ?>>Cerah</option>
                    <option value="Hujan" <?php echo ($value_kondisi == 'Hujan' ? 'selected' : ''); ?>>Hujan</option>
                    <option value="Berawan" <?php echo ($value_kondisi == 'Berawan' ? 'selected' : ''); ?>>Berawan</option>
                    <option value="Salju" <?php echo ($value_kondisi == 'Salju' ? 'selected' : ''); ?>>Salju</option>
                </select>

                <label for="suhu_<?php echo $label_jam; ?>">Suhu:</label>
                <input type="number" name="suhu[<?php echo $label_jam; ?>]" id="suhu_<?php echo $label_jam; ?>" required
                    min="-50" max="50" value="<?php echo htmlspecialchars($value_suhu); ?>">

                <label for="kelembapan_<?php echo $label_jam; ?>">Kelembapan:</label>
                <input type="number" name="kelembapan[<?php echo $label_jam; ?>]" id="kelembapan_<?php echo $label_jam; ?>"
                    required value="<?php echo htmlspecialchars($value_kelembapan); ?>">

                <br>

                <?php
            }
            ?>
        </div>


        <button type="submit" name="submit">Simpan</button>
    </form>
</body>

</html>

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
    negara.forEach(negaraItem => {
        const option = document.createElement("option");
        option.value = negaraItem;
        option.text = negaraItem;
        if (negaraItem === selectedCountry) {
            option.selected = true;
        }
        selectElement.add(option);
    });
    function updateform_map() {
        const negara = document.getElementById("negara").value;
        const alamat = document.getElementById("alamat").value;
        const longitude = document.getElementById("longitude").value;
        const latitude = document.getElementById("latitude").value;
        let mapQuery = '';
        if (alamat) {
            mapQuery = `${negara} ${alamat}`;
        } else if (longitude && latitude) {
            mapQuery = `${latitude},${longitude}`;
        }
        document.getElementById("mapFrame").src = `https://maps.google.com/maps?q=${mapQuery}&output=embed`;
    }

</script>
</body>

</html>