<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $user_id = $_SESSION['user_id'];
    
    $nama_file = $_FILES['foto']['name'];
    $tmp_name = $_FILES['foto']['tmp_name'];
    $ekstensi = pathinfo($nama_file, PATHINFO_EXTENSION);
    $nama_baru = time() . '.' . $ekstensi;

    if (move_uploaded_file($tmp_name, 'uploads/' . $nama_baru)) {
        $query = "INSERT INTO photos (user_id, nama_file, judul, deskripsi) VALUES ('$user_id', '$nama_baru', '$judul', '$deskripsi')";
        if (mysqli_query($conn, $query)) {
            // HANYA BAGIAN INI YANG DIUBAH: Menambahkan parameter status
            header("Location: index.php?status=upload_success");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Karya | FOTOVIBE.</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #3b82f6;
            --accent-color: #0ea5e9;
            --bg-color: #f8fafc;
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: var(--bg-color);
            color: #1f2937;
            background-image: radial-gradient(circle at top right, rgba(59, 130, 246, 0.15), transparent),
                              radial-gradient(circle at bottom left, rgba(14, 165, 233, 0.15), transparent);
            min-height: 100vh;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .upload-card {
            border: 3px solid var(--primary-color) !important;
            border-radius: 30px;
            background: white;
            padding: 30px;
            /* EFEK ULTRA GLOW: Disamakan dengan Login & Register */
            box-shadow: 
                0 0 20px rgba(59, 130, 246, 0.6), 
                0 0 40px rgba(59, 130, 246, 0.4), 
                0 15px 60px rgba(59, 130, 246, 0.3);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .upload-card:hover {
            transform: translateY(-8px);
            /* GLOW SAAT HOVER */
            box-shadow: 
                0 0 30px rgba(59, 130, 246, 0.8), 
                0 0 60px rgba(59, 130, 246, 0.5), 
                0 25px 80px rgba(59, 130, 246, 0.4);
        }

        .hero-title {
            font-weight: 800;
            background: linear-gradient(135deg, #3b82f6, #0ea5e9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -2px;
        }

        .welcome-divider {
            width: 50px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
            margin: 10px auto 0;
            border-radius: 10px;
        }

        .btn-back-vibe {
            display: inline-flex;
            align-items: center;
            color: #fff;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            border: none;
            border-radius: 50px;
            padding: 10px 24px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 13px;
        }

        .btn-back-vibe:hover {
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(59, 130, 246, 0.4);
            filter: brightness(1.1);
        }

        .form-label { font-weight: 700; color: #4b5563; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; }

        .form-control {
            border-radius: 15px;
            padding: 12px 18px;
            border: 2px solid var(--primary-color);
            background-color: #f8fafc;
            transition: all 0.3s;
        }

        .form-control:focus {
            background-color: #fff;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2);
        }

        #preview-container {
            width: 100%;
            height: 250px;
            border-radius: 20px;
            border: 2px dashed var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: #f8fafc;
            margin-bottom: 25px;
            cursor: pointer;
            transition: 0.3s;
        }

        #preview-img { width: 100%; height: 100%; object-fit: cover; display: none; }

        .btn-publish {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border: none;
            border-radius: 100px;
            padding: 16px;
            font-weight: 800;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
        }

        .btn-publish:hover { 
            transform: translateY(-3px); 
            box-shadow: 0 15px 30px rgba(59, 130, 246, 0.5); 
            color: white; 
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light sticky-top mb-5">
    <div class="container">
        <a class="navbar-brand fw-bold fs-4" href="index.php" style="color: var(--primary-color); letter-spacing: -1px;">✨ FOTOVIBE.</a>
        <div class="ms-auto">
            <a href="index.php" class="btn-back-vibe">
                <i class="bi bi-arrow-left me-2"></i> KEMBALI KE GALERI
            </a>
        </div>
    </div>
</nav>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-8">
            <div class="text-center mb-5">
                <h1 class="hero-title mb-2">Upload Karya</h1>
                <p class="text-muted fw-500 mb-0">Ekspresikan duniamu sekarang.</p>
                <div class="welcome-divider"></div>
            </div>

            <div class="card upload-card">
                <form action="" method="POST" enctype="multipart/form-data">
                    
                    <div id="preview-container" onclick="document.getElementById('imgInput').click()">
                        <div class="upload-placeholder text-center">
                            <i class="bi bi-cloud-arrow-up" style="font-size: 3rem; color: var(--primary-color); opacity: 0.8;"></i>
                            <div class="small fw-bold mt-2 text-muted">Klik untuk memilih foto</div>
                        </div>
                        <img id="preview-img" src="#" alt="Preview">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pilih File</label>
                        <input type="file" name="foto" class="form-control" accept="image/*" required id="imgInput">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Judul</label>
                        <input type="text" name="judul" class="form-control" placeholder="Beri judul foto ini..." required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Ceritakan momen ini..." required></textarea>
                    </div>

                    <button type="submit" class="btn btn-publish w-100">
                        <i class="bi bi-rocket-takeoff-fill me-2"></i> PUBLIKASIKAN SEKARANG
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const imgInput = document.getElementById('imgInput');
    const previewImg = document.getElementById('preview-img');
    const placeholderText = document.querySelector('.upload-placeholder');
    const previewContainer = document.getElementById('preview-container');

    imgInput.onchange = evt => {
        const [file] = imgInput.files;
        if (file) {
            previewImg.src = URL.createObjectURL(file);
            previewImg.style.display = 'block';
            placeholderText.style.display = 'none';
            previewContainer.style.borderStyle = 'solid';
        }
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>