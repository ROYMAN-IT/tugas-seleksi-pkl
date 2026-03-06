<?php
include 'koneksi.php';
session_start();

// Proteksi: Harus login dulu
if (!isset($_SESSION['user_id'])) {
    header("location:login.php");
    exit;
}

$komentar_id = $_GET['id'];
$photo_id = $_GET['photo_id']; // Kita ambil ini supaya bisa balik lagi ke modal yang bener
$user_id = $_SESSION['user_id'];

// Cek apakah user sudah pernah dislike komentar ini?
$cek_dislike = mysqli_query($conn, "SELECT * FROM dislike_komentar WHERE komentar_id = '$komentar_id' AND user_id = '$user_id'");

if (mysqli_num_rows($cek_dislike) > 0) {
    // Kalau sudah ada, hapus dislike-nya (un-dislike)
    mysqli_query($conn, "DELETE FROM dislike_komentar WHERE komentar_id = '$komentar_id' AND user_id = '$user_id'");
} else {
    // Kalau belum, masukkan data dislike baru
    mysqli_query($conn, "INSERT INTO dislike_komentar (komentar_id, user_id) VALUES ('$komentar_id', '$user_id')");
}

// Balik lagi ke index.php
header("location:index.php");
?>