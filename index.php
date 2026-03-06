<?php
  
include 'koneksi.php';
session_start();

// Fungsi Warna PP Random tapi Konsisten - DIUBAH KE VARIASI BIRU
function getAvatarBg($name) {
    $colors = ['#3b82f6', '#60a5fa', '#93c5fd', '#2563eb', '#1d4ed8', '#0ea5e9', '#38bdf8', '#0077b6'];
    $index = ord(strtolower($name)) % count($colors);
    return $colors[$index];
}

$query = "SELECT photos.*, users.nama FROM photos 
          JOIN users ON photos.user_id = users.id 
          ORDER BY photos.id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fotovibe | Galeri Kreatif</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root { 
            --primary-color: #3b82f6; 
            --primary-dark: #2563eb;
            --bg-color: #f8fafc; 
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: var(--bg-color); 
        }

        /* --- STYLE BARU TOMBOL LOGIN & DAFTAR (BORDER HITAM) --- */
        .btn-nav-login {
            background-color: #fff;
            color: #000 !important;
            border: 2px solid #000;
            border-radius: 50px;
            padding: 8px 25px;
            font-weight: 700;
            text-decoration: none;
            transition: 0.3s;
            margin-right: 10px;
            display: inline-block;
        }
        .btn-nav-login:hover {
            background-color: #f1f5f9;
            transform: translateY(-2px);
            box-shadow: 4px 4px 0px #000;
        }

        .btn-nav-daftar {
            background-color: #3b82f6;
            color: white !important;
            border: 2px solid #000;
            border-radius: 50px;
            padding: 8px 25px;
            font-weight: 700;
            text-decoration: none;
            transition: 0.3s;
            display: inline-block;
        }
        .btn-nav-daftar:hover {
            background-color: #2563eb;
            transform: translateY(-2px);
            box-shadow: 4px 4px 0px #000;
        }
        /* ----------------------------------------------------- */

        .swal2-popup {
            border: 3px solid var(--primary-color) !important;
            border-radius: 25px !important;
            box-shadow: 0 0 30px rgba(59, 130, 246, 0.3) !important;
        }

        .navbar { 
            background: rgba(255, 255, 255, 0.8) !important; 
            backdrop-filter: blur(15px); 
            border-bottom: none !important;
            padding: 15px 0; 
        }
        .navbar-brand { 
            font-weight: 900; 
            color: var(--primary-color) !important; 
            letter-spacing: -1.5px; 
            font-size: 2.2rem !important;
            line-height: 1;
        }

        .hero-title {
            font-weight: 800;
            font-size: 3.5rem;
            letter-spacing: -2.5px;
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        .hero-subtitle {
            color: #64748b;
            font-weight: 500;
            margin-bottom: 30px;
        }

        .card { 
            border: 2px solid var(--primary-color) !important; 
            border-radius: 25px; 
            transition: 0.3s; 
            background: white; 
            overflow: hidden; 
        }
        .card:hover { 
            transform: translateY(-10px); 
            box-shadow: 0 15px 40px rgba(59, 130, 246, 0.2); 
        }

        .card-img-top { 
            height: 260px; 
            object-fit: cover; 
            cursor: pointer; 
            border: 6px solid #fff;
            outline: 2px solid var(--primary-color);
            border-radius: 22px;
            margin: 8px;
            width: calc(100% - 16px);
        }

        .pp-circle { color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.1); flex-shrink: 0; }
        
        .avatar-mini { width: 22px; height: 22px; font-size: 10px; margin-left: -8px; } 
        .avatar-comment { width: 50px; height: 50px; font-size: 20px; margin-right: 15px; }
        .avatar-tiny { width: 20px; height: 20px; font-size: 9px; margin-right: -5px; border: 1px solid #fff; }

        .modal-fullscreen-custom .modal-content { background: #000; }
        .detail-container { display: flex; height: 100vh; background: #000; }
        .image-display { flex: 1.5; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .image-display img { max-width: 100%; max-height: 90vh; object-fit: contain; }
        .info-panel { flex: 1; background: white; display: flex; flex-direction: column; max-width: 500px; }
        .scroll-area { flex: 1; overflow-y: auto; padding: 30px; }
        .action-area { padding: 20px 30px; border-top: 1px solid #eee; background: #fff; }

        .comment-bubble { background: #f8fafc; border-radius: 15px; padding: 15px; margin-bottom: 12px; display: flex; align-items: flex-start; position: relative; }
        .comment-actions { position: absolute; right: 12px; top: 12px; display: flex; gap: 10px; align-items: center; }
        .btn-action-com { border: none; background: none; padding: 0; opacity: 0.5; transition: 0.3s; font-size: 14px; color: #64748b; text-decoration: none; cursor: pointer; }
        .btn-action-com:hover { opacity: 1; color: var(--primary-color); }
        .dislike-btn { color: #64748b; text-decoration: none; font-size: 11px; font-weight: 700; transition: 0.2s; }
        .dislike-btn:hover { color: #ef4444; }
        .reaction-group { display: flex; gap: 10px; align-items: center; margin-top: 5px; }

        .btn-primary-custom { 
            background-color: var(--primary-color); 
            color: white; 
            border: 2px solid #000; 
            border-radius: 50px; 
            font-weight: 700; 
            transition: 0.2s; 
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary-custom:hover { 
            background-color: var(--primary-dark); 
            color: white; 
            transform: translate(-2px, -2px); 
            box-shadow: 4px 4px 0px #000; 
        }

        @media (max-width: 992px) { 
            .detail-container { flex-direction: column; height: auto; } 
            .info-panel { max-width: 100%; } 
            .hero-title { font-size: 2.5rem; }
            .image-display img { max-height: 60vh; width: 100%; }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
        <div class="d-flex flex-column align-items-start">
            <a class="navbar-brand mb-0" href="index.php">✨ FOTOVIBE<span style="color: #000;">.</span></a>
            
            <?php if(isset($_SESSION['user_id'])): ?>
                <div class="d-flex align-items-center mt-1 bg-light px-3 py-2 rounded-pill border border-primary" style="opacity: 0.9;">
                    <div class="pp-circle me-2" style="width: 35px; height: 35px; font-size: 16px; background-color: <?php echo getAvatarBg($_SESSION['nama']); ?>;">
                        <?php echo strtoupper(substr($_SESSION['nama'], 0, 1)); ?>
                    </div>
                    <span class="fw-bold" style="font-size: 16px; color: var(--primary-color);">
                        <?php echo $_SESSION['nama']; ?>
                    </span>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="ms-auto d-flex align-items-center">
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="upload.php" class="btn btn-primary-custom px-3 fw-bold me-2">📤 Upload</a>
                <button onclick="logoutKonfirmasi()" class="btn btn-outline-danger rounded-pill px-3 fw-bold">🚪 Logout</button>
            <?php else: ?>
                <a href="login.php" class="btn-nav-login">Login</a>
                <a href="register.php" class="btn-nav-daftar">Daftar</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container">
    <div class="py-5 text-center">
        <?php if(!isset($_SESSION['user_id'])): ?>
        <div class="mb-3" style="font-size: 0.9rem; color: #ef4444; font-weight: 700; text-transform: uppercase;">
            ⚠️ Jika belum daftar silahkan masukkan email anda terlebih dahulu dan buat password terlebih dahulu
        </div>
        <?php endif; ?>

        <h1 class="hero-title">Inspirasi Dalam Lensa</h1>
        <p class="hero-subtitle">Temukan sudut pandang unik dari seluruh penjuru dunia.</p>
        <div style="width: 60px; height: 4px; background: var(--primary-color); margin: 0 auto; border-radius: 10px;"></div>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 pb-5">
        <?php while($row = mysqli_fetch_assoc($result)): ?>
            <?php $p_id = $row['id']; ?>
            <div class="col">
                <div class="card h-100">
                    <img src="uploads/<?php echo $row['nama_file']; ?>" class="card-img-top" data-bs-toggle="modal" data-bs-target="#modalDetail<?php echo $p_id; ?>">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-light text-dark rounded-pill px-3 py-2 fw-bold" style="font-size: 14px;">@<?php echo strtolower($row['nama']); ?></span>
                            <div class="d-flex align-items-center gap-3">
                                <div class="d-flex align-items-center">
                                    <span class="text-danger small fw-bold">❤️ <?php echo $row['jumlah_like']; ?></span>
                                    <div class="d-flex ms-2"> <?php
                                        $res_likes = mysqli_query($conn, "SELECT users.nama FROM likes JOIN users ON likes.user_id = users.id WHERE photo_id = '$p_id' LIMIT 3");
                                        while($l_user = mysqli_fetch_assoc($res_likes)): ?>
                                            <div class="pp-circle avatar-mini" style="background-color: <?php echo getAvatarBg($l_user['nama']); ?>;"><?php echo strtoupper(substr($l_user['nama'], 0, 1)); ?></div>
                                        <?php endwhile; ?>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="text-secondary small fw-bold">👎 <?php echo $row['jumlah_dislike'] ?? 0; ?></span>
                                    <div class="d-flex ms-2"> <?php
                                        $res_dislikes = mysqli_query($conn, "SELECT users.nama FROM dislikes JOIN users ON dislikes.user_id = users.id WHERE photo_id = '$p_id' LIMIT 3");
                                        while($d_user = mysqli_fetch_assoc($res_dislikes)): ?>
                                            <div class="pp-circle avatar-mini" style="background-color: <?php echo getAvatarBg($d_user['nama']); ?>;"><?php echo strtoupper(substr($d_user['nama'], 0, 1)); ?></div>
                                        <?php endwhile; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h5 class="fw-bold text-dark text-truncate mb-1"><?php echo $row['judul']; ?></h5>
                        <p class="text-secondary small text-truncate mb-3"><?php echo $row['deskripsi']; ?></p>
                        <button class="btn btn-dark w-100 rounded-pill fw-bold" data-bs-toggle="modal" data-bs-target="#modalDetail<?php echo $p_id; ?>">Lihat Detail</button>
                    </div>
                </div>
            </div>

            <div class="modal fade modal-fullscreen-custom" id="modalDetail<?php echo $p_id; ?>" tabindex="-1">
                <div class="modal-dialog modal-fullscreen">
                    <div class="modal-content border-0">
                        <div class="detail-container">
                            <div class="image-display"><img src="uploads/<?php echo $row['nama_file']; ?>"></div>
                            <div class="info-panel">
                                <div class="scroll-area">
                                    <button type="button" class="btn btn-light rounded-circle mb-4" data-bs-dismiss="modal"><i class="bi bi-arrow-left"></i></button>
                                    <h1 class="fw-800 mb-2"><?php echo $row['judul']; ?></h1>
                                    <p class="text-secondary mb-3"><?php echo $row['deskripsi']; ?></p>
                                    <div class="d-flex gap-2 mb-4">
                                        <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] != $row['user_id']): ?>
                                            <button onclick="laporPostingan(<?php echo $p_id; ?>)" class="btn btn-sm btn-outline-danger rounded-pill" style="font-size: 11px;"><i class="bi bi-exclamation-triangle-fill me-1"></i> Laporkan Postingan</button>
                                        <?php endif; ?>
                                        <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row['user_id']): ?>
                                            <button onclick="hapusPostinganKonfirmasi(<?php echo $p_id; ?>)" class="btn btn-sm btn-outline-danger rounded-pill" style="font-size: 11px;"><i class="bi bi-trash3-fill me-1"></i> Hapus Postingan</button>
                                        <?php endif; ?>
                                    </div>
                                    <hr class="my-4">
                                    <h6 class="fw-800 mb-3">💬 Diskusi</h6>
                                    <div class="comment-section">
                                        <?php
                                        $comments = mysqli_query($conn, "SELECT comments.*, users.nama FROM comments JOIN users ON comments.user_id = users.id WHERE photo_id = '$p_id' ORDER BY id DESC");
                                        while($com = mysqli_fetch_assoc($comments)):
                                            $com_id = $com['id']; 
                                            $com_user_id = $com['user_id'];
                                            $com_color = getAvatarBg($com['nama']);
                                            
                                            $u_now = $_SESSION['user_id'] ?? 0;
                                            $is_liked = false;
                                            $is_disliked = false;
                                            if($u_now > 0) {
                                                $check_l = mysqli_query($conn, "SELECT id FROM like_komentar WHERE komentar_id = '$com_id' AND user_id = '$u_now'");
                                                $is_liked = mysqli_num_rows($check_l) > 0;
                                                $check_d = mysqli_query($conn, "SELECT id FROM dislike_komentar WHERE komentar_id = '$com_id' AND user_id = '$u_now'");
                                                $is_disliked = mysqli_num_rows($check_d) > 0;
                                            }
                                        ?>
                                            <div class="comment-bubble">
                                                <div class="pp-circle avatar-comment" style="background-color: <?php echo $com_color; ?>;"><?php echo strtoupper(substr($com['nama'], 0, 1)); ?></div>
                                                <div class="w-100">
                                                    <span class="fw-bold text-dark" style="font-size: 14px;"><?php echo $com['nama']; ?></span>
                                                    <p class="mb-1 small text-secondary"><?php echo $com['isi_komentar']; ?></p>
                                                    <div class="reaction-group">
                                                        <a href="proses_like_komentar.php?id=<?php echo $com_id; ?>&photo_id=<?php echo $p_id; ?>" 
                                                           class="dislike-btn d-flex align-items-center gap-1" 
                                                           style="color: <?php echo $is_liked ? 'var(--primary-color)' : '#64748b'; ?>;">
                                                             <?php if($is_liked && isset($_SESSION['nama'])): ?>
                                                                <div class="pp-circle avatar-tiny" style="background-color: <?php echo getAvatarBg($_SESSION['nama']); ?>;"><?php echo strtoupper(substr($_SESSION['nama'], 0, 1)); ?></div>
                                                             <?php endif; ?>
                                                             👍 Suka
                                                        </a>
                                                        <a href="proses_dislike_komentar.php?id=<?php echo $com_id; ?>&photo_id=<?php echo $p_id; ?>" 
                                                           class="dislike-btn d-flex align-items-center gap-1"
                                                           style="color: <?php echo $is_disliked ? '#ef4444' : '#64748b'; ?>;">
                                                             <?php if($is_disliked && isset($_SESSION['nama'])): ?>
                                                                <div class="pp-circle avatar-tiny" style="background-color: <?php echo getAvatarBg($_SESSION['nama']); ?>;"><?php echo strtoupper(substr($_SESSION['nama'], 0, 1)); ?></div>
                                                             <?php endif; ?>
                                                             👎 Gak Suka
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="comment-actions">
                                                    <?php if(isset($_SESSION['user_id'])): ?>
                                                        <?php if($_SESSION['user_id'] == $com_user_id): ?>
                                                            <a onclick="hapusKomentarKonfirmasi(<?php echo $com_id; ?>, <?php echo $p_id; ?>)" class="btn-action-com text-danger">
                                                                <i class="bi bi-trash3-fill"></i>
                                                            </a>
                                                        <?php else: ?>
                                                            <a onclick="laporKomentar(<?php echo $com_id; ?>, <?php echo $p_id; ?>)" class="btn-action-com text-warning">
                                                                <i class="bi bi-exclamation-octagon-fill"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endwhile; ?>
                                    </div>
                                </div>
                                <div class="action-area">
                                    <?php if(isset($_SESSION['user_id'])): ?>
                                        <form action="tambah_komentar.php" method="POST" class="mb-3">
                                            <input type="hidden" name="photo_id" value="<?php echo $p_id; ?>">
                                            <div class="input-group">
                                                <input type="text" name="isi_komentar" class="form-control border-0 bg-light rounded-start-pill px-3" placeholder="Tulis komentar..." required>
                                                <button class="btn btn-primary rounded-end-pill" style="background-color: var(--primary-color); border:none;" type="submit"><i class="bi bi-send-fill"></i></button>
                                            </div>
                                        </form>
                                    <?php endif; ?>
                                    <div class="d-flex flex-column gap-3">
                                        <div class="d-flex gap-4">
                                            <div class="d-flex align-items-center">
                                                <span class="fw-bold text-danger">❤️ <?php echo $row['jumlah_like']; ?></span>
                                                <div class="d-flex ms-2">
                                                    <?php
                                                    $res_l_modal = mysqli_query($conn, "SELECT users.nama FROM likes JOIN users ON likes.user_id = users.id WHERE photo_id = '$p_id' LIMIT 3");
                                                    while($l_m = mysqli_fetch_assoc($res_l_modal)): ?>
                                                        <div class="pp-circle avatar-mini" style="background-color: <?php echo getAvatarBg($l_m['nama']); ?>;"><?php echo strtoupper(substr($l_m['nama'], 0, 1)); ?></div>
                                                    <?php endwhile; ?>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <span class="fw-bold text-secondary">👎 <?php echo $row['jumlah_dislike'] ?? 0; ?></span>
                                                <div class="d-flex ms-2">
                                                    <?php
                                                    $res_d_modal = mysqli_query($conn, "SELECT users.nama FROM dislikes JOIN users ON dislikes.user_id = users.id WHERE photo_id = '$p_id' LIMIT 3");
                                                    while($d_m = mysqli_fetch_assoc($res_d_modal)): ?>
                                                        <div class="pp-circle avatar-mini" style="background-color: <?php echo getAvatarBg($d_m['nama']); ?>;"><?php echo strtoupper(substr($d_m['nama'], 0, 1)); ?></div>
                                                    <?php endwhile; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-2 w-100">
                                            <a href="like.php?photo_id=<?php echo $p_id; ?>" class="btn btn-outline-danger rounded-pill fw-bold px-4 flex-grow-1">SUKAI</a>
                                            <a href="dislike.php?photo_id=<?php echo $p_id; ?>" class="btn btn-outline-secondary rounded-pill fw-bold px-4 flex-grow-1">DISLIKE</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function logoutKonfirmasi() {
        Swal.fire({
            title: 'Yakin mau keluar?',
            text: "Momen seru menantimu di sini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3b82f6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Logout!',
            cancelButtonText: 'Batal'
        }).then((result) => { if (result.isConfirmed) { window.location.href = 'logout.php'; } })
    }

    function hapusPostinganKonfirmasi(id) {
        Swal.fire({
            title: 'Hapus Postingan?',
            text: "Data yang dihapus tidak bisa dikembalikan!",
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => { if (result.isConfirmed) { window.location.href = 'hapus_postingan.php?photo_id=' + id; } })
    }

    function hapusKomentarKonfirmasi(com_id, photo_id) {
        Swal.fire({
            title: 'Hapus Komentar?',
            text: "Komentar kamu akan hilang selamanya!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'hapus_komentar.php?id=' + com_id + '&photo_id=' + photo_id;
            }
        })
    }

    function laporPostingan(id) {
        Swal.fire({
            title: 'Laporkan Postingan?',
            text: "Kenapa Anda melaporkan postingan ini?",
            icon: 'warning',
            input: 'select',
            inputOptions: {
                'spam': 'Spam / Iklan Terlarang',
                'pencurian': 'Pencurian Karya Hak Cipta',
                'kebencian': 'Ujaran Kebencian / SARA',
                'tidak_pantas': 'Konten Tidak Pantas / Dewasa',
                'lainnya': 'Lainnya'
            },
            inputPlaceholder: 'Pilih alasan laporan',
            showCancelButton: true,
            confirmButtonColor: '#3b82f6',
            confirmButtonText: 'Kirim Laporan',
            cancelButtonText: 'Batal',
            inputValidator: (value) => {
                if (!value) { return 'Anda harus memilih alasan!' }
            }
        }).then((result) => { if (result.isConfirmed) { window.location.href = 'lapor_postingan.php?photo_id=' + id + '&alasan=' + result.value; } })
    }

    function laporKomentar(com_id, photo_id) {
        Swal.fire({
            title: 'Laporkan Komentar?',
            text: "Pilih alasan pelaporan komentar ini:",
            icon: 'warning',
            input: 'select',
            inputOptions: {
                'kasar': 'Kata-kata Kasar / Toxic',
                'spam': 'Spam / Iklan',
                'sara': 'Ujaran Kebencian / SARA',
                'bullying': 'Bullying / Pelecehan',
                'lainnya': 'Lainnya'
            },
            inputPlaceholder: 'Pilih alasan laporan',
            showCancelButton: true,
            confirmButtonColor: '#3b82f6',
            confirmButtonText: 'Laporkan!',
            cancelButtonText: 'Batal',
            inputValidator: (value) => {
                if (!value) { return 'Anda harus memilih alasan!' }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'lapor_komentar.php?id=' + com_id + '&photo_id=' + photo_id + '&alasan=' + result.value;
            }
        })
    }

    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('login') === 'success' || urlParams.get('status') === 'login_success') {
        if (urlParams.get('new') === 'true') {
            Swal.fire({ icon: 'success', title: 'Selamat Datang! ✨', text: 'Akun anda sudah aktif, selamat bergabung di FOTOVIBE!', confirmButtonColor: '#3b82f6' });
        } else {
            Swal.fire({ icon: 'success', title: 'Berhasil Masuk! 👋', text: 'Halo, senang melihat anda kembali!', confirmButtonColor: '#3b82f6' });
        }
    }
    // NOTIFIKASI UPLOAD BERHASIL
    if (urlParams.get('status') === 'upload_success') { 
        Swal.fire({ icon: 'success', title: 'Upload Berhasil! 📸', text: 'Foto keren anda sudah masuk ke galeri.', confirmButtonColor: '#3b82f6' }); 
    }
    if (urlParams.get('status') === 'deleted') { Swal.fire({ icon: 'success', title: 'Berhasil Dihapus!', text: 'Data telah dihapus secara permanen.', confirmButtonColor: '#3b82f6' }); }
    if (urlParams.get('status') === 'reported') { Swal.fire({ icon: 'success', title: 'Laporan Terkirim!', text: 'Terima kasih telah membantu menjaga komunitas.', confirmButtonColor: '#3b82f6' }); }
    if (urlParams.get('status') === 'comment_success') { Swal.fire({ icon: 'success', title: 'Komentar Terkirim!', text: 'Komentar anda sudah muncul.', confirmButtonColor: '#3b82f6' }); }
    if (urlParams.has('status') || urlParams.has('login')) { window.history.replaceState({}, document.title, window.location.pathname); }
</script>

</body>
</html>