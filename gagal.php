<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran Gagal - BUTOTA STORE</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <header class="header">
        <div class="logo">
            <img src="assets/logo.png" alt="BUTOTA Logo" height="40">
            <span>BUTOTA STORE</span>
        </div>
    </header>

    <main class="error-container">
        <h2>Pembayaran Gagal ❌</h2>
        <p>Transaksi Anda dibatalkan atau terjadi kesalahan saat proses pembayaran.</p>
        <p>Silakan coba lagi atau hubungi admin jika masalah terus terjadi.</p>

        <a href="index.php" class="btn-beli">Kembali ke Beranda</a>
    </main>

    <footer>
        <p>&copy; <?= date("Y") ?> BUTOTA STORE. All rights reserved.</p>
    </footer>
</body>
</html>