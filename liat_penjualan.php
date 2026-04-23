<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan penjualan</title>
    <link rel="stylesheet" href="css/liat_penjualan.css">
</head>
<body>
    
</body>
</html>
<?php
session_start();
include 'koneksi.php';

// proteksi role
if (!isset($_SESSION['login']) || $_SESSION['jabatan'] != 'managertoko') {
    header("Location: index.php");
    exit;
}

// filter tanggal (opsional)
$where = "";
if (!empty($_GET['dari']) && !empty($_GET['sampai'])) {
    $dari = $_GET['dari'];
    $sampai = $_GET['sampai'];
    $where = "WHERE DATE(tanggal) BETWEEN '$dari' AND '$sampai'";
}

// query rekap per hari
$data = mysqli_query($conn, "
SELECT 
    DATE(tanggal) as tgl,
    SUM(total) as total_harian,
    COUNT(id_transaksi) as jumlah_transaksi
FROM transaksi
$where
GROUP BY DATE(tanggal)
ORDER BY tgl DESC
");
?>

<h2>Laporan Penjualan Harian</h2>

<a href="cabang.php">Kembali</a>
<br>
<br>
<!-- FILTER -->
<form method="GET">
    Dari: <input type="date" name="dari">
    Sampai: <input type="date" name="sampai">
    <button>Tampilkan</button>
</form>

<br>

<table border="1">
<tr>
    <th>Tanggal</th>
    <th>Jumlah Transaksi</th>
    <th>Total Penjualan</th>
</tr>

<?php while($d = mysqli_fetch_assoc($data)) { ?>
<tr>
    <td><?= date('d-m-Y', strtotime($d['tgl'])) ?></td>
    <td><?= $d['jumlah_transaksi'] ?></td>
    <td>Rp <?= number_format($d['total_harian'],0,',','.') ?></td>
</tr>
<?php } ?>
</table>

