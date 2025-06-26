<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran Berhasil - BUTOTA STORE</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <header class="header">
        <div class="logo">
            <img src="assets/logo.png" alt="BUTOTA Logo" height="40">
            <span>BUTOTA STORE</span>
        </div>
    </header>

    <main class="success-container">
        <h2>Pembayaran Berhasil ✅</h2>
        <p>Terima kasih! Pembayaran Anda sudah diterima.</p>
        <p>Voucher akan dikirim otomatis ke nomor WhatsApp Anda dalam beberapa saat.</p>

        <a href="index.php" class="btn-beli">Kembali ke Beranda</a>
    </main>

    <footer>
        <p>&copy; <?= date("Y") ?> BUTOTA STORE. All rights reserved.</p>
    </footer>
</body>
</html>