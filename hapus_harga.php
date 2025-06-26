<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include '../config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $query = "DELETE FROM harga WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        header("Location: harga.php?hapus=berhasil");
    } else {
        header("Location: harga.php?hapus=gagal");
    }
} else {
    header("Location: harga.php");
}
exit;
?>