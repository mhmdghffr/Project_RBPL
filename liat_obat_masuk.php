<?php
session_start();
include 'koneksi.php';

// proteksi login & role
if (!isset($_SESSION['login']) || $_SESSION['jabatan'] != 'managertoko') {
    header("Location: index.php");
    exit;
}

// filter tanggal
$where = "";
if (!empty($_GET['dari']) && !empty($_GET['sampai'])) {
    $dari = $_GET['dari'];
    $sampai = $_GET['sampai'];
    $where = "WHERE DATE(obat_masuk.tanggal) BETWEEN '$dari' AND '$sampai'";
}

// query rekap harian
$data = mysqli_query($conn, "
SELECT 
    DATE(obat_masuk.tanggal) as tgl,
    SUM(detail_obat_masuk.jumlah) as total_obat,
    COUNT(DISTINCT obat_masuk.id_masuk) as jumlah_transaksi
FROM detail_obat_masuk
JOIN obat_masuk ON detail_obat_masuk.id_masuk = obat_masuk.id_masuk
$where
GROUP BY DATE(obat_masuk.tanggal)
ORDER BY tgl DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Laporan Obat Masuk</title>
    <link rel="stylesheet" href="css/liat_obat_masuk.css">
</head>
<body>

<h2>Laporan Obat Masuk (Harian)</h2>

<a href="cabang.php">Kembali</a>
<br>
<br>

<!-- FILTER -->
<form method="GET">
    Dari: <input type="date" name="dari">
    Sampai: <input type="date" name="sampai">
    <button type="submit">Tampilkan</button>
</form>

<br>

<table border="1" cellpadding="5" cellspacing="0">
<tr>
    <th>Tanggal</th>
    <th>Jumlah Transaksi</th>
    <th>Total Obat Masuk</th>
</tr>

<?php while($d = mysqli_fetch_assoc($data)) { ?>
<tr>
    <td>
        <a href="detail_obat_masuk.php?tgl=<?= $d['tgl'] ?>">
            <?= date('d-m-Y', strtotime($d['tgl'])) ?>
        </a>
    </td>
    <td><?= $d['jumlah_transaksi'] ?></td>
    <td><?= $d['total_obat'] ?></td>
</tr>
<?php } ?>

</table>

</body>
</html>