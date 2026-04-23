<?php
session_start();
include 'koneksi.php';

$tanggal = date('Y-m-d');

if (isset($_GET['tanggal'])) {
    $tanggal = $_GET['tanggal'];
}

$query = mysqli_query($conn, "
    SELECT * FROM transaksi
    WHERE tanggal = '$tanggal'
    ORDER BY id_transaksi DESC
");

$total_pemasukan = 0;
$total_transaksi = mysqli_num_rows($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan Harian</title>
    <link rel="stylesheet" href="css/laporan_penjualan.css">
</head>
<body>

<h3>Laporan Penjualan Harian</h3>

<form method="GET" class="row mb-3">
    <div class="col-md-4">
        <label>Pilih Tanggal</label>
        <input type="date" name="tanggal"
               class="form-control"
               value="<?= $tanggal ?>">
    </div>
    <div class="col-md-2 align-self-end">
        <button class="btn btn-primary">Tampilkan</button>
    </div>
</form>

<div class="card">
    <div class="card-body">

        <h5>Tanggal: <?= date('d-m-Y', strtotime($tanggal)) ?></h5>

        <table class="table table-bordered table-striped mt-3">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>ID Transaksi</th>
                    <th>Total</th>
                    <th>Uang Bayar</th>
                    <th>Kembalian</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                while ($row = mysqli_fetch_assoc($query)) :
                    $total_pemasukan += $row['total'];
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $row['id_transaksi'] ?></td>
                    <td>Rp <?= number_format($row['total'],0,',','.') ?></td>
                    <td>Rp <?= number_format($row['uang_bayar'],0,',','.') ?></td>
                    <td>Rp <?= number_format($row['kembalian'],0,',','.') ?></td>
                </tr>
                <?php endwhile; ?>

                <?php if ($total_transaksi == 0) : ?>
                <tr>
                    <td colspan="5" class="text-center">
                        Tidak ada transaksi pada tanggal ini
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <hr>

        <h5>Total Transaksi: <?= $total_transaksi ?></h5>
        <h4>Total Penjualan: Rp <?= number_format($total_pemasukan,0,',','.') ?></h4>

    </div>
</div>

</body>
</html>