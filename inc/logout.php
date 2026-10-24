<?php
// WAJIB panggil session_start() dulu agar PHP tahu sesi mana yang akan dihancurkan
session_start();

// Menghapus variabel sesi spesifik sesuai yang kita buat di file login.php
unset($_SESSION['email']);
unset($_SESSION['role']);
unset($_SESSION['id_users']);

// Mengosongkan seluruh data variabel array sesi sebagai langkah pengamanan ekstra
$_SESSION = [];

// Menghapus seluruh variabel sesi yang terdaftar
session_unset();

// Menghancurkan secara permanen data sesi yang tersimpan di server
session_destroy();

// Mengarahkan (redirect) navigasi peramban pengguna kembali ke halaman login
// (Menggunakan header PHP lebih cepat dan aman daripada script JS)
header("Location: ../inc/login.php");

// Menghentikan seluruh eksekusi skrip agar kode bawahnya tidak dijalankan
exit;
?>