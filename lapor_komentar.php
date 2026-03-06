<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("location:login.php");
    exit;
}

// Tambahan CSS Border sesuai permintaan (Hanya menambah, tidak mengubah struktur logikamu)
echo "<style>
    .card-img-top { 
        border: 4px solid #007bff !important; 
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
</style>";

// Tangkap variabel dari URL (Sesuai JS index.php)
$komentar_id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : null;
$photo_id = isset($_GET['photo_id']) ? mysqli_real_escape_string($conn, $_GET['photo_id']) : null;
$alasan = isset($_GET['alasan']) ? mysqli_real_escape_string($conn, $_GET['alasan']) : null;
$user_id = $_SESSION['user_id'];

if ($komentar_id && $alasan) {
    // Sesuaikan nama tabel dan kolom dengan database kamu (misal: laporan_komentar)
    $query = "INSERT INTO laporan_komentar (komentar_id, user_id, alasan) VALUES ('$komentar_id', '$user_id', '$alasan')";
    $sql = mysqli_query($conn, $query);

    if ($sql) {
        // Redirect balik ke index dengan parameter status reported
        header("location:index.php?status=reported");
        exit;
    } else {
        echo "Gagal melaporkan komentar: " . mysqli_error($conn);
    }
} else {
    header("location:index.php");
    exit; // Tambahkan exit agar eksekusi berhenti di sini
}
?>