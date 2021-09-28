<?php

require_once 'main/salam.php';

if( isset($_SESSION['id_user'])) :
    
    if( $_SESSION['kode_dvd'] ) :

        /* 
        |
        | mengambil sesion yang dibuat di index.php
        |
        */
        $id_user = $_SESSION['id_user'];
        $kode_dvd = $_SESSION['kode_dvd'];

        $user = single("master_anggota", "id_user", $id_user);
        $dvd = single("master_dvd", "kode_dvd", $kode_dvd);

        $nama = mysqli_real_escape_string(koneksi(), $user['nama']);
        $no = mysqli_real_escape_string(koneksi(), $user['no']);
        $judul = mysqli_real_escape_string(koneksi(), $dvd['judul']);
        $tgl_masuk = mysqli_real_escape_string(koneksi(), $dvd['tgl_masuk']);
        $genre = mysqli_real_escape_string(koneksi(), $dvd['genre']);

        $jumlah_dvd = mysqli_real_escape_string(koneksi(), $dvd['jumlah_dvd']);
        $jumlah_tersedia = mysqli_real_escape_string(koneksi(), $dvd['jumlah_tersedia']);

        // Jumlah tersdia akan berkurang
        $jumlah_tersedia -= 1;

        // Otomatis isikan tanggal
        $tgl_pinjaman = date('Y-m-d', time());

        // tenggat waktu peminjam 7hari
        $tgl_pengembalian = date('Y-m-d', time()+60*60*24*7);




        /* 
        |
        | Jika DVD tersedia
        |
        */
        if( $dvd['jumlah_tersedia'] > 0 ) :


            $result = tampilkanSemua("SELECT * FROM master_peminjaman WHERE id_user = '$id_user'");
            if( count($result) === 3 ) :
                unset($_SESSION['ide_user']);
                unset($_SESSION['kode_dvd']);
                echo "
                    <script>
                        alert('Maksimal peminjaman hanya 3 DVD dan Anda sudah memenuhinya!');
                        window.location.href = 'index.php';
                    </script>
                ";
                die;
            endif;


            /* 
            |
            | Menambahkan data pada table peminjaman
            |
            */
            $tambah_pinjaman = result("INSERT INTO master_peminjaman 
                    VALUES 
            (null, '$id_user', '$nama', '$no', '$kode_dvd', '$judul', '$tgl_pinjaman', '$tgl_pengembalian')");

            $edit_dvd = result("UPDATE master_dvd SET tgl_masuk = '$tgl_masuk',
                            judul = '$judul',
                            genre = '$genre',
                            jumlah_dvd = '$jumlah_dvd',
                            jumlah_tersedia = '$jumlah_tersedia'
                        WHERE kode_dvd = '$kode_dvd'");



            // Unset sesion agar user tidak iseng nuliskan link file ini
            unset($_SESSION['ide_user']);
            unset($_SESSION['kode_dvd']);

            if( $tambah_pinjaman ) :
                if( $edit_dvd) :
                    echo "<script>
                            alert('Penyewaan DVD berjudul $judul berhasil');
                            window.location.href = 'daftar-pinjaman.php';
                        </script>";
                else :
                    echo "<script>
                            alert('gagal1');
                        </script>";
                endif;
            else :
                    echo "<script>
                            alert('gagal2');
                        </script>";
            endif;

        else :

            // Unset sesion agar user tidak iseng nuliskan link file ini
            unset($_SESSION['ide_user']);
            unset($_SESSION['kode_dvd']);
            
            echo "<script>
            alert('DVD berjudul $judul sedang kosong');
            window.location.href = 'index.php';
            </script>";

        endif;


        



    /* 
    |
    | User iseng tidak bisa masuk karena tidak 
    | disetnya session di index.php setelah menekan tombol 
    |
    | bagian isset kode dvd
    */
    else :

        header('location: index.php');

    endif;
    
// sama
// bagian if isset id user
else :

    header('location: index.php');

endif;

