<?php
session_start();
include "koneksi.php";

// Buat cart jika belum ada
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

//////////////////////////////////////////////////
// TAMBAH OBAT KE CART
//////////////////////////////////////////////////
if (isset($_POST['tambah'])) {

    $id_obat = $_POST['id_obat'];
    $jumlah = $_POST['jumlah'];

    $query = mysqli_query($conn, "SELECT * FROM obat WHERE id_obat='$id_obat'");
    $data = mysqli_fetch_assoc($query);

    if (!$data) {
        echo "<script>alert('Obat tidak ditemukan');</script>";
    } else {

        if ($jumlah > $data['stok']) {
            echo "<script>alert('Stok tidak mencukupi');</script>";
        } else {

            $subtotal = $data['harga'] * $jumlah;

            // Gunakan id_obat sebagai key (lebih stabil)
            $_SESSION['cart'][$id_obat] = [
                'id_obat' => $id_obat,
                'nama' => $data['nama'],
                'harga' => $data['harga'],
                'jumlah' => $jumlah,
                'subtotal' => $subtotal
            ];
        }
    }
}

//////////////////////////////////////////////////
// HAPUS ITEM
//////////////////////////////////////////////////
if (isset($_GET['hapus'])) {

    $id_obat = $_GET['hapus'];

    if (isset($_SESSION['cart'][$id_obat])) {
        unset($_SESSION['cart'][$id_obat]);
    }

    header("Location: kasir_transaksi.php");
    exit;
}

//////////////////////////////////////////////////
// EDIT ITEM
//////////////////////////////////////////////////
if (isset($_POST['edit'])) {

    $id_obat = $_POST['id_obat'];
    $jumlah_baru = $_POST['jumlah_baru'];

    if (isset($_SESSION['cart'][$id_obat])) {

        $query = mysqli_query($conn, "SELECT * FROM obat WHERE id_obat='$id_obat'");
        $data = mysqli_fetch_assoc($query);

        if ($jumlah_baru <= $data['stok']) {

            $harga = $data['harga'];
            $subtotal = $harga * $jumlah_baru;

            $_SESSION['cart'][$id_obat]['jumlah'] = $jumlah_baru;
            $_SESSION['cart'][$id_obat]['subtotal'] = $subtotal;

        } else {
            echo "<script>alert('Stok tidak cukup');</script>";
        }
    }
}

//////////////////////////////////////////////////
// PROSES BAYAR
//////////////////////////////////////////////////
if (isset($_POST['bayar'])) {

    if (!empty($_SESSION['cart'])) {

        $id_user = $_SESSION['id_user'];
        $total = 0;

        foreach ($_SESSION['cart'] as $item) {
            $total += $item['subtotal'];
        }

        // Simpan header transaksi
        mysqli_query($conn, "INSERT INTO transaksi (id_user, total) 
                             VALUES ('$id_user','$total')");
        $id_transaksi = mysqli_insert_id($conn);

        // Simpan detail
        foreach ($_SESSION['cart'] as $item) {

            mysqli_query($conn, "INSERT INTO detail_transaksi 
                (id_transaksi, id_obat, jumlah, harga, subtotal)
                VALUES 
                ('$id_transaksi','".$item['id_obat']."','".$item['jumlah']."','".$item['harga']."','".$item['subtotal']."')
            ");

            // Kurangi stok
            mysqli_query($conn, "UPDATE obat 
                                 SET stok = stok - ".$item['jumlah']." 
                                 WHERE id_obat='".$item['id_obat']."'");
        }

        unset($_SESSION['cart']);

        echo "<script>alert('Transaksi berhasil'); window.location='kasir_transaksi.php';</script>";
    } else {
        echo "<script>alert('Keranjang kosong');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/kasir_transaksi.css">
</head>
<body>
    <a href="kasir.php">Kembali ke Halaman Kasir</a>

<br><br>

<h2>Masukkan Transaksi</h2>

<!-- FORM TAMBAH OBAT -->
<form method="POST">
    <select name="id_obat" required>
        <option value="">Pilih Obat</option>
        <?php
        $obat = mysqli_query($conn, "SELECT * FROM obat ORDER BY nama ASC");
        while ($o = mysqli_fetch_assoc($obat)) {
            echo "<option value='".$o['id_obat']."'>
                ".$o['nama']." - Rp ".$o['harga']." (Stok: ".$o['stok'].")
            </option>";
        }
        ?>
    </select>

    <input type="number" name="jumlah" placeholder="Jumlah" min="1" required>
    <button type="submit" name="tambah">Tambah</button>
</form>

<hr>

<h3>Receipt Bawasis</h3>

<table border="1" width="100%">
    <tr>
        <th>Nama</th>
        <th>Harga</th>
        <th>Jumlah</th>
        <th>Subtotal</th>
        <th>Aksi</th>
    </tr>

<?php
$total = 0;

if (!empty($_SESSION['cart'])) {

    foreach ($_SESSION['cart'] as $item) {

        echo "<tr>
            <td>".$item['nama']."</td>
            <td>Rp ".$item['harga']."</td>
            <td>
                <form method='POST' style='display:inline;'>
                    <input type='hidden' name='id_obat' value='".$item['id_obat']."'>
                    <input type='number' name='jumlah_baru' value='".$item['jumlah']."' min='1' required>
                    <button type='submit' name='edit'>Update</button>
                </form>
            </td>
            <td>Rp ".$item['subtotal']."</td>
            <td>
                <a href='?hapus=".$item['id_obat']."' 
                   onclick='return confirm(\"Hapus item?\")'>
                   Hapus
                </a>
            </td>
        </tr>";

        $total += $item['subtotal'];
    }
}
?>

<tr>
    <td colspan="3"><b>Total Belanja</b></td>
    <td colspan="2"><b>Rp <?php echo $total; ?></b></td>
</tr>

</table>

<br>

<hr>

<h4>Total Belanja: Rp <?= number_format($total,0,',','.') ?></h4>

<form method="POST">

    <label>Uang Bayar</label>
    <input type="number" name="uang_bayar" id="uang_bayar"
           class="form-control"
           required
           onkeyup="hitungKembalian()">

    <br>

    <label>Kembalian</label>
    <input type="text" id="kembalian"
           class="form-control"
           readonly>

    <br>

    <button type="submit" name="bayar" class="btn btn-success">
        BAYAR
    </button>

</form>

</body>
</html>
