<?php

require 'main/salam.php';


/* 
|
| Apabila user belum memngisi data profile, maka arahkan user ke hal. profile
|
*/
$result = result("SELECT * FROM master_anggota WHERE id_user = '$id_user'");
if( mysqli_num_rows($result) === 0 ) {
    echo "
        <script>
            alert('Anda belum mengisi data diri.');
            window.location.href = 'profile.php';
        </script>
    ";
    die;
}


/* 
|
| Ambil data menggunakan method single, func.php
|
*/

$result_anggota = single('master_anggota', 'id_user', $id_user);
$result_user = single('master_user', 'id', $id_user);

$alamat = hilangkan_karakter_tertentu($result_anggota['alamat'], ['+', '\\', '\'']);
$nama = hilangkan_karakter_tertentu($result_anggota['nama'], ['+', '\\', '\'']);
$tempat_lahir = hilangkan_karakter_tertentu($result_anggota['tempat_lahir'], ['+', '\\', '\'']);

$no = $result_anggota['no'];

$tanggal_lahir = explode('-', $result_anggota['tanggal_lahir']);

$tanggal_lahir = $tanggal_lahir[2]. ' ' .$tanggal_lahir[1]. ' ' .$tanggal_lahir[0];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main/css/style.css">
    <link rel="stylesheet" href="main/css/akun.css">
    <title>Profile dan Akun | Rental DVD</title>

    <?php if( is_file('akun.php')) : ?>
    <style>
        .akun {
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

    <section class="main-akun">
        <div class="container">
            <h1>Hello <?= $nama; ?></h1>
            <h2></h2>
            <div class="row">
                <div class="box-akun">
                    <p>Nama: <span><?= $nama; ?></span></p>
                    <p>Tempat Lahir: <span><?= $tempat_lahir; ?>, <?= $tanggal_lahir; ?></span></p>
                    <p>Jenis Kelamin: <span><?= $result_anggota['jenis_kelamin']; ?></span></p>
                    <p>No. Telepon: <span><?= $no; ?></span></p>
                    <p>Alamat: <span><?= $alamat; ?></span></p>
                    <a href="edit-profile.php">Lakukan perubahan</a>
                </div>
                <div class="box-akun">
                    <p>Username: <span><?= $result_user['username']; ?></span></p>
                    <p>Password: 
                        <span>
                            <?php 
                            for( $i = 0; $i < $_SESSION['pass']; $i++ ) {
                                echo '*';
                            }
                            ?>
                        </span>
                    </p>
                    <form action="" method="post">
                        <button type="submit" name="hapus">hapus akun</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- My JS -->
    <script src="main/js/my.js"></script>
    <script src="main/js/time.js"></script>
</body>
</html>
<?php

if( isset($_POST['hapus']) ) {
    $result = tampilkanSemua("SELECT * FROM master_peminjaman WHERE id_user = '$id_user'");
    $banyak_pinjaman = count($result);
    if( $banyak_pinjaman > 0 ) {
        echo "<script>
                alert('Anda masih meminjam sebanyak $banyak_pinjaman DVD. Anda tidak bisa menghapus akun!');
            </script>";
        die;
    }

    $_SESSION['hapus'] = true;
    echo "<script>
            let konfir = confirm('Jika anda menghapus akun, maka data data Anda akan hilang');
            if( konfir ) {
                window.location.href = 'hapus-akun.php';
            }
        </script>";
    die;
}

?>