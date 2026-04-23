<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['jabatan'] != 'kasir') {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Kasir</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/kasir.css">
</head>

<body>

<div class="container">

    <!-- PROFILE -->
    <div class="profile-box">
        <img src="img/kasir.png" class="profile-img">

        <div class="profile-text">
            <h3><?= $_SESSION['username']; ?></h3>
            <p>Kasir Apotek</p>
            <span id="clock"></span>
        </div>
    </div>

    <!-- MENU 1 -->
    <div class="menu-card">

        <div class="menu-content">
            <div class="icon-box">
                <img src="img/riwayat_transaksi.png" alt="">
            </div>

            <div class="menu-text">
                <h3>Riwayat Transaksi</h3>
                <p>Kelola riwayat transaksi</p>
            </div>
        </div>

        <a href="riwayat_transaksi.php" class="btn btn-dark">Lihat</a>
    </div>

    <!-- MENU 2 -->
    <div class="menu-card">

        <div class="menu-content">
            <div class="icon-box">
                <img src="img/transaksi.png" alt="">
            </div>

            <div class="menu-text">
                <h3>Transaksi</h3>
                <p>Input dan proses transaksi</p>
            </div>
        </div>

        <a href="kasir_transaksi.php" class="btn btn-dark">Bayar</a>
    </div>

    <!-- MENU 3 -->
    <div class="menu-card">

        <div class="menu-content">
            <div class="icon-box">
                <img src="img/logout.png" alt="">
            </div>

            <div class="menu-text">
                <h3>Terima Kasih</h3>
                <p>Terimakasih menggunakan layanan kami</p>
            </div>
        </div>

        <a href="logout.php" class="btn btn-red">Logout</a>
    </div>

</div>

<!-- JAM REALTIME -->
<script>
function updateClock(){
    const now = new Date();

    const jam = String(now.getHours()).padStart(2,'0');
    const menit = String(now.getMinutes()).padStart(2,'0');
    const detik = String(now.getSeconds()).padStart(2,'0');

    document.getElementById("clock").innerText =
        jam + ":" + menit + ":" + detik;
}

setInterval(updateClock,1000);
updateClock();
</script>

</body>
</html>
