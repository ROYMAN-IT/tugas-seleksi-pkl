<?php
include 'koneksi.php';
session_start();
if (!isset($_SESSION['user_id'])) { header("location:login.php"); exit; }

$com_id = $_GET['id'];
$p_id = $_GET['photo_id'];
$u_id = $_SESSION['user_id'];

// Hapus dari like dulu (Eksklusif)
mysqli_query($conn, "DELETE FROM like_komentar WHERE komentar_id = '$com_id' AND user_id = '$u_id'");

// Cek apakah sudah dislike?
$cek = mysqli_query($conn, "SELECT * FROM dislike_komentar WHERE komentar_id = '$com_id' AND user_id = '$u_id'");
if (mysqli_num_rows($cek) > 0) {
    mysqli_query($conn, "DELETE FROM dislike_komentar WHERE komentar_id = '$com_id' AND user_id = '$u_id'");
} else {
    mysqli_query($conn, "INSERT INTO dislike_komentar (komentar_id, user_id) VALUES ('$com_id', '$u_id')");
}
header("location:index.php");