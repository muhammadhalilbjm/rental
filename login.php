<?php
/* 
|
| Jika sudah login, maka user tidak bisa kembali
| Kecuali logout
|
*/
session_start();
if(isset($_SESSION['id'])) {
    header('location: index.php');
}

$username = '';
if(isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    unset($_COOKIE['username']);
}

if(isset($_COOKIE['username'])) {
    $username = $_COOKIE['username'];
}
?>


<!-- HTML -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main/css/login.css">
    <title>Login | Rental DVD</title>
</head>
<body>
    <div class="container">
        <h1 id="akun">Login</h1>
        <form action="" method="post">
            <input type="text" name="username" autocomplete="off" autofocus placeholder="Username" value="<?= $username; ?>" required/>
            <input type="password" name="password" placeholder="Password" required/>
            <input type="submit" name="login" id="simpan" class="form-control" value="Login">
        </form>
        <p>Belum mempunyai sebuah akun? buat <a href="buat-akun.php">disini</a></p>
        <p>
            <a href="">instagram</a> / <a href="">facebook</a> / <a href="">github</a>
        </p>
    </div>
    <script src="main/js/my.js"></script>
</body>
</html>


<!-- PHP -->
<?php

/* 
|
| Memanggil file func.php untuk function
|
*/
require 'main/func.php';


// Jika tombol login ditekan
if(isset($_POST['login']))
{
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $result = result("SELECT * FROM master_user WHERE username = '$username'");
    $_SESSION['pass'] = strlen($_POST['password']);


    /* 
    |
    | Memeriksa apakah akun sudah ada?
    |
    */
    if( mysqli_num_rows($result) === 1 ) :

        // !Jika aku ada maka ambil data
        $data = mysqli_fetch_assoc($result);

        // !Membandingkan password database dengan inputan password
        if(password_verify($password, $data['password'])) :

            // !Pengamanan ganda
            $konfir = $_POST['password'] .= $username;
            if(password_verify(md5($konfir), $data['other_pass'])) :

                // !Jika sama, maka buat session 
                $_SESSION['guru_zuhdi'] = $data['other_pass'];
                $_SESSION['guru_sekumpul'] = $data['id'];
                unset($_SESSION['username']);
                setcookie('username', $username, time()+60*60*24*30*12);
                header('location: index.php');

            else :
                unset($_SESSION['pass']);
                echo "<script>
                    alert('Username/password salah!');
                </script>";
            endif;

        else :
            unset($_SESSION['pass']);
            echo "<script>
                alert('Username/password salah!');
            </script>";
        endif;
    
    /* 
    |
    | Jika akun tidak ditemukan/ada, maka tampilkan peringatan
    |
    */
    else :
        echo "<script>
                alert('Akun tidak terdaftar!');
            </script>";
    endif;

}
?>