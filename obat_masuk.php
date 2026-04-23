<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Obat masuk</title>
    <link rel="stylesheet" href="css/obat_masuk.css">
</head>
<body>
    <?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user']) || $_SESSION['jabatan'] != 'managergudang') {
    header("Location: login.php");
    exit;
}

// TAMBAH OBAT BARU
if (isset($_POST['tambah_obat'])) {
    $nama = $_POST['nama'];
    $produsen = $_POST['produsen'];
    $harga = $_POST['harga'];

    mysqli_query($conn, "INSERT INTO obat (nama, produsen, stok, harga)
    VALUES ('$nama','$produsen',0,'$harga')");
}

// SIMPAN TRANSAKSI
if (isset($_POST['simpan'])) {
    $tanggal = date('Y-m-d H:i:s');
    $keterangan = $_POST['keterangan'];

    mysqli_query($conn, "INSERT INTO obat_masuk (tanggal, keterangan)
    VALUES ('$tanggal','$keterangan')");

    $id_masuk = mysqli_insert_id($conn);

    foreach ($_POST['id_obat'] as $key => $id_obat) {
        $jumlah = $_POST['jumlah'][$key];

        if ($id_obat != "" && $jumlah > 0) {

            // simpan detail
            mysqli_query($conn, "INSERT INTO detail_obat_masuk (id_masuk, id_obat, jumlah)
            VALUES ('$id_masuk','$id_obat','$jumlah')");

            // update stok
            mysqli_query($conn, "UPDATE obat SET stok = stok + $jumlah WHERE id_obat='$id_obat'");
        }
    }

    echo "Data berhasil disimpan!";
}

// ambil obat
$obat = mysqli_query($conn, "SELECT * FROM obat");
?>

<a href="gudang.php">Kembali</a>
<br>

<h2>Input Obat Masuk (Multi Item)</h2>

<form method="POST">

    Keterangan:
    <input type="text" name="keterangan"><br><br>

    <table border="1" id="tabel">
        <tr>
            <th>Obat</th>
            <th>Jumlah</th>
        </tr>

        <tr>
            <td>
                <select name="id_obat[]">
                    <option value="">--Pilih--</option>
                    <?php while($o = mysqli_fetch_assoc($obat)) { ?>
                        <option value="<?= $o['id_obat'] ?>">
                            <?= $o['nama'] ?>
                        </option>
                    <?php } ?>
                </select>
            </td>
            <td><input type="number" name="jumlah[]"></td>
        </tr>
    </table>

    <br>
    <button type="button" onclick="tambahBaris()">+ Tambah Baris</button>

    <br><br>
    <button name="simpan">Simpan</button>
</form>

<hr>

<h3>Tambah Obat Baru</h3>
<form method="POST">
    Nama: <input type="text" name="nama"><br>
    Produsen: <input type="text" name="produsen"><br>
    Harga: <input type="number" name="harga"><br>
    <button name="tambah_obat">Tambah Obat</button>
</form>

<script>
function tambahBaris() {
    var table = document.getElementById("tabel");
    var row = table.insertRow();

    var cell1 = row.insertCell(0);
    var cell2 = row.insertCell(1);

    cell1.innerHTML = table.rows[1].cells[0].innerHTML;
    cell2.innerHTML = '<input type="number" name="jumlah[]">';
}
</script>
</body>
</html>
