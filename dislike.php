<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("location:login.php");
    exit;
}

$photo_id = $_GET['photo_id'];
$user_id = $_SESSION['user_id'];

// 1. Cek apakah user sudah pernah LIKE foto ini?
$cek_like = mysqli_query($conn, "SELECT * FROM likes WHERE photo_id = '$photo_id' AND user_id = '$user_id'");

if (mysqli_num_rows($cek_like) > 0) {
    // Jika ada, hapus Like-nya dulu karena mau ganti jadi Dislike
    mysqli_query($conn, "DELETE FROM likes WHERE photo_id = '$photo_id' AND user_id = '$user_id'");
    mysqli_query($conn, "UPDATE photos SET jumlah_like = jumlah_like - 1 WHERE id = '$photo_id'");
}

// 2. Sekarang proses Dislike-nya
$cek_dislike = mysqli_query($conn, "SELECT * FROM dislikes WHERE photo_id = '$photo_id' AND user_id = '$user_id'");

if (mysqli_num_rows($cek_dislike) > 0) {
    // Jika sudah Dislike, maka batal Dislike (Toggle)
    mysqli_query($conn, "DELETE FROM dislikes WHERE photo_id = '$photo_id' AND user_id = '$user_id'");
    mysqli_query($conn, "UPDATE photos SET jumlah_dislike = jumlah_dislike - 1 WHERE id = '$photo_id'");
} else {
    // Jika belum Dislike, tambahkan Dislike
    mysqli_query($conn, "INSERT INTO dislikes (photo_id, user_id) VALUES ('$photo_id', '$user_id')");
    mysqli_query($conn, "UPDATE photos SET jumlah_dislike = jumlah_dislike + 1 WHERE id = '$photo_id'");
}

header("location:index.php");
?>