<?php
session_start();

// Menghapus semua data session
session_destroy();

// Mengarahkan langsung ke halaman utama (login/daftar)
// Ganti 'index.php' dengan nama file halaman depanmu (misal login.php)
header("location:index.php");
exit();
?>