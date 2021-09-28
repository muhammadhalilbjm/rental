<?php
require_once 'main/salam.php';

$result = result("SELECT * FROM master_anggota WHERE id_user = '$id_user'");

// Jika profile belum diisi, maka user akan di arahkan ke profile.php
if( mysqli_num_rows($result) === 0 ) {
    echo "
        <script>
            alert('Anda belum mengisi data diri.');
            window.location.href = 'profile.php';
        </script>
    ";
    die;
}

// Menampilkan data

$row = single("master_anggota", "id_user", $id_user);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main/css/style.css">
    <link rel="stylesheet" href="main/css/profile.css">
    <title>Edit Profile | Rental DVD</title>
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
        <div class="container-profile">
            <form action="" method="post">
                <input type="hidden" name="id" class="form-control" value=<?= $row['id']; ?> autocomplete="off" required autofocus placeholder="Nama">
                <input type="hidden" name="id_user" class="form-control" value=<?= $row['id_user']; ?> autocomplete="off" required autofocus placeholder="Nama">                
                <div class="form-group">
                    <label for="" class="label">No</label>
                    <input type="text" name="no" class="form-control" value=<?= $row['no']; ?> autocomplete="off" required placeholder="No">
                </div>
                <div class="form-group">
                    <label for="" class="label">Alamat</label>
                    <input type="text" name="alamat" class="form-control" value=<?= $row['alamat']; ?> autocomplete="off" required placeholder="Alamat">
                </div>
                <div class="form-group">
                    <label for="" class="label"></label>
                    <input type="submit" name="ok" id="simpan" class="form-control" value="Simpan">
                </div>
            </form>
        </div>
    </section>

    <script src="main/js/my.js"></script>
</body>
</html>

<!-- Logika edit profile -->
<?php

if(isset($_POST['ok'])) :

    if( edit_profile($_POST, $row) ) :

        echo "
            <script>
                alert('data berhasil diubah!');
                window.location.href = 'akun.php';
            </script>
        ";

    else :

        echo "
            <script>
                alert('data gagal diubah!');
            </script>
        ";

    endif;
endif;

?>