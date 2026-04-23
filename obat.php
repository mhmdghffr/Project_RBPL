<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar obat</title>
    <link rel="stylesheet" href="css/obat.css">
</head>
<body>
<?php
session_start();
include 'koneksi.php';

// CEK AKSES
if (!isset($_SESSION['id_user']) || $_SESSION['jabatan'] != 'managergudang') {
    header("Location: login.php");
}

// AMBIL DATA OBAT
$data = mysqli_query($conn, "SELECT * FROM obat");
?>

<a href="gudang.php">Kembali</a>
<br>

<h2>Data Obat</h2>

<table>
<tr>
    <th>No</th>
    <th>Nama</th>
    <th>Produsen</th>
    <th>Stok</th>
    <th>Harga</th>
</tr>

<?php 
$no = 1;
while($d = mysqli_fetch_assoc($data)) { 
?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= $d['nama'] ?></td>
    <td><?= $d['produsen'] ?></td>
    <td><?= $d['stok'] ?></td>
    <td><?= $d['harga'] ?></td>
</tr>
<?php } ?>

</table>

</body>
</html>
