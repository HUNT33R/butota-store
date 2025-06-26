<?php
require_once "admin/config/db.php";

// Baca JSON dari Paydisini
$raw = file_get_contents("php://input");
$data = json_decode($raw, true);

// Validasi data
if (!$data || !isset($data['merchant_ref'], $data['status'])) {
    http_response_code(400);
    echo "Invalid payload";
    exit;
}

$kode_transaksi = $data['merchant_ref'];
$status = $data['status'];

// Hanya proses jika status = 'PAID'
if ($status !== 'PAID') {
    http_response_code(200);
    echo "Not paid yet";
    exit;
}

// Ambil transaksi
$query = mysqli_query($conn, "SELECT * FROM transaksi WHERE kode_transaksi = '$kode_transaksi' LIMIT 1");
$transaksi = mysqli_fetch_assoc($query);

if (!$transaksi) {
    http_response_code(404);
    echo "Transaksi tidak ditemukan";
    exit;
}

if ($transaksi['status'] === 'berhasil') {
    http_response_code(200);
    echo "Sudah diproses";
    exit;
}

// Update status transaksi & voucher
mysqli_query($conn, "UPDATE transaksi SET status = 'berhasil' WHERE kode_transaksi = '$kode_transaksi'");
mysqli_query($conn, "UPDATE voucher SET status = 'terpakai' WHERE kode = '{$transaksi['kode_voucher']}'");

// Kirim voucher via Wablas
$token = "ISI_TOKEN_WABLAS_KAMU"; // Ganti dengan token API Wablas kamu
$phone = $transaksi['no_wa'];
$voucher = $transaksi['kode_voucher'];

$pesan = "Terima kasih sudah membeli voucher WiFi di BUTOTA STORE ✅\n\nBerikut voucher Anda:\n🔐 *$voucher*\n\nSelamat menikmati internet cepat!";

$payload = [
    "phone" => $phone,
    "message" => $pesan
];

$ch = curl_init("https://console.wablas.com/api/send-message");
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: $token",
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);

// Selesai
http_response_code(200);
echo "Sukses";