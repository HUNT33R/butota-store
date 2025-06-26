<?php
session_start();
require_once "admin/config/db.php";

if (!isset($_GET['id'])) {
    die("ID paket tidak ditemukan.");
}

$id = intval($_GET['id']);
$query = mysqli_query($conn, "SELECT * FROM harga_paket WHERE id = $id");
$paket = mysqli_fetch_assoc($query);

if (!$paket) {
    die("Paket tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Beli - <?= htmlspecialchars($paket['nama_paket']) ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <header class="header">
        <div class="logo">
            <img src="assets/logo.png" alt="BUTOTA Logo">
            <span>BUTOTA STORE</span>
        </div>
    </header>

    <main class="beli-container">
        <h2>Beli Voucher: <?= htmlspecialchars($paket['nama_paket']) ?></h2>
        <p><strong>Provider:</strong> <?= htmlspecialchars($paket['provider']) ?></p>
        <p><strong>Harga:</strong> Rp<?= number_format($paket['harga'], 0, ',', '.') ?></p>

        <form action="proses_pembelian.php" method="POST">
            <input type="hidden" name="id_paket" value="<?= $paket['id'] ?>">

            <label for="no_wa">Nomor WhatsApp:</label>
            <input type="number" name="no_wa" id="no_wa" required placeholder="08xxxx">

            <button type="submit" class="btn-beli">Lanjutkan Pembelian</button>
        </form>
    </main>

    <footer>
        <p>&copy; <?= date("Y") ?> BUTOTA STORE</p>
    </footer>
</body>
</html>