<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
require_once "config/db.php";

// Ambil data provider
$providerList = mysqli_query($conn, "SELECT * FROM provider ORDER BY nama ASC");

// Tambah harga
if (isset($_POST['tambah'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_paket']);
    $provider = mysqli_real_escape_string($conn, $_POST['provider']);
    $harga = intval($_POST['harga']);
    mysqli_query($conn, "INSERT INTO harga_paket (nama_paket, provider, harga) VALUES ('$nama', '$provider', $harga)");
    header("Location: harga.php");
    exit;
}

// Ambil data harga paket
$paketList = mysqli_query($conn, "SELECT * FROM harga_paket ORDER BY provider, nama_paket");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Harga - Admin Panel</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <header class="admin-header">
        <h2>Kelola Harga Paket</h2>
        <a href="dashboard.php" class="btn-back">← Kembali</a>
    </header>

    <main class="admin-content">
        <h3>Tambah Harga Paket</h3>
        <form method="POST">
            <input type="text" name="nama_paket" required placeholder="Nama Paket (Contoh: 1 Jam)" class="input-text">
            <select name="provider" required class="input-text">
                <option value="">-- Pilih Provider --</option>
                <?php while ($p = mysqli_fetch_assoc($providerList)): ?>
                    <option value="<?= $p['nama'] ?>"><?= $p['nama'] ?></option>
                <?php endwhile; ?>
            </select>
            <input type="number" name="harga" required placeholder="Harga (Contoh: 5000)" class="input-text">
            <button type="submit" name="tambah" class="btn-primary">Tambah</button>
        </form>

        <h3>Daftar Harga Paket</h3>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Paket</th>
                    <th>Provider</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; while ($row = mysqli_fetch_assoc($paketList)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['nama_paket']) ?></td>
                    <td><?= htmlspecialchars($row['provider']) ?></td>
                    <td>Rp<?= number_format($row['harga'], 0, ',', '.') ?></td>
                    <td>
                        <a href="hapus_harga.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus paket ini?')" class="btn-delete">Hapus</a>
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