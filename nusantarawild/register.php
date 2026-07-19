<?php
/**
 * Halaman pendaftaran akun user baru.
 */
session_start();
include 'includes/koneksi.php';
include 'includes/swal_helper.php';

if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

$error = [];
$success = "";
$old = []; // Untuk mengisi ulang form

// === PROSES FORM REGISTRASI ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama     = trim($_POST['nama'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $telepon  = trim($_POST['telepon'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $konfirm  = trim($_POST['konfirm'] ?? '');
    $provinsi = trim($_POST['provinsi'] ?? '');
    $setuju   = isset($_POST['setuju']);

    $old = compact('nama', 'email', 'telepon', 'provinsi');

    // === BRANCHING: Validasi form ===
    if (strlen($nama) < 3) {
        $error[] = "Nama minimal 3 karakter.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error[] = "Format email tidak valid.";
    }
    if (!preg_match('/^[0-9]{10,13}$/', $telepon)) {
        $error[] = "Nomor telepon harus 10-13 digit angka.";
    }
    if (strlen($password) < 6) {
        $error[] = "Password minimal 6 karakter.";
    }
    if ($password !== $konfirm) {
        $error[] = "Konfirmasi password tidak sesuai.";
    }
    if (empty($provinsi)) {
        $error[] = "Pilih provinsi asal Anda.";
    }
    if (!$setuju) {
        $error[] = "Anda harus menyetujui syarat dan ketentuan.";
    }

    // === BRANCHING: Jika ada error validasi ===
    if (!empty($error)) {
        swal_flash('error', 'Registrasi Gagal', implode(' ', $error));
        header('Location: register.php');
        exit;
    }

    // cek email sudah ada atau belum
    $cek = mysqli_query(
        $koneksi,
        "SELECT id FROM users WHERE email='$email'"
    );

    if (mysqli_num_rows($cek) > 0) {
        swal_flash('error', 'Registrasi Gagal', 'Email sudah terdaftar!');
        header('Location: register.php');
        exit;
    } else {
        $password_hash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        mysqli_query(
            $koneksi,
            "INSERT INTO users
            (nama,email,no_hp,provinsi,password)
            VALUES
            ('$nama',
             '$email',
             '$telepon',
             '$provinsi',
             '$password_hash')"
        );

        // Ambil ID user yang baru dibuat
        $id_user = mysqli_insert_id($koneksi);

        $_SESSION['user'] = [
            'id_user' => $id_user,
            'nama' => $nama,
            'email' => $email,
            'role' => 'user'
        ];
        
        swal_flash('success', 'Registrasi Berhasil', 'Selamat datang, ' . $nama . '!', 'index.php');
        header('Location: index.php');
        exit;
    }
}

// Data provinsi Indonesia
$provinsi_list = [
    "Aceh", "Sumatera Utara", "Sumatera Barat", "Riau", "Jambi",
    "Sumatera Selatan", "Bengkulu", "Lampung", "Kepulauan Bangka Belitung",
    "Kepulauan Riau", "DKI Jakarta", "Jawa Barat", "Jawa Tengah",
    "DI Yogyakarta", "Jawa Timur", "Banten", "Bali",
    "Nusa Tenggara Barat", "Nusa Tenggara Timur", "Kalimantan Barat",
    "Kalimantan Tengah", "Kalimantan Selatan", "Kalimantan Timur",
    "Kalimantan Utara", "Sulawesi Utara", "Sulawesi Tengah",
    "Sulawesi Selatan", "Sulawesi Tenggara", "Gorontalo", "Sulawesi Barat",
    "Maluku", "Maluku Utara", "Papua Barat", "Papua"
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - NusantaraWild</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body class="auth-page">

<div class="auth-container">
    <div class="auth-left register-bg d-none d-lg-flex cs-820dbe">
        <div class="auth-left-content">
            <a href="index.php" class="auth-logo">
                <i class="bi bi-compass me-2"></i>NusantaraWild
            </a>
            <h2>Bergabung Bersama Kami</h2>
            <p>Daftar gratis dan mulai menjelajahi keindahan alam Nusantara bersama komunitas wisatawan Indonesia.</p>
            <div class="auth-stats">
                <div class="auth-stat"><span>128K+</span><small>Anggota Aktif</small></div>
                <div class="auth-stat"><span>500+</span><small>Destinasi</small></div>
                <div class="auth-stat"><span>4.9★</span><small>Rating App</small></div>
            </div>
        </div>
    </div>
    <div class="auth-right">
        <div class="auth-form-wrap">
            <a href="index.php" class="d-lg-none auth-logo-mobile">
                <i class="bi bi-compass me-2"></i>NusantaraWild
            </a>
            <h3 class="auth-title">Buat Akun Baru</h3>
            <p class="auth-sub text-muted">Sudah punya akun? <a href="login.php" class="text-success">Masuk di sini</a></p>

            <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0 ps-3">
                    <?php foreach ($error as $e): ?>
                    <li><?= $e ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill me-2"></i><?= $success ?>
            </div>
            <?php endif; ?>

            <!-- FORM INPUT HTML-PHP: Registrasi -->
            <form action="register.php" method="POST" class="auth-form">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-person text-success"></i></span>
                            <input type="text" name="nama" class="form-control"
                                   placeholder="Masukkan nama lengkap"
                                   value="<?= htmlspecialchars($old['nama'] ?? '') ?>" required>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Alamat Email <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-envelope text-success"></i></span>
                            <input type="email" name="email" class="form-control"
                                   placeholder="nama@email.com"
                                   value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Nomor Telepon <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-phone text-success"></i></span>
                            <input type="tel" name="telepon" class="form-control"
                                   placeholder="08xxxxxxxxxx"
                                   value="<?= htmlspecialchars($old['telepon'] ?? '') ?>" required>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Provinsi Asal <span class="text-danger">*</span></label>
                        <select name="provinsi" class="form-select" required>
                            <option value="">-- Pilih Provinsi --</option>
                            <?php
                            // === LOOPING: Render options provinsi ===
                            foreach ($provinsi_list as $prov):
                                $selected = (isset($old['provinsi']) && $old['provinsi'] === $prov) ? 'selected' : '';
                            ?>
                            <option value="<?= $prov ?>" <?= $selected ?>><?= $prov ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-lock text-success"></i></span>
                            <input type="password" name="password" id="pass1" class="form-control"
                                   placeholder="Min. 6 karakter" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePass('pass1','eye1')">
                                <i class="bi bi-eye" id="eye1"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Konfirmasi Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-lock-fill text-success"></i></span>
                            <input type="password" name="konfirm" id="pass2" class="form-control"
                                   placeholder="Ulangi password" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePass('pass2','eye2')">
                                <i class="bi bi-eye" id="eye2"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="setuju" id="setuju" required>
                            <label class="form-check-label" for="setuju">
                                Saya menyetujui <a href="#" class="text-success">Syarat & Ketentuan</a> serta
                                <a href="#" class="text-success">Kebijakan Privasi</a>
                            </label>
                        </div>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-green w-100 btn-lg">
                            <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function togglePass(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    input.type = input.type === 'password' ? 'text' : 'password';
    icon.classList.toggle('bi-eye');
    icon.classList.toggle('bi-eye-slash');
}
</script>
<?= swal_render() ?>
</body>
</html>
