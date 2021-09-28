let a = document.getElementsByTagName('a')[2];
a.addEventListener('click', () => {
    let logout = confirm('Apakah Anda ingin keluar dari aplikasi?');
    if( logout ) {
        window.location.href = 'gerua.php';
    }
});