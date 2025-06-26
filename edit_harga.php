<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include '../config.php';

if (!isset($_GET['id'])) {
    header("Location: harga.php");
    exit;
}

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM harga WHERE id = '$id'");
$harga = mysqli_fetch_assoc($query);

if (!$harga) {
    header("Location: harga.php");
    exit;
}

// Ambil data provider untuk dropdown
$providers = mysqli_query($conn, "SELECT * FROM providers");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $provider_id = $_POST['provider_id'];
    $paket = $_POST['paket'];
    $harga_paket = $_POST['harga'];

    $update = mysqli_query($conn, "UPDATE harga SET provider_id='$provider_id', paket='$paket', harga='$harga_paket' WHERE id='$id'");

    if ($update) {
        header("Location: harga.php?success=1");
        exit;
    } else {
        $error = "Gagal memperbarui harga.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Harga</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Edit Harga Paket</h2>
        <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
        <form method="POST">
            <label>Provider</label>
            <select name="provider_id" required>
                <?php while ($p = mysqli_fetch_assoc($providers)) { ?>
                    <option value="<?= $p['id'] ?>" <?= $harga['provider_id'] == $p['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($p['nama']) ?>
                    </option>
                <?php } ?>
            </select><br>

            <label>Nama Paket</label>
            <input type="text" name="paket" value="<?= htmlspecialchars($harga['paket']) ?>" required><br>

            <label>Harga</label>
            <input type="number" name="harga" value="<?= $harga['harga'] ?>" required><br>

            <button type="submit">Simpan Perubahan</button>
            <a href="harga.php">Batal</a>
        </form>
    </div>
</body>
</html>