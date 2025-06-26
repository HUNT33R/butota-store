<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
require_once "config/db.php";

// Ambil semua paket
$paketList = mysqli_query($conn, "SELECT * FROM harga_paket ORDER BY provider, nama_paket");

// Ambil semua voucher
$voucherList = mysqli_query($conn, "SELECT v.*, h.nama_paket, h.provider 
    FROM voucher v
    JOIN harga_paket h ON v.id_paket = h.id 
    ORDER BY h.provider, h.nama_paket, v.id DESC");

// Notifikasi upload
$uploadSuccess = isset($_GET['upload']) && $_GET['upload'] === 'success';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Voucher - Admin Panel</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <header class="admin-header">
        <h2>Kelola Voucher</h2>
        <a href="dashboard.php" class="btn-back">← Kembali</a>
    </header>

    <main class="admin-content">
        <?php if ($uploadSuccess): ?>
            <div class="alert success">Upload voucher berhasil!</div>
        <?php endif; ?>

        <h3>Upload Voucher (PDF atau Manual)</h3>
        <form action="tambah_voucher.php" method="POST" enctype="multipart/form-data">
            <label>Pilih Paket:</label>
            <select name="id_paket" required class="input-text">
                <option value="">-- Pilih Paket --</option>
                <?php while ($p = mysqli_fetch_assoc($paketList)): ?>
                    <option value="<?= $p['id'] ?>">
                        <?= htmlspecialchars($p['provider']) ?> - <?= htmlspecialchars($p['nama_paket']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label>Upload File PDF (50 kode, 5 per baris):</label>
            <input type="file" name="pdf_file" accept="application/pdf" class="input-text">

            <label>Atau Input Manual (1 kode per baris):</label>
            <textarea name="manual_voucher" rows="5" placeholder="Misal:
ABC123
DEF456
GHI789" class="input-text"></textarea>

            <button type="submit" class="btn-primary">Upload Voucher</button>
        </form>

        <h3>Daftar Voucher Tersedia</h3>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Provider</th>
                    <th>Paket</th>
                    <th>Kode</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; while ($v = mysqli_fetch_assoc($voucherList)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($v['provider']) ?></td>
                    <td><?= htmlspecialchars($v['nama_paket']) ?></td>
                    <td><?= htmlspecialchars($v['kode']) ?></td>
                    <td><?= $v['terpakai'] ? 'Terpakai' : 'Tersedia' ?></td>
                    <td>
                        <?php if (!$v['terpakai']): ?>
                            <a href="hapus_voucher.php?id=<?= $v['id'] ?>" class="btn-delete" onclick="return confirm('Hapus voucher ini?')">Hapus</a>
                        <?php else: ?>
                            <span>-</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>

    <footer>
        <p>&copy; <?= date("Y") ?> BUTOTA STORE</p>
    </footer>
</body>
</html>