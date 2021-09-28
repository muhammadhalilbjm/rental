<?php
require 'akun.php';

if( !isset($_SESSION['hapus']) ) {
    header('location: akun.php');
    die;
}

result("DELETE FROM master_anggota WHERE id_user = '$id_user'");
result("DELETE FROM master_user WHERE id = '$id_user'");

echo "
    <script>
        alert('Akun berhasil dihapus');
    </script>
";

setcookie('username', '', time()-60*60*2);

header('location: akun.php');