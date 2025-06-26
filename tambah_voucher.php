<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

require_once "config/db.php";

// Ambil daftar paket
$paket = mysqli_query($conn, "SELECT * FROM harga_paket ORDER BY provider, nama_paket");

// Proses upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_paket = intval($_POST['id_paket']);
    $tipe_file = $_FILES['file']['type'];
    $file_tmp = $_FILES['file']['tmp_name'];
    $kodeList = [];

    if (strpos($tipe_file, 'text') !== false) {
        // .txt file
        $lines = file($file_tmp, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $kode = trim($line);
            if (!empty($kode)) $kodeList[] = $kode;
        }
    } elseif (strpos($tipe_file, 'pdf') !== false) {
        // .pdf file
        require_once "../vendor/autoload.php";
        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile($file_tmp);
        $text = $pdf->getText();
        $lines = explode("\n", $text);
        foreach ($lines as $line) {
            $parts = preg_split('/\s+/', trim($line)); // 5 kode per baris
            foreach ($parts as $kode) {
                if (!empty($kode)) $kodeList[] = trim($kode);
            }
        }
    }

    // Simpan ke DB
    $inserted = 0;
    foreach ($kodeList as $kode) {
        $kode = mysqli_real_escape_string($conn, $kode);
        // Hindari duplikat
        $cek = mysqli_query($conn, "SELECT id FROM voucher WHERE kode = '$kode' AND id_paket = $id_paket");
        if (mysqli_num_rows($cek) == 0) {
            mysqli_query($conn, "INSERT INTO voucher (id_paket, kode, status) VALUES ($id_paket, '$kode', 'tersedia')");
            $inserted++;
        }
    }

    header("Location: voucher.php?upload=success&jumlah=$inserted");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Upload Voucher</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="admin-container">
    <h2>Upload Voucher</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="id_paket">Pilih Paket:</label>
        <select name="id_paket" id="id_paket" required>
            <option value="">-- Pilih Paket --</option>
            <?php while ($p = mysqli_fetch_assoc($paket)): ?>
                <option value="<?= $p['id'] ?>">
                    <?= $p['provider'] ?> - <?= $p['nama_paket'] ?> (Rp<?= number_format($p['harga'], 0, ',', '.') ?>)
                </option>
            <?php endwhile; ?>
        </select>

        <label for="file">Upload File Voucher (.txt atau .pdf):</label>
        <input type="file" name="file" accept=".txt,.pdf" required>

        <button type="submit" class="btn">Upload Sekarang</button>
    </form>

    <br><a href="voucher.php">← Kembali ke Data Voucher</a>
</div>
</body>
</html>