<?php
$host = "localhost";
$user = "butm3781_boboy";
$pass = "#Sunade13";
$db = "butm3781_base";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>