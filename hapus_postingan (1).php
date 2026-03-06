<?php
include 'koneksi.php';
session_start();

// 1. Cek Login
if (!isset($_SESSION['user_id'])) {
    header("location:login.php");
    exit;
}

// 2. Ambil ID dari URL - DISESUAIKAN DENGAN photo_id DARI INDEX
$p_id = isset($_GET['photo_id']) ? mysqli_real_escape_string($conn, $_GET['photo_id']) : null;
$u_id = $_SESSION['user_id'];

if ($p_id) {
    // 3. Cari data foto (untuk hapus file fisik dan pastikan pemiliknya benar)
    $query_cek = "SELECT * FROM photos WHERE id = '$p_id' AND user_id = '$u_id'";
    $cek_pemilik = mysqli_query($conn, $query_cek);
    $data = mysqli_fetch_assoc($cek_pemilik);

    if ($data) {
        // 4. Hapus File Gambar di folder uploads
        $nama_file = $data['nama_file'];
        $target_file = "uploads/" . $nama_file;
        
        if (file_exists($target_file)) {
            unlink($target_file);
        }

        // 5. Hapus Data di Database
        // Kita hapus manual data terkait dulu (Foreign Key)
        mysqli_query($conn, "DELETE FROM likes WHERE photo_id = '$p_id'");
        mysqli_query($conn, "DELETE FROM dislikes WHERE photo_id = '$p_id'");
        mysqli_query($conn, "DELETE FROM comments WHERE photo_id = '$p_id'");
        mysqli_query($conn, "DELETE FROM laporan_postingan WHERE photo_id = '$p_id'");
        
        // Terakhir hapus fotonya
        $delete = mysqli_query($conn, "DELETE FROM photos WHERE id = '$p_id'");

        if($delete) {
            // REDIRECT DENGAN STATUS UNTUK MEMICU SWEETALERT DI INDEX
            header("location:index.php?status=deleted");
            exit;
        } else {
            echo "Gagal menghapus data di database: " . mysqli_error($conn);
        }
    } else {
        echo "Postingan tidak ditemukan atau Anda bukan pemiliknya.";
    }
} else {
    header("location:index.php");
    exit;
}
?>