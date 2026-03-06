<?php
include 'koneksi.php';
session_start();

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$comment_id = $_GET['id'];
$photo_id = $_GET['photo_id'];
$user_id = $_SESSION['user_id'];

// Keamanan: Pastikan yang menghapus adalah pemilik komentar tersebut
$query = "DELETE FROM comments WHERE id = '$comment_id' AND user_id = '$user_id'";

if (mysqli_query($conn, $query)) {
    // Kembali ke index dan buka kembali modal fotonya
    header("Location: index.php?modal_id=" . $photo_id);
} else {
    echo "Gagal menghapus komentar.";
}
exit();
?>