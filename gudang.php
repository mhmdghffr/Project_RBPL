<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Manager Gudang</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/gudang.css">
</head>

<body>
    <?php
    session_start();
    if (!isset($_SESSION['login']) || $_SESSION['jabatan'] != 'managergudang') {
        header("Location: index.php");
        exit;
    }
    ?>

    <div class="container">

        <!-- PROFILE -->
        <div class="profile-box">
            <img src="img/manager_gudang.jpeg" class="profile-img">

            <div class="profile-text">
                <h3><?= $_SESSION['username']; ?></h3>
                <p>Manager Gudang</p>
                <span id="clock"></span>
            </div>
        </div>

        <!-- MENU 1 -->
        <div class="menu-card">
            <div class="menu-content">
                <div class="icon-box">
                    <img src="img/obat.png" alt="">
                </div>

                <div class="menu-text">
                    <h3>Data Obat</h3>
                    <p>Kelola data obat</p>
                </div>
            </div>

            <a href="obat.php">Masuk</a>
        </div>

        <!-- MENU 2 -->
        <div class="menu-card">
            <div class="menu-content">
                <div class="icon-box">
                    <img src="img/stok.jpeg" alt="">
                </div>

                <div class="menu-text">
                    <h3>Manajemen Stok</h3>
                    <p>Update dan kontrol stok</p>
                </div>
            </div>

            <a href="obat_masuk.php">Masuk</a>
        </div>

        <!-- MENU 3 -->
        <div class="menu-card">
            <div class="menu-content">
                <div class="icon-box">
                    <img src="img/laporan.jpeg" alt="">
                </div>

                <div class="menu-text">
                    <h3>Laporan obat</h3>
                    <p>Lihat laporan obat masuk</p>
                </div>
            </div>

            <a href="laporan_obat_masuk.php">Lihat</a>
        </div>

        <!-- MENU 4 -->
        <div class="menu-card">
            <div class="menu-content">
                <div class="icon-box">
                    <img src="img/logout.png" alt="">
                </div>

                <div class="menu-text">
                    <h3>Logout</h3>
                    <p>Keluar dari sistem</p>
                </div>
            </div>

            <a href="logout.php">Logout</a>
        </div>

    </div>

    <!-- JAM REALTIME -->
    <script>
    function updateClock() {
        var now = new Date();
        document.getElementById('clock').innerHTML =
            now.toLocaleTimeString();
    }
    setInterval(updateClock, 1000);
    </script>

</body>
</html>