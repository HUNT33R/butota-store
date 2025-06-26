<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

require_once "config/db.php";

if (!isset($_GET['id'])) {
    die("ID voucher tidak ditemukan.");
}

$id = intval($_GET['id']);
mysqli_query($conn, "DELETE FROM voucher WHERE id = $id");

header("Location: voucher.php?hapus=success");
exit;