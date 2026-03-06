<?php
include 'koneksi.php';

$register_success = false; // Flag untuk trigger SweetAlert

if (isset($_POST['register'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5($_POST['password']);

    $cek_email = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    
    if (mysqli_num_rows($cek_email) > 0) {
        $error_msg = "Email sudah digunakan, silakan gunakan email lain!";
    } else {
        $query = "INSERT INTO users (nama, email, password) VALUES ('$nama', '$email', '$password')";
        if (mysqli_query($conn, $query)) {
            $register_success = true;
        } else {
            $error_msg = "Terjadi kesalahan saat mendaftar.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun | FOTOVIBE.</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root { 
            --primary: #3b82f6; 
            --primary-dark: #2563eb;
            --bg: #f8fafc; 
        }
        
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: var(--bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            background-image: radial-gradient(circle at 100% 0%, rgba(59, 130, 246, 0.15) 0%, transparent 50%),
                              radial-gradient(circle at 0% 100%, rgba(99, 102, 241, 0.15) 0%, transparent 50%);
        }

        /* Tombol Kembali melayang di kiri atas */
        .back-to-home {
            position: absolute;
            top: 25px;
            left: 25px;
            z-index: 1000;
        }

        .swal2-popup {
            border: 3px solid var(--primary) !important;
            border-radius: 25px !important;
            box-shadow: 0 0 30px rgba(59, 130, 246, 0.3) !important;
        }

        .register-card {
            border: 3px solid var(--primary); 
            border-radius: 30px;
            background: white;
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.6), 0 15px 60px rgba(59, 130, 246, 0.3);
            padding: 40px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .register-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 80px rgba(59, 130, 246, 0.4);
        }

        .hero-title {
            font-weight: 800;
            background: linear-gradient(135deg, #3b82f6, #0ea5e9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -1px;
        }

        .welcome-divider {
            width: 50px; height: 4px;
            background: linear-gradient(90deg, var(--primary), #0ea5e9);
            margin: 10px auto 0; border-radius: 10px;
        }

        .input-wrapper { position: relative; margin-bottom: 15px; }
        .input-wrapper i { position: absolute; left: 20px; top: 50%; transform: translateY(-50%); color: #94a3b8; z-index: 10; }

        .form-control-custom {
            width: 100%; height: 55px; background-color: #f1f5f9;
            border: 2px solid var(--primary); border-radius: 100px;
            padding-left: 55px; padding-right: 20px; outline: none;
            transition: 0.3s; font-weight: 500;
        }

        .form-control-custom:focus { background-color: #fff; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2); }

        .btn-auth {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border: none; border-radius: 100px; padding: 15px;
            font-weight: 700; color: white; transition: 0.3s;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }
        .btn-auth:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(59, 130, 246, 0.5); color: white; }

        .text-primary { color: var(--primary) !important; }
    </style>
</head>
<body>

<div class="back-to-home">
    <a href="index.php" class="text-decoration-none text-secondary fw-bold bg-white px-3 py-2 rounded-pill shadow-sm border">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
    </a>
</div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="text-center mb-4">
                <a href="index.php" class="text-decoration-none"><h2 class="hero-title">✨ FOTOVIBE.</h2></a>
                <p class="text-muted small mb-0">Buat akun untuk mulai berkarya.</p>
                <div class="welcome-divider"></div>
            </div>
            
            <div class="card register-card">
                <?php if(isset($error_msg)): ?>
                    <div class="alert alert-danger border-0 small rounded-4 text-center py-2 mb-4">
                        <?php echo $error_msg; ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-2">
                        <label class="form-label small fw-bold text-secondary ms-3">Nama Lengkap</label>
                        <div class="input-wrapper">
                            <i class="bi bi-person-badge-fill"></i>
                            <input type="text" name="nama" class="form-control-custom" placeholder="Nama Anda" required>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label small fw-bold text-secondary ms-3">Email Address</label>
                        <div class="input-wrapper">
                            <i class="bi bi-envelope-at-fill"></i>
                            <input type="email" name="email" class="form-control-custom" placeholder="nama@email.com" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-secondary ms-3">Buat Password</label>
                        <div class="input-wrapper">
                            <i class="bi bi-shield-lock-fill"></i>
                            <input type="password" name="password" class="form-control-custom" placeholder="••••••••" required>
                        </div>
                    </div>

                    <button type="submit" name="register" class="btn btn-auth w-100">
                        Daftar Sekarang <i class="bi bi-person-plus-fill ms-1"></i>
                    </button>
                </form>

                <div class="text-center mt-4">
                    <p class="small text-muted mb-0">Sudah punya akun? <a href="login.php" class="text-primary fw-bold text-decoration-none">Masuk di sini</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php if ($register_success): ?>
<script>
    Swal.fire({
        icon: 'success',
        title: 'Akun Berhasil Dibuat!',
        text: 'Silakan login untuk mulai menjelajah FOTOVIBE.',
        confirmButtonColor: '#3b82f6',
        confirmButtonText: 'Lanjut Login',
        allowOutsideClick: false
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'login.php?new=true';
        }
    });
</script>
<?php endif; ?>

</body>
</html>