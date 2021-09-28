<?php

require 'profile.php';

$no = $_SESSION['no'];
$result = result("INSERT INTO master_anggota VALUES (null, '$id_user', '$nama', '$tempat_lahir', '$tanggal_lahir', '$jenkel', '$no', '$alamat')");

if( $result ) {
    setcookie('nama', '', time()-60*60*2);
    setcookie('tl', '', time()-60*60*2);
    setcookie('tgl', '', time()-60*60*2);
    setcookie('jenkel', '', time()-60*60*2);
    setcookie('alamat', '', time()-60*60*2);
    setcookie('no', '', time()-60*60*2);

    unset($_SESSION['no']);
    echo "
        <script>
            alert('data berhasil ditambahkan!');
            window.location.href = 'akun.php';
        </script>
    ";
} else {
    echo mysqli_error(koneksi());
}