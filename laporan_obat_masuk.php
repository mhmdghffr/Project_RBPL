<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user']) || $_SESSION['jabatan'] != 'managergudang') {
    header("Location: login.php");
    exit;
}

$data = mysqli_query($conn, "
SELECT 
    obat_masuk.id_masuk,
    obat_masuk.tanggal,
    obat_masuk.keterangan,
    obat.nama,
    detail_obat_masuk.jumlah
FROM detail_obat_masuk
JOIN obat_masuk ON detail_obat_masuk.id_masuk = obat_masuk.id_masuk
JOIN obat ON detail_obat_masuk.id_obat = obat.id_obat
ORDER BY obat_masuk.tanggal DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>laporan_obat_masuk</title>
    <link rel="stylesheet" href="css/laporan_obat_masuk.css">
</head>
<body>
    <h2>Laporan Obat Masuk</h2>

<table border="1">
<tr>
    <th>ID Transaksi</th>
    <th>Tanggal</th>
    <th>Nama Obat</th>
    <th>Jumlah</th>
    <th>Keterangan</th>
</tr>

<?php while($d = mysqli_fetch_assoc($data)) { ?>
<tr>
    <td><?= $d['id_masuk'] ?></td>
    <td><?= $d['tanggal'] ?></td>
    <td><?= $d['nama'] ?></td>
    <td><?= $d['jumlah'] ?></td>
    <td><?= $d['keterangan'] ?></td>
</tr>
<?php } ?>
</table>

<br>
<a href="gudang.php">Kembali</a>
</body>
</html>

