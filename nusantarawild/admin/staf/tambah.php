<?php
/**
 * Admin - Form tambah data staf baru.
 */
// === MEMULAI SESSION ===
session_start();

// === CEK LOGIN ===
// Mengecek apakah pengguna sudah login atau belum, jika belum arahkan ke halaman login
if (!isset($_SESSION['user'])) {
    header("Location: ../../login.php");
    exit;
}

// === KONEKSI DATABASE ===
include "../../includes/koneksi.php";

// === PROSES SIMPAN DATA (JIKA FORM DISUBMIT) ===
if (isset($_POST['simpan'])) {
    // Membaca data inputan dari form
    $nama      = $_POST['nama_staf'];
    $jabatan   = $_POST['jabatan'];
    $email     = $_POST['email'];
    $no_hp     = $_POST['no_hp'];
    $deskripsi = $_POST['deskripsi'];

    // === PROSES UPLOAD FOTO ===
    $namaFoto = "";
    // Memeriksa apakah ada file foto yang diunggah
    if ($_FILES['foto']['name'] != "") {
        // Membuat nama file yang unik menggunakan timestamp agar tidak bentrok
        $namaFoto = time() . "_" . $_FILES['foto']['name'];
        // Memindahkan file foto dari folder sementara ke folder tujuan (image/)
        move_uploaded_file(
            $_FILES['foto']['tmp_name'],
            "../../image/" . $namaFoto
        );
    }

    // === EXECUTE QUERY: SIMPAN DATA STAF ===
    // Menyimpan informasi staf baru beserta nama file foto ke tabel 'staf'
    mysqli_query($koneksi, "
        INSERT INTO staf (nama_staf, jabatan, email, no_hp, foto, deskripsi)
        VALUES ('$nama', '$jabatan', '$email', '$no_hp', '$namaFoto', '$deskripsi')
    ");

    // === REDIRECT ===
    // Setelah berhasil menyimpan, kembalikan ke halaman daftar staf
    header("Location: index.php");
    exit;
}

// Mengambil data user yang sedang login untuk ditampilkan di sidebar jika perlu
$user = $_SESSION['user'];
$nama_user = $user['nama'] ?? 'Admin';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Staf - NusantaraWild</title>

    <!-- Memuat Bootstrap & Google Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styling Kustom -->
    <link rel="stylesheet" href="../../style/css/style.css">
</head>
<body class="admin-page">

<!-- Bagian Sidebar Navigasi -->
<div class="sidebar">
    <div class="brand">
        <i class="bi bi-compass"></i> NusantaraWild
    </div>

    <a href="../dashboard.php">
        <i class="bi bi-speedometer2 me-2"></i> Dashboard
    </a>
    <a href="../users/index.php">
        <i class="bi bi-people me-2"></i> Kelola Users
    </a>
    <a href="../destinasi/index.php">
        <i class="bi bi-geo-alt me-2"></i> Kelola Destinasi
    </a>
    <a href="../booking/index.php">
        <i class="bi bi-calendar-check me-2"></i> Kelola Booking
    </a>
    <a href="index.php" class="active">
        <i class="bi bi-person-badge me-2"></i> Kelola Staf
    </a>
    <a href="../kontak/index.php">
        <i class="bi bi-envelope me-2"></i> Kelola Kontak
    </a>

    <hr class="text-white">

    <a href="../../index.php" target="_blank">
        <i class="bi bi-globe me-2"></i> Lihat Website
    </a>
    <a href="../../logout.php">
        <i class="bi bi-box-arrow-right me-2"></i> Logout
    </a>
</div>

<!-- Bagian Konten Utama -->
<div class="main-content with-sidebar">

    <!-- Header Halaman -->
    <div class="admin-page-header animate-in delay-1">
        <h2><i class="bi bi-person-plus-fill me-2"></i>Tambah Anggota Staf</h2>
        <p>Tambahkan staf pelaksana lapangan baru ke sistem NusantaraWild</p>
        <div class="breadcrumb-nav">
            <a href="../dashboard.php">Dashboard</a>
            <i class="bi bi-chevron-right cs-5d7659"></i>
            <a href="index.php">Kelola Staf</a>
            <i class="bi bi-chevron-right cs-5d7659"></i>
            <span>Tambah Staf</span>
        </div>
    </div>

    <!-- Container Form Input Data Staf -->
    <div class="card form-card animate-in delay-2">
        <div class="card-header">
            <i class="bi bi-person-vcard me-2 cs-9baaad"></i> Data Profil Staf
        </div>
        <div class="card-body">

            <!-- Form harus menyertakan enctype="multipart/form-data" untuk unggah file -->
            <form method="POST" enctype="multipart/form-data">

                <!-- Bagian Info Personal -->
                <div class="row">
                    <!-- Input Nama -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">
                            <i class="bi bi-person"></i> Nama Lengkap Staf
                        </label>
                        <input type="text" name="nama_staf" class="form-control form-control-custom"
                               placeholder="Masukkan nama staf" required>
                    </div>

                    <!-- Input Jabatan -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">
                            <i class="bi bi-person-workspace"></i> Jabatan
                        </label>
                        <input type="text" name="jabatan" class="form-control form-control-custom"
                               placeholder="Contoh: Tour Guide, Admin Keuangan" required>
                    </div>
                </div>

                <!-- Bagian Kontak Staf -->
                <div class="row">
                    <!-- Input Email -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">
                            <i class="bi bi-envelope"></i> Email Staf
                        </label>
                        <input type="email" name="email" class="form-control form-control-custom"
                               placeholder="staf@email.com">
                    </div>

                    <!-- Input Nomor HP -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">
                            <i class="bi bi-phone"></i> Nomor HP
                        </label>
                        <input type="text" name="no_hp" class="form-control form-control-custom"
                               placeholder="08xxxxxxxxxx">
                    </div>
                </div>

                <!-- Pemisah Bagian Upload & Bio -->
                <div class="section-divider">
                    <div class="line"></div>
                    <span><i class="bi bi-image me-1"></i> Bio & Media</span>
                    <div class="line"></div>
                </div>

                <!-- Bagian Upload Foto -->
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label-custom">
                            <i class="bi bi-image-fill"></i> Foto Profil Staf
                        </label>
                        <input type="file" name="foto" class="form-control form-control-custom" accept="image/*" required>
                        <div class="form-hint">Format gambar yang didukung: JPG, JPEG, PNG</div>
                    </div>
                </div>

                <!-- Bagian Deskripsi / Bio -->
                <div class="mb-3">
                    <label class="form-label-custom">
                        <i class="bi bi-card-text"></i> Deskripsi Singkat / Keahlian
                    </label>
                    <textarea name="deskripsi" class="form-control form-control-custom" rows="4"
                              placeholder="Ceritakan singkat mengenai tanggung jawab atau biodata staf ini..."></textarea>
                </div>

                <!-- Tombol Aksi Simpan & Kembali -->
                <div class="d-flex gap-3 mt-4">
                    <button type="submit" name="simpan" class="btn-simpan">
                        <i class="bi bi-check-circle"></i> Simpan Staf
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