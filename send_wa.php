<?php
require_once "config/db.php";

// Ambil ID transaksi dari URL
if (!isset($_GET['id'])) {
    die("ID transaksi tidak ditemukan.");
}

$id = intval($_GET['id']);

// Ambil data transaksi
$trx = mysqli_query($conn, "SELECT t.*, h.nama_paket FROM transaksi t 
    JOIN harga_paket h ON t.id_paket = h.id 
    WHERE t.id = $id");
$data = mysqli_fetch_assoc($trx);

if (!$data) {
    die("Data transaksi tidak ditemukan.");
}

$nomor_wa = $data['nomor_wa'];
$voucher = $data['kode_voucher'];
$paket = $data['nama_paket'];

// Format pesan WhatsApp
$pesan = "*BUTOTA STORE*\n";
$pesan .= "Voucher Anda berhasil dikirim!\n\n";
$pesan .= "*Paket:* $paket\n";
$pesan .= "*Kode Voucher:* `$voucher`\n\n";
$pesan .= "Terima kasih telah membeli di BUTOTA STORE 😊";

// Kirim lewat API Wablas
$curl = curl_init();
$token = 'ISI_TOKEN_WABLAS_KAMU';

curl_setopt($curl, CURLOPT_URL, 'https://kirim.pesan.my.id/api/send-message');
curl_setopt($curl, CURLOPT_HTTPHEADER, [
    "Authorization: $token",
    "Content-Type: application/json"
]);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode([
    "phone" => $nomor_wa,
    "message" => $pesan
]));
$response = curl_exec($curl);
curl_close($curl);

// Cek hasil kirim
mysqli_query($conn, "UPDATE transaksi SET status = 'Terkirim' WHERE id = $id");

header("Location: transaksi.php");
exit;