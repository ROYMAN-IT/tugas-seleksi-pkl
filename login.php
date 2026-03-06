<?php
include 'koneksi.php';
session_start();

$login_error = false;

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5($_POST['password']);

    $query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result); 
        $_SESSION['user_id'] = $data['id']; 
        $_SESSION['nama'] = $data['nama'];
        
        if (isset($_GET['new']) && $_GET['new'] === 'true') {
            header("Location: index.php?login=success&new=true");
        } else {
            header("Location: index.php?login=success");
        }
        exit(); 
    } else {
        $login_error = true;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk | FOTOVIBE.</title>
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
            background-image: radial-gradient(circle at 0% 0%, rgba(59, 130, 246, 0.15) 0%, transparent 50%),
                              radial-gradient(circle at 100% 100%, rgba(14, 165, 233, 0.15) 0%, transparent 50%);
        }

        /* Tombol Kembali Kiri Atas */
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

        .login-card {
            border: 3px solid var(--primary); 
            border-radius: 30px;
            background: white;
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.6), 0 15px 60px rgba(59, 130, 246, 0.3);
            padding: 40px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .login-card:hover {
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

        .input-wrapper { position: relative; margin-bottom: 20px; }
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
                <p class="text-muted small mb-0">
                    <?php echo (isset($_GET['new']) && $_GET['new'] == 'true') ? 'Selamat datang!' : 'Selamat datang kembali!'; ?>
                </p>
                <div class="welcome-divider"></div>
            </div>
            
            <div class="card login-card">
                <form method="POST" action="<?php echo isset($_GET['new']) ? 'login.php?new=true' : 'login.php'; ?>">
                    <div class="mb-2">
                        <label class="form-label small fw-bold text-secondary ms-3">Email Address</label>
                        <div class="input-wrapper">
                            <i class="bi bi-envelope-at-fill"></i>
                            <input type="email" name="email" class="form-control-custom" placeholder="nama@email.com" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-secondary ms-3">Password</label>
                        <div class="input-wrapper">
                            <i class="bi bi-shield-lock-fill"></i>
                            <input type="password" name="password" class="form-control-custom" placeholder="••••••••" required>
                        </div>
                    </div>

                    <button type="submit" name="login" class="btn btn-auth w-100">
                        Masuk Sekarang <i class="bi bi-arrow-right-short ms-1"></i>
                    </button>
                </form>

                <div class="text-center mt-4">
                    <p class="small text-muted mb-0">Belum punya akun? <a href="register.php" class="text-primary fw-bold text-decoration-none">Daftar Sekarang</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    <?php if ($login_error): ?>
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Email atau Password salah!',
        confirmButtonColor: '#3b82f6'
    });
    <?php endif; ?>

    const urlParams = new URLSearchParams(window.location.search);
    
    if (urlParams.get('new') === 'true') {
        Swal.fire({
            icon: 'success',
            title: 'Akun Siap!',
            text: 'Sekarang silakan login untuk masuk ke dashboard.',
            confirmButtonColor: '#3b82f6'
        });
    }

    if (urlParams.get('pesan') === 'logout') {
        Swal.fire({
            icon: 'info',
            title: 'Berhasil Keluar',
            text: 'Sampai jumpa lagi di FOTOVIBE!',
            timer: 2000,
            showConfirmButton: false
        });
        window.history.replaceState({}, document.title, window.location.pathname);
    }
</script>

</body>
</html>