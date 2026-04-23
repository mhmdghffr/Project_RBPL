<?php
include 'koneksi.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi Harian</title>
    <link rel="stylesheet" href="css/riwayat_transaksi.css">
</head>
<body>

<h2>Laporan Transaksi Harian</h2>

<a href="kasir.php">Kembali ke Halaman Kasir</a>

<!-- FILTER -->
<form method="GET">
    <label>Pilih Tanggal:</label>
    <input type="date" name="tanggal" value="<?= isset($_GET['tanggal']) ? $_GET['tanggal'] : '' ?>">
    <button type="submit">Filter</button>
    <a href="riwayat_transaksi.php">Reset</a>
</form>

<br>

<?php

// FILTER QUERY
$where = "";
if (isset($_GET['tanggal']) && $_GET['tanggal'] != "") {
    $tanggal = $_GET['tanggal'];
    $where = "WHERE DATE(t.tanggal) = '$tanggal'";
}

// QUERY REKAP OBAT PER HARI
$query = mysqli_query($conn, "
    SELECT 
        DATE(t.tanggal) as tanggal,
        o.nama,
        SUM(dt.jumlah) as total_obat
    FROM transaksi t
    JOIN detail_transaksi dt ON t.id_transaksi = dt.id_transaksi
    JOIN obat o ON dt.id_obat = o.id_obat
    $where
    GROUP BY DATE(t.tanggal), o.id_obat
    ORDER BY tanggal DESC
");

// CEK DATA
if (mysqli_num_rows($query) == 0) {
    echo "<p>Tidak ada data</p>";
} else {

    $current_date = "";

    while($data = mysqli_fetch_assoc($query)) {

        // JIKA TANGGAL BERUBAH
        if ($current_date != $data['tanggal']) {

            // tutup tabel sebelumnya
            if ($current_date != "") {
                echo "</table>";
            }

            $current_date = $data['tanggal'];

            // TOTAL UANG PER HARI
            $total_query = mysqli_query($conn, "
                SELECT SUM(total) as total
                FROM transaksi
                WHERE DATE(tanggal) = '$current_date'
            ");
            $total_data = mysqli_fetch_assoc($total_query);
?>

            <hr>
            <h4>Tanggal: <?= $current_date; ?></h4>
            <p><b>Total Uang: Rp <?= number_format($total_data['total'],0,',','.'); ?></b></p>

            <table>
                <tr>
                    <th>Nama Obat</th>
                    <th>Jumlah Keluar</th>
                </tr>

<?php
        }
?>

        <tr>
            <td><?= $data['nama']; ?></td>
            <td><?= $data['total_obat']; ?></td>
        </tr>

<?php
    }

    echo "</table>";
}
?>

</body>
</html>