<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
require_once "config/db.php";

// Ambil total transaksi
$totalTransaksi = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM transaksi"))['total'];

// Ambil total voucher tersisa per provider
$voucherQuery = mysqli_query($conn, "
    SELECT provider, COUNT(*) as stok 
    FROM voucher 
    WHERE status = 'tersedia' 
    GROUP BY provider
");

// Siapkan array stok
$stokProvider = [];
while ($row = mysqli_fetch_assoc($voucherQuery)) {
    $stokProvider[$row['provider']] = $row['stok'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Admin Panel</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <header class="admin-header">
        <h2>Admin Panel - BUTOTA STORE</h2>
        <a href="logout.php" class="btn-logout">Logout</a>
    </header>

    <main class="admin-dashboard">
        <h3>Ringkasan</h3>
        <div class="dashboard-cards">
            <div class="card">
                <h4>Total Transaksi</h4>
                <p><?= $totalTransaksi ?></p>
            </div>
            <?php foreach ($stokProvider as $provider => $stok): ?>
                <div class="card <?= $stok == 0 ? 'card-warning' : '' ?>">
                    <h4>Stok - <?= htmlspecialchars($provider) ?></h4>
                    <p><?= $stok ?> voucher</p>
                    <?php if ($stok == 0): ?>
                        <p class="warning-text">⚠️ Stok Habis!</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <section class="admin-nav">
            <a href="provider.php" class="nav-button">Kelola Provider</a>
            <a href="harga.php" class="nav-button">Kelola Harga Paket</a>
            <a href="voucher.php" class="nav-button">Kelola Voucher</a>
            <a href="transaksi.php" class="nav-button">Riwayat Transaksi</a>
        </section>
    </main>

    <footer>
        <p>&copy; <?= date("Y") ?> BUTOTA STORE</p>
    </footer>
</body>
</html>