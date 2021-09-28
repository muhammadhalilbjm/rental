<?php

require 'main/salam.php';

/* 
|
| Jika sudah melewati tantangan, maka ambil data
|
*/
$rows = tampilkanSemua("SELECT * FROM master_dvd");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main/css/style.css">
    <title>Rental DVD</title>
    <?php if( is_file('index.php')) : ?>
    <style>
        .home {
            background-color: rgba(58, 58, 58, .5);
            color: white;
        }
    </style>
    <?php endif; ?>
</head>
<body>

    <nav>
        <div class="container">
            <div class="nav-brand">
                <h1>Rental DVD</h1>                
            </div>
            <div class="nav-list">
                <a href="index.php" class="home">Home</a>
                <a href="daftar-pinjaman.php" class="pinjaman">Daftar Pinjaman</a>
                <a href="#" class="logout">Logout</a>
                <a href="akun.php" class="akun">Akun</a>
            </div>
        </div>
    </nav>

    <section class="main">
        <div class="container">
            <!-- sidebar -->
            <div class="sidebar">
                <h2>Daftar DVD</h2>
                <br>

                <?php foreach( $rows as $row ) : ?>
                <?php $judul = $row['judul']; ?>
                    <a href="#<?= $judul; ?>"><?= $row['judul']; ?></a>
                <?php endforeach; ?>

            </div>
            <!-- penutup sidebar -->


            <!-- content -->
            <div class="content">

                <!-- Mengulang semua isi data -->
                <?php foreach( $rows as $row ) : ?>
                <?php $tersedia = ($row['jumlah_tersedia'] > 0) ? $row['jumlah_tersedia'] : "kosong"; ?>
                <!-- Penutup logika -->

                <!-- Tempat menampilkan data -->
                <div class="box" id="<?= $row['judul']; ?>">
                    <h2><?= $row['judul'];  ?></h2>
                    <p>Genre: <?= $row['genre']; ?></p>
                    <p>Jumlah tersedia: <?= $tersedia; ?></p>
                    <div class="aksi">
                        <form action="" method="post">
                            <input type="hidden" name="kode_dvd" value="<?= $row['kode_dvd']; ?>">
                            <input type="hidden" name="judul" value="<?= $row['judul']; ?>">
                            <button type="submit" name="pinjam" class="pinjam">Pinjam</button>
                        </form>
                    </div>
                </div>
                <!-- penutup menampilkan data -->

                <?php endforeach; ?>

            </div>
            <!-- Penutup konten -->


        </div>
        <!-- Penutup kontainer -->
    </section>

    <script src="main/js/my.js"></script>
    <script src="main/js/alert.js"></script>
</body>
</html>

<?php

if(isset($_POST['pinjam'])) {

    pinjam($_POST, $id_user);

}

?>
