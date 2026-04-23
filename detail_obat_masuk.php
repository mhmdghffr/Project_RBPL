<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail obat masuk</title>
    <link rel="stylesheet" href="css/detail_obat_masuk.css">
</head>
<body>
    <?php
    session_start();
    include 'koneksi.php';

    // proteksi role
    if (!isset($_SESSION['login']) || $_SESSION['jabatan'] != 'managertoko') {
        header("Location: index.php");
        exit;
    }

    // ambil tanggal dari URL
    $tgl = $_GET['tgl'];

    // query detail
    $data = mysqli_query($conn, "
    SELECT 
        obat_masuk.tanggal,
        obat_masuk.keterangan,
        obat.nama,
        obat.produsen,
        detail_obat_masuk.jumlah
    FROM detail_obat_masuk
    JOIN obat_masuk ON detail_obat_masuk.id_masuk = obat_masuk.id_masuk
    JOIN obat ON detail_obat_masuk.id_obat = obat.id_obat
    WHERE DATE(obat_masuk.tanggal) = '$tgl'
    ORDER BY obat_masuk.tanggal DESC
    ");
    ?>

    <h2>Detail Obat Masuk - <?= date('d-m-Y', strtotime($tgl)) ?></h2>

    <a href="liat_obat_masuk.php">Kembali</a>
    <br>
    <br>

    <table border="1">
    <tr>
        <th>Tanggal</th>
        <th>Nama Obat</th>
        <th>Produsen</th>
        <th>Jumlah</th>
        <th>Keterangan</th>
    </tr>

    <?php while($d = mysqli_fetch_assoc($data)) { ?>
    <tr>
        <td><?= $d['tanggal'] ?></td>
        <td><?= $d['nama'] ?></td>
        <td><?= $d['produsen'] ?></td>
        <td><?= $d['jumlah'] ?></td>
        <td><?= $d['keterangan'] ?></td>
    </tr>
    <?php } ?>
    </table>

</body>
</html>