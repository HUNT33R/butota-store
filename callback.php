<?php
require_once "admin/config/db.php";

// Konfigurasi
$api_id_asli = "3540";
$api_key_asli = "33d421610d5b77e4811da18bfe3a53ee";
$wablas_url = "https://kirim.pesan.my.id/api/send-message";
$wablas_token = "ISI_TOKEN_WABLAS_ANDA"; // Ganti dengan token Wablas Anda

// Ambil data JSON dari callback
$payload = json_decode(file_get_contents('php://input'), true);

if (!$payload) {
    http_response_code(400);
    exit("Invalid payload.");
}

// Verifikasi API dari Paydisini
if ($payload['api_id'] != $api_id_asli || $payload['api_key'] != $api_key_asli) {
    http_response_code(403);
    exit("Unauthorized.");
}

// Ambil data transaksi
$kode = $payload['merchant_ref'];
$status = strtoupper($payload['status'] ?? '');

if ($status != "PAID") {
    exit("Status bukan PAID, tidak diproses.");
}

// Ambil transaksi dari database
$trx = mysqli_query($conn, "SELECT * FROM transaksi WHERE kode_transaksi = '$kode'");
$data = mysqli_fetch_assoc($trx);

if (!$data || $data['status'] == 'berhasil') {
    exit("Transaksi tidak ditemukan atau sudah diproses.");
}

// Ambil kode voucher
$id_voucher = $data['id_voucher'];
$voucher = mysqli_query($conn, "SELECT * FROM voucher WHERE id = '$id_voucher'");
$kode_voucher = mysqli_fetch_assoc($voucher)['kode'] ?? '';

if (!$kode_voucher) {
    exit("Voucher tidak ditemukan.");
}

// Update status transaksi & voucher
mysqli_query($conn, "UPDATE transaksi SET status = 'berhasil' WHERE kode_transaksi = '$kode'");
mysqli_query($conn, "UPDATE voucher SET status = 'terpakai' WHERE id = '$id_voucher'");

// Kirim pesan WhatsApp via Wablas
$pesan = "Terima kasih, pembayaran Anda berhasil ✅\n\nBerikut kode voucher WiFi Anda:\n\n🔑 *$kode_voucher*\n\nSelamat menggunakan layanan dari BUTOTA STORE!";

$wa_payload = [
    'phone' => $data['no_wa'],
    'message' => $pesan,
];

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $wablas_url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($wa_payload),
    CURLOPT_HTTPHEADER => [
        "Authorization: $wablas_token",
        "Content-Type: application/json"
    ]
]);

$response = curl_exec($curl);
curl_close($curl);

echo "Callback berhasil diproses.";