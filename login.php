<?php
session_start();
require '../config.php';

$error = "";

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM admin WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) === 1) {
        $admin = mysqli_fetch_assoc($result);
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin'] = $admin['username'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Password salah.";
        }
    } else {
        $error = "Username tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin - BUTOTA STORE</title>
    <link rel="stylesheet" href="../style.css"> <!-- pastikan file CSS ini ada -->
    <style>
        body {
            background: #1a1a2e;
            color: #fff;
            font-family: 'Segoe UI', sans-serif;
        }
        .login-box {
            width: 400px;
            margin: 100px auto;
            padding: 30px;
            background: #222;
            border-radius: 10px;
            box-shadow: 0 0 20px #4e0eff;
        }
        .login-box h2 {
            text-align: center;
            color: #fff;
        }
        .login-box input[type="text"], .login-box input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: none;
            border-radius: 6px;
        }
        .login-box input[type="submit"] {
            width: 100%;
            padding: 12px;
            background: #4e0eff;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        .login-box .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Login Admin</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="Username admin" required>
        <input type="password" name="password" placeholder="Password" required>
        <?php if ($error) echo "<div class='error'>$error</div>"; ?>
        <input type="submit" name="login" value="Masuk">
    </form>
</div>

</body>
</html>