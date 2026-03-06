<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("location:login.php");
    exit;
}

// Tangkap variabel dari URL (Sesuai JS index.php)
$photo_id = isset($_GET['photo_id']) ? mysqli_real_escape_string($conn, $_GET['photo_id']) : null;
$alasan = isset($_GET['alasan']) ? mysqli_real_escape_string($conn, $_GET['alasan']) : null;
$user_id = $_SESSION['user_id'];

if ($photo_id && $alasan) {
    $query = "INSERT INTO laporan_postingan (photo_id, user_id, alasan) VALUES ('$photo_id', '$user_id', '$alasan')";
    $sql = mysqli_query($conn, $query);

    if ($sql) {
        // Redirect balik ke index dengan parameter status
        header("location:index.php?status=reported");
        exit;
    } else {
        echo "Gagal mengirim laporan: " . mysqli_error($conn);
    }
} else {
    header("location:index.php");
}
?>