<?php
/**
 * Admin - Form tambah user baru.
 */
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../../login.php");
    exit;
}

include "../../includes/koneksi.php";

if (isset($_POST['simpan'])) {

    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $no_hp = $_POST['no_hp'];
    $provinsi = $_POST['provinsi'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    mysqli_query($koneksi, "
        INSERT INTO users
        (nama,email,no_hp,provinsi,password,role)
        VALUES
        ('$nama','$email','$no_hp','$provinsi','$password','$role')
    ");

    header("Location: index.php");
    exit;
}

$user = $_SESSION['user'];
$nama_user = $user['nama'] ?? 'Admin';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah User - NusantaraWild</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../../style/css/style.css">
</head>
<body class="admin-page">

<!-- Main Content -->
<div class="main-content">

    <!-- Page Header -->
    <div class="admin-page-header animate-in delay-1">
        <h2><i class="bi bi-person-plus-fill me-2"></i>Tambah User Baru</h2>
        <p>Tambahkan pengguna baru ke dalam sistem NusantaraWild</p>
        <div class="breadcrumb-nav">
            <a href="../dashboard.php">Dashboard</a>
            <i class="bi bi-chevron-right cs-5d7659"></i>
            <a href="index.php">Kelola Users</a>
            <i class="bi bi-chevron-right cs-5d7659"></i>
            <span>Tambah User</span>
        </div>
    </div>

    <!-- Form Card -->
    <div class="card form-card animate-in delay-2">
        <div class="card-header">
            <i class="bi bi-person-vcard me-2 cs-9baaad"></i>
            Informasi User
        </div>
        <div class="card-body">

            <form method="POST">

                <!-- Personal Info Section -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">
                            <i class="bi bi-person"></i> Nama Lengkap
                        </label>
                        <input type="text" name="nama" class="form-control form-control-custom"
                               placeholder="Masukkan nama lengkap" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">
                            <i class="bi bi-envelope"></i> Email
                        </label>
                        <input type="email" name="email" class="form-control form-control-custom"
                               placeholder="contoh@email.com" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">
                            <i class="bi bi-phone"></i> No HP
                        </label>
                        <input type="text" name="no_hp" class="form-control form-control-custom"
                               placeholder="08xxxxxxxxxx" required>
                        <div class="form-hint">Masukkan nomor handphone yang aktif</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">
                            <i class="bi bi-geo-alt"></i> Provinsi
                        </label>
                        <input type="text" name="provinsi" class="form-control form-control-custom"
                               placeholder="Contoh: Jawa Barat" required>
                    </div>
                </div>

                <!-- Security Section -->
                <div class="section-divider">
                    <div class="line"></div>
                    <span><i class="bi bi-shield-lock me-1"></i> Keamanan & Hak Akses</span>
                    <div class="line"></div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">
                            <i class="bi bi-lock"></i> Password
                        </label>
                        <input type="password" name="password" class="form-control form-control-custom"
                               placeholder="Buat password" required>
                        <div class="form-hint">Minimal 6 karakter</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">
                            <i class="bi bi-shield-check"></i> Role
                        </label>
                        <select name="role" class="form-select form-select-custom">
                            <option value="user">👤 User</option>
                            <option value="admin">🛡️ Admin</option>
                        </select>
                        <div class="form-hint">Admin memiliki akses penuh ke dashboard</div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="d-flex gap-3 mt-4">
                    <button type="submit" name="simpan" class="btn-simpan">
                        <i class="bi bi-check-circle"></i> Simpan User
                    </button>
                    <a href="index.php" class="btn-kembali">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>

</body>
</html>