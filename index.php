<?php
session_start();
include 'koneksi.php';

$error = "";

if (isset($_POST['login'])) {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    $user  = mysqli_fetch_assoc($query);

    if ($user && $password === $user['password']) {

        $_SESSION['login']    = true;
        $_SESSION['id_user']  = $user['id_user'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['jabatan']  = $user['jabatan'];

        if ($user['jabatan'] == 'managertoko') {
            header("Location: cabang.php");
        } elseif ($user['jabatan'] == 'managergudang') {
            header("Location: gudang.php");
        } elseif ($user['jabatan'] == 'kasir') {
            header("Location: kasir.php");
        }
        exit;

    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
      <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <div class="container">
        <img src="img/logo.png" alt="" width="290px">
    <div class="card">
    <div class="login-box">
        <h2>Login</h2> <br>
        <?php if ($error): ?>
            <div 
            class="error"><?= $error ?>
            </div>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required> <br>
            <button name="login">Login</button>
        </form>
    </div>
    </div>
    </div>
</body>
</html>