<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

require_once "config/db.php";

if (!isset($_GET['id'])) {
    die("ID transaksi tidak ditemukan.");
}

$id = intval($_GET['id']);
mysqli_query($conn, "DELETE FROM transaksi WHERE id = $id");

header("Location: transaksi.php?hapus=success");
exit;