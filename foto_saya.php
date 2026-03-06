<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil data foto hanya milik user yang login (berdasarkan database Anda)
$query = "SELECT * FROM photos WHERE user_id = '$user_id' ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Koleksi Saya - Galeriku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        .container { margin-top: 50px; }
        .table-container { background: white; border-radius: 15px; padding: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .img-thumbnail-custom { width: 80px; height: 80px; object-fit: cover; border-radius: 10px; }
    </style>
</head>
<body>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary">Koleksi Foto Saya</h3>
        <a href="index.php" class="btn btn-outline-secondary btn-sm rounded-pill">Kembali ke Beranda</a>
    </div>

    <div class="table-container">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Foto</th>
                    <th>Judul</th>
                    <th>Deskripsi</th>
                    <th>Jumlah Like</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td>
                        <img src="uploads/<?php echo $row['nama_file']; ?>" class="img-thumbnail-custom">
                    </td>
                    <td><span class="fw-bold"><?php echo $row['judul']; ?></span></td>
                    <td><small class="text-muted"><?php echo substr($row['deskripsi'], 0, 50); ?>...</small></td>
                    <td><span class="badge bg-danger rounded-pill">❤️ <?php echo $row['jumlah_like']; ?></span></td>
                    <td>
                        <a href="hapus_foto.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus foto ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php if(mysqli_num_rows($result) == 0): ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Belum ada foto yang diunggah.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>