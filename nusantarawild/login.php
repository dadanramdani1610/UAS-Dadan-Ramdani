<?php
/**
 * Halaman Login untuk user maupun admin.
 */
session_start();
include 'includes/koneksi.php';


// Jika sudah login, redirect
if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

$error = "";
$success = "";

// === PROSES FORM LOGIN (POST) ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $ingat_saya = isset($_POST['ingat_saya']);

    // === BRANCHING: Validasi input ===
    if (empty($email) || empty($password)) {
        $error = "Email dan password harus diisi!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid!";
    } else {
        // Simulasi database user (array)
        $query = mysqli_query(
            $koneksi,
            "SELECT * FROM users WHERE email='$email'"
        );

        $user_found = mysqli_fetch_assoc($query);

        // === BRANCHING: Hasil login ===
        if (
            $user_found &&
            password_verify($password, $user_found['password'])
        ) {
            // Set session
            $_SESSION['user'] = [
                'id_user' => $user_found['id'],
                'nama' => $user_found['nama'],
                'email' => $user_found['email'],
                'role' => $user_found['role'],
                'login_time' => time()
            ];
            // Set cookie jika "ingat saya"
            if ($ingat_saya) {
                setcookie('remember_email', $email, time() + (86400 * 30), "/");
            }
            $success = "Login berhasil! Selamat datang, {$user_found['nama']}!";
            header('Location: index.php');
            exit;
        } else {
            $error = "Email atau password salah!";
            // stay on the same page to show error message

        }
    }
}

// Cek cookie remember
$remembered_email = $_COOKIE['remember_email'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - NusantaraWild</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="style/css/style.css">

</head>

<body class="auth-page">

    <div class="auth-container">
        <div class="auth-left login-bg d-none d-lg-flex">
            <div class="auth-left-content">
                <a href="index.php" class="auth-logo">
                    <i class="bi bi-compass me-2"></i>NusantaraWild
                </a>
                <h2>Selamat Datang Kembali</h2>
                <p>Masuk untuk menjelajahi ribuan destinasi wisata alam Indonesia yang menakjubkan.</p>
                <div class="auth-features">
                    <div class="auth-feature-item"><i class="bi bi-check-circle-fill me-2"></i>Simpan destinasi favorit
                    </div>
                    <div class="auth-feature-item"><i class="bi bi-check-circle-fill me-2"></i>Riwayat perjalanan</div>
                    <div class="auth-feature-item"><i class="bi bi-check-circle-fill me-2"></i>Notifikasi promo
                        eksklusif</div>
                </div>
            </div>
        </div>
        <div class="auth-right">
            <div class="auth-form-wrap">
                <a href="index.php" class="d-lg-none auth-logo-mobile">
                    <i class="bi bi-compass me-2"></i>NusantaraWild
                </a>
                <h3 class="auth-title">Masuk Akun</h3>
                <p class="auth-sub text-muted">Belum punya akun? <a href="register.php" class="text-success">Daftar
                        Gratis</a></p>

                <?php if ($error): ?>
                    <div class="alert alert-danger d-flex align-items-center gap-2">
                        <i class="bi bi-exclamation-circle-fill"></i> <?= $error ?>
                    </div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill"></i> <?= $success ?>
                    </div>
                <?php endif; ?>

                <!-- FORM INPUT HTML-PHP: Login -->
                <form action="login.php" method="POST" class="auth-form">
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Alamat Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-envelope text-success"></i></span>
                            <input type="email" name="email" class="form-control" placeholder="nama@email.com"
                                value="<?= htmlspecialchars($remembered_email) ?>" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-lock text-success"></i></span>
                            <input type="password" name="password" id="inputPassword" class="form-control"
                                placeholder="Masukkan password" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                                <i class="bi bi-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-4 d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="ingat_saya" id="ingatSaya"
                                <?= !empty($remembered_email) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="ingatSaya">Ingat saya</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-green w-100 btn-lg">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                    </button>
                </form>

                <div class="demo-accounts mt-4">
                    <p class="text-muted text-center small mb-2">Akun Demo:</p>
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="demo-account-box" onclick="fillDemo('dadan@gmail.com','123456')">
                                <i class="bi bi-person me-1"></i>
                                <div><small class="d-block fw-semibold">User Biasa</small>
                                    <small class="text-muted">dadan@email.com</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="demo-account-box" onclick="fillDemo('admin@gmail.com','123456')">
                                <i class="bi bi-shield-check me-1"></i>
                                <div><small class="d-block fw-semibold">Admin</small>
                                    <small class="text-muted">admin@gmail.com</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function togglePassword() {
            const input = document.getElementById('inputPassword');
            const icon = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        }
        function fillDemo(email, pass) {
            document.querySelector('[name="email"]').value = email;
            document.querySelector('[name="password"]').value = pass;
        }
    </script>

</body>

</html>