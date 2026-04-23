<?php
session_start();
include 'koneksi.php';

// proteksi login & role
if (!isset($_SESSION['login']) || $_SESSION['jabatan'] != 'managertoko') {
    header("Location: index.php");
    exit;
}

// pencarian
$where = "";
$cari = "";
if (!empty($_GET['cari'])) {
    $cari = $_GET['cari'];
    $where = "WHERE nama LIKE '%$cari%'";
}

// ambil data
$data = mysqli_query($conn, "
SELECT * FROM obat
$where
ORDER BY id_obat ASC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Laporan Stok Obat</title>
    <link rel="stylesheet" href="css/liat_stok.css">
</head>
<body>

<h2>Laporan Stok Obat</h2>
<a href="cabang.php">Kembali</a>
<br>
<br>

<!-- FORM CARI -->
<form method="GET">
    Cari Obat: 
    <input type="text" name="cari" value="<?= $cari ?>" placeholder="Nama obat...">
    
    <button type="submit">Cari</button>

    <!-- 🔄 RESET -->
    <a href="liat_stok.php">
        <button type="button">Reset</button>
    </a>
</form>

<br>

<table border="1" cellpadding="5" cellspacing="0">
<tr>
    <th>ID</th>
    <th>Nama Obat</th>
    <th>Produsen</th>
    <th>Harga</th>
    <th>Stok</th>
    <th>Status</th>
</tr>

<?php while($d = mysqli_fetch_assoc($data)) { ?>
<tr>
    <td><?= $d['id_obat'] ?></td>
    <td><?= $d['nama'] ?></td>
    <td><?= $d['produsen'] ?></td>
    <td>Rp <?= number_format($d['harga'],0,',','.') ?></td>
    <td><?= $d['stok'] ?></td>
    <td>
        <?php 
        if ($d['stok'] == 0) {
            echo "Habis";
        } elseif ($d['stok'] < 10) {
            echo "Hampir Habis";
        } else {
            echo "Tersedia";
        }
        ?>
    </td>
</tr>
<?php } ?>

</table>

</body>
</html>