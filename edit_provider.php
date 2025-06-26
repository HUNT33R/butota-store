<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include '../config.php';

if (!isset($_GET['id'])) {
    header("Location: provider.php");
    exit;
}

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM providers WHERE id = '$id'");
$provider = mysqli_fetch_assoc($query);

if (!$provider) {
    header("Location: provider.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $icon = $_POST['icon'];

    $update = mysqli_query($conn, "UPDATE providers SET nama='$nama', icon='$icon' WHERE id='$id'");

    if ($update) {
        header("Location: provider.php?success=1");
        exit;
    } else {
        $error = "Gagal memperbarui data provider.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Provider</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Edit Provider</h2>
        <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
        <form method="POST">
            <label>Nama Provider</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($provider['nama']) ?>" required><br>
            <label>Link Icon (Opsional)</label>
            <input type="text" name="icon" value="<?= htmlspecialchars($provider['icon']) ?>"><br>
            <button type="submit">Simpan Perubahan</button>
            <a href="provider.php">Batal</a>
        </form>
    </div>
</body>
</html>