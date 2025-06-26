<?php
session_start();
require_once "admin/config/db.php";

// Validasi input
if (!isset($_POST['id_paket']) || !isset($_POST['no_wa'])) {
    die("Data tidak lengkap.");
}

$id_paket = intval($_POST['id_paket']);
$no_wa = trim($_POST['no_wa']);

// Ambil data paket
$query = mysqli_query($conn, "SELECT * FROM harga_paket WHERE id = $id_paket");
$paket = mysqli_fetch_assoc($query);

if (!$paket) {
    die("Paket tidak ditemukan.");
}

// Ambil 1 voucher tersedia
$cekVoucher = mysqli_query($conn, "SELECT * FROM voucher WHERE provider = '{$paket['provider']}' AND status = 'tersedia' LIMIT 1");
$voucher = mysqli_fetch_assoc($cekVoucher);

if (!$voucher) {
    die("Maaf, stok voucher untuk provider ini habis.");
}

// Generate kode transaksi unik
$kode_transaksi = "BT" . time() . rand(10, 99);

// Simpan transaksi dengan status "pending"
mysqli_query($conn, "INSERT INTO transaksi 
(kode_transaksi, id_paket, nama_paket, provider, harga, no_wa, status, waktu, id_voucher)
VALUES 
('$kode_transaksi', '$id_paket', '{$paket['nama_paket']}', '{$paket['provider']}', '{$paket['harga']}', '$no_wa', 'pending', NOW(), '{$voucher['id']}')");

// Tandai voucher sebagai "digunakan sementara"
mysqli_query($conn, "UPDATE voucher SET status = 'digunakan' WHERE id = '{$voucher['id']}'");

// Kirim ke API Paydisini
$api_id = "3540";
$api_key = "33d421610d5b77e4811da18bfe3a53ee";
$callback_url = "https://butota.my.id/callback.php";

$payload = [
    'api_id' => $api_id,
    'api_key' => $api_key,
    'merchant_ref' => $kode_transaksi,
    'amount' => $paket['harga'],
    'customer_name' => "Pembeli BUTOTA",
    'customer_email' => "butota@store.com",
    'customer_phone' => $no_wa,
    'callback_url' => $callback_url,
    'return_url' => "https://butota.my.id/sukses.php?kode=$kode_transaksi",
];

// Kirim request CURL
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.paydisini.com/v1/transaction",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query($payload),
]);

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

if ($err) {
    die("Gagal konek ke Paydisini: $err");
}

$data = json_decode($response, true);
if (isset($data['data']['payment_url'])) {
    header("Location: " . $data['data']['payment_url']);
    exit;
} else {
    die("Terjadi kesalahan saat membuat transaksi.");
}