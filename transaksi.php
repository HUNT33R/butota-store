<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

require_once "config/db.php";

$result = mysqli_query($conn, "SELECT t.*, h.nama_paket, h.provider 
    FROM transaksi t 
    JOIN harga_paket h ON t.id_paket = h.id 
    ORDER BY t.id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Transaksi</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="admin-container">
        <h2>Daftar Transaksi</h2>
        <?php if (isset($_GET['hapus']) && $_GET['hapus'] == 'success'): ?>
            <div class="alert success">Transaksi berhasil dihapus.</div>
        <?php endif; ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nomor WA</th>
                    <th>Paket</th>
                    <th>Provider</th>
                    <th>Status</th>
                    <th>Waktu</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['nomor_wa']) ?></td>
                        <td><?= htmlspecialchars($row['nama_paket']) ?></td>
                        <td><?= htmlspecialchars($row['provider']) ?></td>
                        <td>
                            <?php if ($row['status'] == 'Terkirim'): ?>
                                <span class="badge success">Terkirim</span>
                            <?php else: ?>
                                <span class="badge warning">Belum</span>
                            <?php endif; ?>
                        </td>
                        <td><?= $row['created_at'] ?></td>
                        <td>
                            <a href="hapus_transaksi.php?id=<?= $row['id'] ?>" onclick="return confirm('Hapus transaksi ini?')">Hapus</a>
                            <?php if ($row['status'] !== 'Terkirim'): ?>
                                <br>
                                <a href="send_wa.php?id=<?= $row['id'] ?>" onclick="return confirm('Kirim ulang WA ke pelanggan?')">Kirim Ulang WA</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <br>
        <a href="dashboard.php">← Kembali ke Dashboard</a>
    </div>
</body>
</html>