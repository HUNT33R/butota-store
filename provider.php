<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
require_once "config/db.php";

// Tambah provider
if (isset($_POST['tambah'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    mysqli_query($conn, "INSERT INTO provider (nama) VALUES ('$nama')");
    header("Location: provider.php");
    exit;
}

// Hapus provider
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    mysqli_query($conn, "DELETE FROM provider WHERE id = $id");
    header("Location: provider.php");
    exit;
}

// Ambil semua provider
$provider = mysqli_query($conn, "SELECT * FROM provider ORDER BY nama ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Provider - Admin Panel</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <header class="admin-header">
        <h2>Kelola Provider</h2>
        <a href="dashboard.php" class="btn-back">← Kembali</a>
    </header>

    <main class="admin-content">
        <h3>Tambah Provider Baru</h3>
        <form method="POST">
            <input type="text" name="nama" required placeholder="Contoh: zayan.net" class="input-text">
            <button type="submit" name="tambah" class="btn-primary">Tambah</button>
        </form>

        <h3>Daftar Provider</h3>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Provider</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; while ($row = mysqli_fetch_assoc($provider)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['nama']) ?></td>
                    <td>
                        <a href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus provider ini?')" class="btn-delete">Hapus</a>
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