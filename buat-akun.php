<?php

/* 
|
| Session jika sudah login, maka harus
| Logout jika mau diperbolehkan masuk hal. ini
|
*/
session_start();
if(isset($_SESSION['guru_zuhdi'])) {
    header('location: index.php');
}

$username = '';
if( isset( $_COOKIE['usernm']) ) {
    $username = $_COOKIE['usernm'];
}

?>

<!-- MY HTML -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main/css/login.css">
    <title>Buat Akun | Rental DVD</title>
</head>
<body>
    <div class="container">
        <h1 id="akun">Buat Akun</h1>
        <form action="" method="post">
            <input type="text" name="username" id="username" placeholder="Username" value="<?= $username; ?>" autocomplete="off" maxlength="22" autofocus required/>
            <input type="password" name="password" placeholder="Password" required/>
            <input type="password" name="passwordConfirm" placeholder="Konfirmasi Password" required/>
            <input type="submit" name="tambah" id="simpan" class="form-control" value="Simpan">
            <a href="login.php" id="kembali">Kembali</a>
        </form>
    </div>

    <!-- My Javascript  -->
    <script src="main/js/my.js"></script>
</body>
</html>


<!-- PHP -->
<?php

/* 
|
| Pemanggilan file func.php
|
*/
require 'main/func.php';
$koneksi = koneksi();


// Jika tombol tambah di tekan
if(isset($_POST['tambah'])) :


    /* 
    |
    | Diambil dari func
    |
    */
    $username = username($_POST);
    $password = password($_POST); 
    $konfirmasi = passwordConfirm($_POST);


    /* 
    |
    | Filter
    |
    */
    BanyakRequest();
    $karakterTerlarang = karakterTerlarang();
    $cek = str_split($_POST['username'], 1);
    for ($filter = 0; $filter < count($cek); $filter++) { 
        if(in_array($cek[$filter], $karakterTerlarang)) {
            echo "
                <script>
                    alert('Dilarang memasukan karakter terlarang');
                </script>               
            ";
            setcookie( $cek[$filter], "gagal", time()+60);
            die;
        } 
    }

    /* 
    |
    | Memeriksa apakah username sudah digunakan/belum
    |
    */
    $result = mysqli_query($koneksi, "SELECT username FROM master_user WHERE username = '$username'");
    if( mysqli_num_rows($result) === 1 ) :
    echo "
        <script>
            alert('Username sudah digunakan!');
        </script>
    ";
    die;
    endif;





    /* 
    |
    | Mmeriksa apakah password terlalu pendek?
    |
    */
    if( strlen($_POST['username']) < 4 ) :
        setcookie('usernm', $_POST['username']);
        echo "
            <script>
                alert('Username terlalu pendek!');
            </script>
        ";
        die;
    endif;





    /* 
    |
    | Mmeriksa apakah password mudah ditebak?
    |
    */
    $us = $_POST['username'] ;
    $password_general = [
        1, 2, 123, 1234, 12345, 1234567, 12345678, 123456789,
        "{$us}1", "{$us}2", "{$us}123", "{$us}1234", "{$us}12345", "{$us}1234567", "{$us}12345678", "{$us}123456789",
        'tes', 'coba', 'password', 'pass', 'pw', $us,
        'passwordsaya', 'passwordaku', 'password123', 'mypassword'
    ];

    if( in_array( $_POST['password'], $password_general) ) :
        setcookie('usernm', $_POST['username']);
        echo "
            <script>
                alert('Password terlalu mudah ditembak!');
                window.location.href = 'buat-akun.php';
            </script>
            ";






    /* 
    |
    | Konfirmasi Password
    |
    */
    elseif( $_POST['password'] !== $_POST['passwordConfirm'] ) :
        setcookie('usernm', $_POST['username']);
        echo "
        <script>
            alert('Konfirmasi password tidak sama!');
            window.location.href = 'buat-akun.php';
        </script>
        ";






    /* 
    |
    | Mmeriksa apakah password terlalu pendek?
    |
    */
    elseif( strlen($_POST['password']) < 6 ) :
        setcookie('usernm', $_POST['username']);
        echo "
            <script>
                alert('Password terlalu pendek!');
                window.location.href = 'buat-akun.php';
            </script>
        ";





    /* 
    |
    | Jika semua sudah sesuai, buat lah akun baru
    |
    */
    else :
    $result = mysqli_query($koneksi, "INSERT INTO master_user VALUES(null, '{$username}', '{$password}', '{$konfirmasi}')");
        if($result) :
            $_SESSION['username'] = $_POST['username'];
            setcookie('usernm', '', time()-60*60*2);
            echo "
                <script>
                    alert('Data telah ditambahkan! Selamat datang di Rental DVD');
                    window.location.href = 'login.php';
                </script>
            ";
        endif;
    endif;
endif;
?>