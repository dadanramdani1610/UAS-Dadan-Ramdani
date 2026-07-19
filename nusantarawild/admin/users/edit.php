<?php
/**
 * Admin - Form edit data user (termasuk role).
 */
// === MEMULAI SESSION ===
session_start();

// === CEK LOGIN ===
// Mengecek apakah pengguna sudah login, jika belum maka arahkan ke halaman login
if (!isset($_SESSION['user'])) {
    header("Location: ../../login.php");
    exit;
}

// === KONEKSI DATABASE ===
include "../../includes/koneksi.php";

// === MENGAMBIL ID DARI URL ===
// Menangkap ID user yang akan diedit dari parameter GET
$id = $_GET['id'];

// === QUERY: AMBIL DATA USER AKTIF ===
// Mengambil data spesifik user berdasarkan ID
$data = mysqli_fetch_assoc(
    mysqli_query(
        $koneksi,
        "SELECT * FROM users WHERE id='$id'"
    )
);

// === PROSES UPDATE DATA (JIKA FORM DISUBMIT) ===
if (isset($_POST['update'])) {

    // Membaca dan mengamankan data inputan dari form
    $nama     = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email    = mysqli_real_escape_string($koneksi, $_POST['email']);
    $no_hp    = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
    $provinsi = mysqli_real_escape_string($koneksi, $_POST['provinsi']);
    $role     = mysqli_real_escape_string($koneksi, $_POST['role']);

    // === EXECUTE QUERY: UPDATE DATA USER ===
    // Memperbarui informasi profil dan hak akses (role) di tabel users
    mysqli_query($koneksi, "
        UPDATE users
        SET
            nama='$nama',
            email='$email',
            no_hp='$no_hp',
            provinsi='$provinsi',
            role='$role'
        WHERE id='$id'
    ");

    // === REDIRECT ===
    // Setelah berhasil update, arahkan pengguna kembali ke halaman daftar user
    header("Location: index.php");
    exit;
}

// Mengambil data user yang saat ini sedang login
$user = $_SESSION['user'];
$nama_user = $user['nama'] ?? 'Admin';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - NusantaraWild</title>

    <!-- Memuat Bootstrap & Ikon -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts (Inter) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styling Kustom -->
    <link rel="stylesheet" href="../../style/css/style.css">
</head>
<body class="admin-page">

<!-- Konten Utama -->
<div class="main-content">

    <!-- Header Halaman -->
    <div class="admin-page-header animate-in delay-1">
        <h2><i class="bi bi-pencil-square me-2"></i>Edit Data User</h2>
        <p>Perbarui informasi akun untuk pengguna: <strong><?= htmlspecialchars($data['nama']) ?></strong></p>
        <div class="breadcrumb-nav">
            <a href="../dashboard.php">Dashboard</a>
            <i class="bi bi-chevron-right cs-5d7659"></i>
            <a href="index.php">Kelola Users</a>
            <i class="bi bi-chevron-right cs-5d7659"></i>
            <span>Edit User</span>
        </div>
    </div>

    <!-- Container Form Edit -->
    <div class="card form-card animate-in delay-2">
        <div class="card-header">
            <i class="bi bi-person-vcard me-2 cs-00c66c"></i>
            Informasi User (ID: <?= htmlspecialchars($data['id']) ?>)
        </div>
        <div class="card-body">

            <form method="POST">

                <!-- Bagian Informasi Pribadi -->
                <div class="row">
                    <!-- Input Nama -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">
                            <i class="bi bi-person"></i> Nama Lengkap
                        </label>
                        <input type="text" name="nama" class="form-control form-control-custom"
                               value="<?= htmlspecialchars($data['nama']) ?>" placeholder="Masukkan nama lengkap" required>
                    </div>

                    <!-- Input Email -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">
                            <i class="bi bi-envelope"></i> Email
                        </label>
                        <input type="email" name="email" class="form-control form-control-custom"
                               value="<?= htmlspecialchars($data['email']) ?>" placeholder="contoh@email.com" required>
                    </div>
                </div>

                <!-- Bagian Kontak & Lokasi -->
                <div class="row">
                    <!-- Input No HP -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">
                            <i class="bi bi-phone"></i> No HP
                        </label>
                        <input type="text" name="no_hp" class="form-control form-control-custom"
                               value="<?= htmlspecialchars($data['no_hp']) ?>" placeholder="08xxxxxxxxxx" required>
                        <div class="form-hint">Masukkan nomor handphone yang aktif</div>
                    </div>

                    <!-- Input Provinsi -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">
                            <i class="bi bi-geo-alt"></i> Provinsi
                        </label>
                        <input type="text" name="provinsi" class="form-control form-control-custom"
                               value="<?= htmlspecialchars($data['provinsi']) ?>" placeholder="Contoh: Jawa Barat" required>
                    </div>
                </div>

                <!-- Pemisah Bagian Hak Akses -->
                <div class="section-divider">
                    <div class="line"></div>
                    <span><i class="bi bi-shield-check me-1"></i> Hak Akses</span>
                    <div class="line"></div>
                </div>

                <!-- Pilihan Role (Hak Akses) -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">
                            <i class="bi bi-shield-lock"></i> Role
                        </label>
                        <select name="role" class="form-select form-select-custom">
                            <option value="user" <?= ($data['role'] == 'user') ? 'selected' : '' ?>>👤 User</option>
                            <option value="admin" <?= ($data['role'] == 'admin') ? 'selected' : '' ?>>🛡️ Admin</option>
                        </select>
                        <div class="form-hint">Tentukan tingkat kewenangan user dalam sistem</div>
                    </div>
                </div>

                <!-- Tombol Aksi Simpan & Kembali -->
                <div class="d-flex gap-3 mt-4">
                    <button type="submit" name="update" class="btn-update">
                        <i class="bi bi-arrow-repeat"></i> Update User
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