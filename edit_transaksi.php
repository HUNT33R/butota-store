<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include '../config.php';

if (!isset($_GET['id'])) {
    header("Location: transaksi.php");
    exit;
}

$id = $_GET['id'];
$transaksi = mysqli_query($conn, "SELECT * FROM transaksi WHERE id = '$id'");
$data = mysqli_fetch_assoc($transaksi);

if (!$data) {
    header("Location: transaksi.php");
    exit;
}

// Ambil semua provider
$providers = mysqli_query($conn, "SELECT * FROM providers");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $no_wa = $_POST['no_wa'];
    $provider_id = $_POST['provider_id'];
    $status = $_POST['status'];

    $update = mysqli_query($conn, "UPDATE transaksi SET no_wa='$no_wa', provider_id='$provider_id', status='$status' WHERE id='$id'");

    if ($update) {
        header("Location: transaksi.php?updated=1");
        exit;
    } else {
        $error = "Gagal memperbarui transaksi.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Transaksi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Edit Transaksi</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="post">
        <label>No. WhatsApp</label>
        <input type="text" name="no_wa" value="<?= htmlspecialchars($data['no_wa']) ?>" required><br>

        <label>Provider</label>
        <select name="provider_id" required>
            <?php while ($p = mysqli_fetch_assoc($providers)) { ?>
                <option value="<?= $p['id'] ?>" <?= $p['id'] == $data['provider_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($p['nama']) ?>
                </option>
            <?php } ?>
        </select><br>

        <label>Status</label>
        <select name="status" required>
            <option value="pending" <?= $data['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
            <option value="sukses" <?= $data['status'] == 'sukses' ? 'selected' : '' ?>>Sukses</option>
            <option value="gagal" <?= $data['status'] == 'gagal' ? 'selected' : '' ?>>Gagal</option>
        </select><br>

        <button type="submit">Simpan</button>
        <a href="transaksi.php">Batal</a>
    </form>
</div>
</body>
</html>