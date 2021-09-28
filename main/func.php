<?php

require_once 'config.php';



/* 
| ==================================
| Koneksi
| ==================================
*/

function koneksi()
{
    return mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
}





/* 
| ==================================
| Mysqli query
| ==================================
*/

function result($query)
{
    $result = mysqli_query(koneksi(), $query);
    return $result;
}





/* 
|
| Mengambil semua data
|
*/

function tampilkanSemua($query)
{
    $result = mysqli_query(koneksi(), $query);

    $rows = [];
    while( $row = mysqli_fetch_assoc( $result ) ) {
        array_push($rows, $row);
    }
    return $rows;
}





/* 
| ==================================
| Mengambil satu data
| ==================================
*/

function single($table, $data, $value)
{
    $result = mysqli_query(koneksi(), "SELECT * FROM $table WHERE $data = '$value'");
    return mysqli_fetch_assoc($result);
}





/* 
| ==================================
| Filter
| ==================================
*/

function username($data)
{
    $username = $data['username'];
    $username = mysqli_real_escape_string(koneksi(), $username);
    $username = strtolower($username);
    $username = strip_tags($username);
    $username = addslashes($username);
    return $username;
}

function password($data)
{
    $password = $data['password'];
    $password = htmlspecialchars($password);
    $password = md5($password);
    $password = password_hash($password, PASSWORD_DEFAULT);
    return $password;
}

function passwordConfirm($data)
{
    $passwordConfirm = $data['passwordConfirm'];
    $username = username($data);
    $passwordConfirm .= $username;
    $passwordConfirm = htmlspecialchars($passwordConfirm);
    $passwordConfirm = md5($passwordConfirm);
    $passwordConfirm = password_hash($passwordConfirm, PASSWORD_DEFAULT);
    return $passwordConfirm;
}





/* 
| ==================================
| Filter buatan sendiri
| ==================================
*/

function karakterTerlarang(): array
{
    $ini = "~`!#$%^&*()-+={}[]|\\:;\"'<>?,/ ";
    $ini = str_split($ini, 1);
    return $ini;
}





/* 
| ==================================
| Apabila user terlalu banyak request pembuatan akun
| ==================================
*/

function BanyakRequest() {
if( count($_COOKIE) > 6 ) {
    echo "
        <script>
            alert('terlalu banyak pemintaan pembuatan akun, sementara pemintaan di berhentikan');
        </script>";
        die;
    }
}





/* 
| ==================================
| Filter pada fle profile.php
| ==================================
*/

function profile($data) {
    $value = $data;
    $value = mysqli_real_escape_string(koneksi(), $value);
    $value = strip_tags($value);
    return $value;
}





/* 
| ====================================================================
| Pemeriksaan, apakah user sudah login
| Jika tidak, maka user akan dipaksa kehalaman login
| ====================================================================
*/

function my_session( $file = 'login.php')
{   
    $id = $_SESSION['guru_zuhdi'];
    $hasil = result("SELECT other_pass FROM master_user WHERE other_pass = '$id'");

    if( !isset($id) ) :
        session_unset();
        unset($_SESSION['guru_zuhdi']);
        session_destroy();
        header('location: ' .$file);
    elseif( !isset($_SESSION['guru_sekumpul']) ) :
        session_unset();
        unset($_SESSION['guru_sekumpul']);
        session_destroy();
        header('location: ' .$file);
    elseif( mysqli_num_rows($hasil) < 1 ) :
        session_unset();
        unset($_SESSION['guru_zuhdi']);
        unset($_SESSION['guru_sekumpul']);
        session_destroy();
        header('location: ' .$file);
    endif;
}





/* 
| ====================================================================
| Logika pengembalian, pada daftar-peminjaman.php
| ====================================================================
*/

