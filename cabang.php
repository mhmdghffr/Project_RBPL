<?php
session_start();

// proteksi login & role
if (!isset($_SESSION['login']) || $_SESSION['jabatan'] != 'managertoko') {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Manager Cabang</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/cabang.css">
</head>

<body>

<div class="container">

    <!-- PROFILE -->
    <div class="profile-box">
        <img src="img/cabang.jpeg" width="60"><br>
        <h2>Dashboard Manager Cabang</h2>
        <p>Selamat datang, <b><?= $_SESSION['username']; ?></b></p>
        <p>Manager Cabang</p>
        <p>Waktu: <span id="clock"></span></p>
    </div>

    <hr>

    <h3>Menu Utama</h3>

    <!-- LAPORAN PENJUALAN -->
    <div class="menu-card">
        <img src="img/laporan.jpeg" width="30">
        <h4>Laporan Penjualan</h4>
        <p>Melihat seluruh transaksi dari kasir</p>
        <a href="liat_penjualan.php">Buka</a>
    </div>

    <br>

    <!-- LAPORAN OBAT MASUK -->
    <div class="menu-card">
        <img src="img/obat.png" width="30">
        <h4>Laporan Obat Masuk</h4>
        <p>Monitoring obat dari produsen</p>
        <a href="liat_obat_masuk.php">Buka</a>
    </div>

    <br>

    <!-- LAPORAN STOK -->
    <div class="menu-card">
        <img src="img/stok.jpeg" width="30">
        <h4>Laporan Stok Obat</h4>
        <p>Melihat kondisi stok obat saat ini</p>
        <a href="liat_stok.php">Buka</a>
    </div>

    <br>

    <!-- LOGOUT -->
    <div class="menu-card">
        <img src="img/logout.png" width="30">
        <h4>Logout</h4>
        <p>Keluar dari sistem</p>
        <a href="logout.php">Keluar</a>
    </div>

</div>

<!-- JAM -->
<script>
function updateClock() {
    var now = new Date();
    document.getElementById('clock').innerHTML = now.toLocaleTimeString();
}
setInterval(updateClock, 1000);
</script>

</body>
</html>