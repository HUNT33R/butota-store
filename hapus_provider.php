<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include '../config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Hapus provider
    $query = "DELETE FROM providers WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        header("Location: provider.php?hapus=berhasil");
        exit;
    } else {
        header("Location: provider.php?hapus=gagal");
        exit;
    }
} else {
    header("Location: provider.php");
    exit;
}
?>