<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include '../config.php';

if (!isset($_GET['id'])) {
    header("Location: voucher.php");
    exit;
}

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM voucher WHERE id = '$id'");
$voucher = mysqli_fetch_assoc($query);

if (!$voucher) {
    header("Location: voucher.php");
    exit;
}

// Ambil semua provider
$providers = mysqli_query($conn, "SELECT * FROM providers");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kode = trim($_POST['kode']);
    $provider_id = $_POST['provider_id'];

    $update = mysqli_query($conn, "UPDATE voucher SET kode='$kode', provider_id='$provider_id' WHERE id='$id'");

    if ($update) {
        header("Location: voucher.php?success=1");
        exit;
    } else {
        $error = "Gagal memperbarui voucher.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Voucher</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Edit Kode Voucher</h2>
    <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
    <form method="POST">
        <label>Kode Voucher</label>
        <input type="text" name="kode" value="<?= htmlspecialchars($voucher['kode']) ?>" required><br>

        <label>Provider</label>
        <select name="provider_id" required>
            <?php while ($p = mysqli_fetch_assoc($providers)) { ?>
                <option value="<?= $p['id'] ?>" <?= $voucher['provider_id'] == $p['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($p['nama']) ?>
                </option>
            <?php } ?>
        </select><br>

        <button type="submit">Simpan Perubahan</button>
        <a href="voucher.php">Batal</a>
    </form>
</div>
</body>
</html>