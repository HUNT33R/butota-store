<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $ikon = mysqli_real_escape_string($conn, $_POST['ikon']); // URL ikon provider

    if (!empty($nama)) {
        $query = "INSERT INTO providers (nama, ikon) VALUES ('$nama', '$ikon')";
        if (mysqli_query($conn, $query)) {
            header("Location: provider.php?success=1");
            exit;
        } else {
            $error = "Gagal menambahkan provider.";
        }
    } else {
        $error = "Nama provider tidak boleh kosong.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Provider</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Tambah Provider</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="post">
        <label>Nama Provider</label>
        <input type="text" name="nama" required><br>

        <label>Link Ikon Provider (opsional)</label>
        <input type="text" name="ikon" placeholder="https://..."><br>

        <button type="submit">Simpan</button>
        <a href="provider.php">Batal</a>
    </form>
</div>
</body>
</html>