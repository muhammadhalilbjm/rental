<?php

// Sesion
require 'main/salam.php';

// ambil data
$rows = tampilkanSemua("SELECT * FROM master_peminjaman WHERE id_user = '$id_user'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main/css/style.css">
    <title>Rental DVD</title>
    <?php if( is_file('daftar-pinjaman.php')) : ?>
    <style>
        .pinjaman {
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

            <!-- Sidebar -->
            <div class="sidebar">
                <?php if(count($rows) > 0 ) : ?>
                    <h2>Daftar pinjaman DVD</h2>
                    <br>

                    <?php foreach( $rows as $row ) : ?>

                        <?php $judul = $row['judul']; ?>
                        <a href="#<?= $judul; ?>"><?= $row['judul']; ?></a>

                    <?php endforeach; ?>

                <?php endif; ?>
            </div>
            <!-- Penutup sidebar -->

            <!-- main konten -->
            <div class="content">

                <!-- Jika belum meminjam DVD -->
                <?php if(count($rows) < 1 ) : ?>
                    <div class="box belum" style="width: max-content; padding-bottom: 25px;">
                        <h1>Anda belum menyewa DVD satupun</h1>
                    </div>

                <?php else : ?>

                    <!-- Menampilkan data peminjaman -->
                    <?php foreach( $rows as $row ) : ?>
                    <?php
                        $judul = $row['judul'];
                        $baris_baru = '\n';
                        $tanggal_sekarang = date('d');

                        $tanggal_kembalikan = explode('-', $row['tgl_kembali']);

                        if( $tanggal_sekarang >= $tanggal_kembalikan[2] ) {
                            echo "<script>
                                    alert('Peringatan{$baris_baru}penyewaan DVD $judul sudah melewati batas!{$baris_baru}Harap dikembalikan');
                                </script>";
                        }
                    ?>
                    <?php $judul = $row['judul']; ?>
                        <div class="box" id="<?= $judul; ?>">
                            <h2><?= $row['judul']; ?></h2>
                            <p>Tanggal Peminjaman: <?= $row['tgl_pinjaman']; ?></p>
                            <p>Tanggal Pengembalian: <?= $row['tgl_kembali']; ?></p>
                            <div class="aksi">
                                <form action="" method="post">
                                    <input type="hidden" name="kode_dvd" value="<?= $row['kode_dvd']; ?>">
                                    <input type="hidden" name="judul" value="<?= $row['judul']; ?>">
                                    <button type="submit" name="kembalikan" class="pinjam">Kembalikan</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <!-- Penutup  -->

                <?php endif; ?>

            </div>
            <!-- penutup konten -->

        </div>
        <!-- Penutup kontainer -->

    </section>

    <!-- My script -->
    <script src="main/js/my.js"></script>
</body>
</html>

<!-- Pengembalian data/DVD -->
<?php

if(isset($_POST['kembalikan'])) {

    $judul = $_POST['judul'];
    if( logikaPengembalian($_POST, $id_user) ) :
        echo "
            <script>
                alert('DVD $judul telah dikembalikan');
                window.location.href = 'index.php';
            </script>
        ";
    endif;
}

?>
<!-- Penutup logika pengembalian -->