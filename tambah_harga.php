<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $provider_id = mysqli_real_escape_string($conn, $_POST['provider_id']);
    $nama_paket = mysqli_real_escape_string($conn, $_POST['nama_paket']);
    $harga = mysqli_real_escape_string($conn, $_POST['harga']);

    $query = "INSERT INTO harga (provider_id, nama_paket, harga) VALUES ('$provider_id', '$nama_paket', '$harga')";
    if (mysqli_query($conn, $query)) {
        header("Location: harga.php?tambah=berhasil");
    } else {
        header("Location: harga.php?tambah=gagal");
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Harga - Admin Panel</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Tambah Harga Paket</h2>
    <form method="POST" action="">
        <label for="provider_id">Provider:</label>
        <select name="provider_id" required>
            <?php
            $result = mysqli_query($conn, "SELECT * FROM providers");
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['nama_provider']) . "</option>";
            }
            ?>
        </select><br><br>

        <label for="nama_paket">Nama Paket:</label>
        <input type="text" name="nama_paket" required><br><br>

        <label for="harga">Harga (Rp):</label>
        <input type="number" name="harga" required><br><br>

        <input type="submit" value="Simpan">
    </form>
</body>
</html>