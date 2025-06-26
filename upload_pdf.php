<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

require_once '../config.php';
require_once '../vendor/autoload.php'; // pastikan composer autoload (untuk smalot/pdfparser)

use Smalot\PdfParser\Parser;

$feedback = '';

// Jika ada file yang diupload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['pdf_file'])) {
    $provider_id = $_POST['provider_id'] ?? '';
    if (empty($provider_id)) {
        $feedback = "Pilih provider terlebih dahulu.";
    } else {
        $file = $_FILES['pdf_file']['tmp_name'];

        // Parsing PDF
        $parser = new Parser();
        $pdf = $parser->parseFile($file);
        $text = $pdf->getText();

        // Ambil setiap baris
        $lines = explode("\n", $text);
        $inserted = 0;

        foreach ($lines as $line) {
            $codes = preg_split('/\s+/', trim($line)); // Pisahkan per spasi

            foreach ($codes as $code) {
                if (!empty($code)) {
                    // Simpan ke database
                    $stmt = $conn->prepare("INSERT INTO voucher (kode, provider_id, status) VALUES (?, ?, 'tersedia')");
                    $stmt->bind_param("si", $code, $provider_id);
                    if ($stmt->execute()) {
                        $inserted++;
                    }
                }
            }
        }

        $feedback = "$inserted kode voucher berhasil diunggah.";
    }
}

// Ambil daftar provider
$providers = $conn->query("SELECT * FROM provider ORDER BY nama ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Voucher PDF - Admin</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="container">
    <h2>Upload Voucher dari PDF</h2>
    <form method="post" enctype="multipart/form-data">
        <label>Pilih Provider</label><br>
        <select name="provider_id" required>
            <option value="">-- Pilih --</option>
            <?php while ($row = $providers->fetch_assoc()) : ?>
                <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['nama']) ?></option>
            <?php endwhile; ?>
        </select><br><br>

        <label>Pilih File PDF</label><br>
        <input type="file" name="pdf_file" accept=".pdf" required><br><br>

        <button type="submit">Upload</button>
    </form>
    <br>
    <p><strong><?= $feedback ?></strong></p>
</div>
</body>
</html>