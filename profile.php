<?php

require 'main/salam.php';

// Jika datanya ada, maka dilarang mengisi lagi, jadi user akan diarahkan ke edit
$result = result("SELECT * FROM master_anggota WHERE id_user = '$id_user'");
if( mysqli_num_rows($result) === 1 ) {
    header('location: akun.php');
    die;
}

$nama = null;
$tempat_lahir = null;
$tanggal_lahir = null;
$jenkel = null;
$no = null;
$alamat = null;

if( isset($_COOKIE['nama']) ) {
    $nama = $_COOKIE['nama'];
    $alamat = $_COOKIE['alamat'];
    $tempat_lahir = $_COOKIE['tl'];
    $tanggal_lahir = $_COOKIE['tgl'];
    $jenkel = $_COOKIE['jenkel'];
    if( isset($_COOKIE['no']) ) {
        $no = $_COOKIE['no'];
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main/css/style.css">
    <link rel="stylesheet" href="main/css/profile.css">
    <title>Profile | Rental DVD</title>
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
                <div class="form-group">
                    <label for="" class="label">Nama</label>
                    <input type="text" name="nama" class="form-control" required autocomplete="off"  autofocus value="<?= $nama;?>" placeholder="Nama">
                </div>
                <div class="form-group">
                    <label for="" class="label">Tempat Lahir</label>
                    <input type="text" name="tl" class="form-control" required autocomplete="off"  value="<?= $tempat_lahir;?>" placeholder="Tempat Lahir">
                </div>
                <div class="form-group">
                    <label for="" class="label">Tanggal Lahir</label>
                    <input type="date" name="tgl_lahir" id="" required value="<?= $tanggal_lahir; ?>">
                </div>
                <div class="form-group">
                    <label for="" class="label">Jenis Kelamin</label>
                    <input type="text" name="jenkel" class="form-control" required autocomplete="off"  value="<?= $jenkel;?>" placeholder="Jenis Kelamin" list="my-list">
                    <datalist id="my-list">
                        <option value="Laki - Laki"></option>
                        <option value="Perempuan"></option>
                    </datalist>
                </div>
                <div class="form-group">
                    <label for="" class="label">No</label>
                    <input type="text" name="no" class="form-control" required autocomplete="off" value="<?= $no; ?>" placeholder="No">
                </div>
                <div class="form-group">
                    <label for="" class="label">Alamat</label>
                    <input type="text" name="alamat" class="form-control" required autocomplete="off"  value="<?= $alamat;?>" placeholder="Alamat">
                </div>
                <div class="form-group">
                    <label for="" class="label"></label>
                    <input type="submit" name="ok" id="simpan" class="form-control" required value="Simpan">
                </div>
            </form>
        </div>
    </section>
    <script src="main/js/my.js"></script>
</body>
</html>

<?php

    if(isset($_POST['ok'])) {
        $nama = ucwords(profile($_POST['nama']));
        $tempat_lahir = ucwords(profile($_POST['tl']));
        $tanggal_lahir = $_POST['tgl_lahir'];

        $tanggallahir = str_split($tanggal_lahir, 4);
        $umur = date('Y') - $tanggallahir[0];

        if( $umur < 18 ) {
            setcookie('nama',  $nama);
            setcookie('tl',  $tempat_lahir);
            setcookie('tgl',  $tanggal_lahir);
            setcookie('jenkel',  $_POST['jenkel']);
            setcookie('alamat',  $_POST['alamat']);
            echo "<script>
                alert('Umur Anda belum 18 tahun');
                location.href = 'profile.php';
            </script>";
            die;
        }

        $jenkel = "Lainnya";
        if( $_POST['jenkel'] === "Laki - Laki" || $_POST['jenkel'] === "Perempuan" ) {
            $jenkel = $_POST['jenkel'];
        }

        $alamat = profile($_POST['alamat']);
        $filter_tambah = str_split($alamat);
        $tambah = ' ';
        $spasi = str_split($tambah);

        for ($filter = 0; $filter < count($filter_tambah); $filter++) { 
            if(in_array($filter_tambah[$filter], $spasi)) {
                $filter_tambah[$filter] = '+';
            } 
        }

        $alamat = join('', $filter_tambah);

        setcookie('nama',  $nama);
        setcookie('tl',  $tempat_lahir);
        setcookie('tgl',  $tanggal_lahir);
        setcookie('jenkel',  $jenkel);
        setcookie('alamat',  $alamat);


        $no = $_POST['no'];
        $_SESSION['no'] = filterNomor(profile($_POST['no']));
        setcookie('no',  $no);
        $baris_baru = '\n';
        if( isset($_SESSION['gas']) ) {
            echo "
                <script>
                    let kon = confirm(' Hallo $nama $baris_baru $baris_baru Berikut data yang akan disimpan $baris_baru Tempat, Tanggal Lahir: $tempat_lahir, $tanggal_lahir $baris_baru Jenis Kelamin: $jenkel $baris_baru No.telepon: $no $baris_baru Alamat: $alamat $baris_baru $baris_baru *Haraf diperhatikan, beberapa data tidak bisa diedit!');
                    if( kon ) {
                        window.location.href = 'proses-profile.php';
                    } else {
                        window.location.href = 'profile.php';                        
                    }
                </script>
            ";
            die;
        }
    }
?>