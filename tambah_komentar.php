<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $photo_id = $_POST['photo_id'];
    $user_id = $_SESSION['user_id'];
    $isi_komentar = mysqli_real_escape_string($conn, $_POST['isi_komentar']);

    if (!empty($isi_komentar)) {
        $query = "INSERT INTO comments (photo_id, user_id, isi_komentar) VALUES ('$photo_id', '$user_id', '$isi_komentar')";
        mysqli_query($conn, $query);
    }

    // Perhatikan baris ini: kita kirim parameter 'modal_id' di URL
    header("Location: index.php?modal_id=" . $photo_id);
    exit();
}
?>