<?php
session_start();
require_once "admin/config/db.php";

$query = "SELECT * FROM harga_paket ORDER BY provider, nama_paket";
$result = mysqli_query($conn, $query);
$paketList = [];
while ($row = mysqli_fetch_assoc($result)) {
    $paketList[] = $row;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>BUTOTA STORE - Top Up Voucher WiFi</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <header class="header">
        <div class="logo">
            <img src="assets/logo.png" alt="BUTOTA Logo">
            <span>BUTOTA STORE</span>
        </div>
    </header>

    <main>
        <section class="hero">
            <h1>Beli Voucher WiFi Mikrotik</h1>
            <p>Langsung aktif, otomatis terkirim via WhatsApp!</p>
        </section>

        <section class="paket-container">
            <?php if (empty($paketList)): ?>
                <p class="no-data">Belum ada paket tersedia.</p>
            <?php else: ?>
                <?php foreach ($paketList as $paket): ?>
                    <div class="paket-card">
                        <h3><?= htmlspecialchars($paket['nama_paket']) ?></h3>
                        <p><strong>Provider:</strong> <?= htmlspecialchars($paket['provider']) ?></p>
                        <p><strong>Harga:</strong> Rp<?= number_format($paket['harga'], 0, ',', '.') ?></p>
                        <a href="beli.php?id=<?= $paket['id'] ?>" class="btn-beli">Beli Sekarang</a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <p>&copy; <?= date("Y") ?> BUTOTA STORE</p>
    </footer>
</body>
</html>