function logikaPengembalian( $data, $salam ) : bool
{

    $kode_dvd = $data['kode_dvd'];

    // Data peminjam akan dihapus
    result("DELETE FROM master_peminjaman WHERE id_user = '$salam' AND kode_dvd = '$kode_dvd'");

    // Mengambil data
    $row = single("master_dvd", "kode_dvd", $kode_dvd);
    $tgl_msk = $row['tgl_masuk'];
    $judul = $row['judul'];
    $genre = $row['genre'];
    $jumlah_dvd = $row['jumlah_dvd'];
    $jumlah_tersedia = $row['jumlah_tersedia'];

    // Jika dikembalikan, maka jumlah tersdia akan bertambah
    $jumlah_tersedia += 1;

    result("UPDATE master_dvd SET tgl_masuk = '$tgl_msk', 
                                judul = '$judul', 
                                genre = '$genre', 
                                jumlah_dvd = '$jumlah_dvd', 
                                jumlah_tersedia = '$jumlah_tersedia' 
                            WHERE kode_dvd = '$kode_dvd'");

    return true;
}





/* 
| ====================================================================
| Logika pengembalian, pada daftar-peminjaman.php
| ====================================================================
*/

function edit_profile( $data, $row )
{
    $id = $data['id'];
    $id_user = $data['id_user'];
    
    $nama = mysqli_real_escape_string(koneksi(), $row['nama']);
    $tempat_lahir = mysqli_real_escape_string(koneksi(), $row['tempat_lahir']);
    $tanggal_lahir = mysqli_real_escape_string(koneksi(), $row['tanggal_lahir']);
    
    // Menentukan jenis kelamin
    $jenkel = $row['jenis_kelamin'];
    
    $no = filterNomor(profile($data['no']));

    $alamat = profile($data['alamat']);
    $spasi = ' ';
    $filter_alamat = str_split($alamat);
    $spasi = str_split($spasi);
    
    for ($filter = 0; $filter < count($filter_alamat); $filter++) { 
        if(in_array($filter_alamat[$filter], $spasi)) {
            $filter_alamat[$filter] = '+';
        } 
    }

    $alamat = join('', $filter_alamat);

    $dataNo = $row['no'];
    $dataAlamat = $row['alamat'];

    if( $dataNo === $no && $dataAlamat === $alamat ) {
        echo "
            <script>
                let kon = confirm('Data tidak ada yang berubah. Apakah Anda ingin kembali?');
                if( kon ) {
                    window.location.href = 'akun.php';
                }
            </script>
        ";
        die;
    }


    $result = result("UPDATE master_anggota SET
                                id_user = '$id_user',
                                nama = '$nama',
                                tempat_lahir = '$tempat_lahir',
                                tanggal_lahir = '$tanggal_lahir',
                                jenis_kelamin = '$jenkel',
                                no = '$no',
                                alamat = '$alamat'
                            WHERE id = '$id'");

    return $result;
}





/* 
| ==================================
| Logika peminjama, pada index.php
| ==================================
*/

function pinjam($data, $id)
{

    $result = result("SELECT id_user FROM master_anggota WHERE id_user = '$id'");

    if( mysqli_num_rows($result) < 1 ) :

        echo "<script>
                alert('Sebelum meminjam, Anda harus mengisikan data diri');
                window.location.href = 'profile.php';
            </script>";
        die;

    endif;

    $user = single("master_anggota",  "id_user", $id);

    $_SESSION['id_user'] = $user['id_user'];
    $_SESSION['kode_dvd'] = $data['kode_dvd'];

    $kode_dvd = $data['kode_dvd'];
    $judul = $data['judul'];
    $id_user = $user['id_user'];

    $resultpeminjaman = result("SELECT * FROM master_peminjaman WHERE id_user = '$id_user' AND kode_dvd = '$kode_dvd'");

    if( mysqli_num_rows($resultpeminjaman)  === 1 ) :
        echo "<script>
                alert('Anda sudah menyewa DVD yang sama');
                window.location.href = 'daftar-pinjaman.php#$judul';
            </script>";
        die;
    endif;

    echo "<script>
            window.location.href = 'pinjam.php';
        </script>";
}





/* 
| ==================================
| Filter nomor
| ==================================
*/

function filterNomor($no)
{
    $nomor = $no;
    $arrNo = str_split($nomor);
    $angka = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, '+'];

    for( $i = 0; $i < count($arrNo); $i++ ) {
        if( !in_array( $arrNo[$i], $angka )) {
            echo "
                <script>
                    alert('No tidak boleh mengandung huruf!');
                    window.location.href = 'profile.php';
                </script>
            ";
            die;
        } 
    }

    $arrNo = str_split($nomor, 2);
    $formatNoInd = ['08'];

    $arrNo62 = str_split($nomor, 3);
    $formatNoInd62 = ['+62'];

    switch( true ) {
        case $arrNo[0] === '08' : break;
        case $arrNo62[0] === "+62" : break;
        default:
            echo "
                <script>
                    alert('Masukan nomor yang benar!');                    
                    window.location.href = 'profile.php';
                </script>
            ";
        exit;
    }

    $_SESSION['gas'] = true;
    return $nomor;
}




function hilangkan_karakter_tertentu(string $data, array $filter = [])
{
    $hasil = str_split($data);
    for ($i = 0; $i < count($hasil); $i++) { 
        if(in_array($hasil[$i], $filter)) {
            $hasil[$i] = ' ';
        } 
    }

    $hasil = join('', $hasil);
    return $hasil;
}
