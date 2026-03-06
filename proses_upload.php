<?php
include 'koneksi.php';
session_start();

if (isset($_POST['upload'])) {
    $user_id = $_SESSION['user_id'];
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    
    // Urusan Foto
    $nama_file = $_FILES['foto']['name'];
    $tmp_file = $_FILES['foto']['tmp_name'];
    $ekstensi_boleh = array('png', 'jpg', 'jpeg');
    $x = explode('.', $nama_file);
    $ekstensi = strtolower(end($x));
    
    // Bikin nama file unik biar gak bentrok
    $nama_baru = date('dmYHis') . '_' . $nama_file;
    $path = "uploads/" . $nama_baru;

    if (in_array($ekstensi, $ekstensi_boleh) === true) {
        if (move_uploaded_file($tmp_file, $path)) {
            // Masukkan ke database
            $query = "INSERT INTO photos (user_id, nama_file, judul, deskripsi, jumlah_like) 
                      VALUES ('$user_id', '$nama_baru', '$judul', '$deskripsi', 0)";
            
            if (mysqli_query($conn, $query)) {
                echo "<script>alert('Karya berhasil dipublish!'); window.location='index.php';</script>";
            } else {
                echo "Gagal input database: " . mysqli_error($conn);
            }
        } else {
            echo "Gagal upload file ke folder!";
        }
    } else {
        echo "Ekstensi tidak diperbolehkan (Hanya JPG, PNG, JPEG)";
    }
}
?>