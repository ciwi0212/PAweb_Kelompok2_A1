<?php
$servername = "localhost";
$username = "root"; 
$password = "can021204_"; 
$dbname = "weatherreport"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}else{

}
?>