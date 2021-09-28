<?php

require_once 'main/salam.php';

session_destroy();
session_unset();

unset($_SESSION['guru_zuhdi']);
unset($_SESSION['guru_Sekumpul']);

setcookie('jenkel', '', time()-60*60*2);
setcookie('tgl', '', time()-60*60*2);
setcookie('alamat', '', time()-60*60*2);
setcookie('nama', '', time()-60*60*2);
setcookie('tl', '', time()-60*60*2);
setcookie('no', '', time()-60*60*2);

header('location: index.php');