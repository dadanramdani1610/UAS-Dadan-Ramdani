<?php
/**
 * Admin - Form tambah destinasi wisata baru.
 */
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../../login.php");
    exit;
}

include "../../includes/koneksi.php";

if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $lokasi = $_POST['lokasi'];
    $kategori = $_POST['kategori'];   
    $harga = $_POST['harga'];
    $terbaik_dikunjungi = $_POST['terbaik_dikunjungi'];
    $rating = $_POST['rating'];       
    $deskripsi = $_POST['deskripsi'];
    
    // File upload logic
    $foto = $_FILES['foto']['name'];
    $tmp  = $_FILES['foto']['tmp_name'];
    if (!empty($foto)) {
        move_uploaded_file($tmp, "../../image/" . $foto);
    } else {
        $foto = "";
    }

    mysqli_query($koneksi, "
        INSERT INTO destinasi
        (nama, lokasi, harga, deskripsi, foto, kategori, rating, terbaik_dikunjungi)
        VALUES
        ('$nama', '$lokasi', '$harga', '$deskripsi', '$foto', '$kategori', '$rating', '$terbaik_dikunjungi')
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
    <title>Tambah Destinasi - NusantaraWild</title>

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
        <h2><i class="bi bi-plus-circle-fill me-2"></i>Tambah Destinasi Baru</h2>
        <p>Tambahkan lokasi wisata baru beserta informasi lengkapnya</p>
        <div class="breadcrumb-nav">
            <a href="../dashboard.php">Dashboard</a>
            <i class="bi bi-chevron-right cs-5d7659"></i>
            <a href="index.php">Kelola Destinasi</a>
            <i class="bi bi-chevron-right cs-5d7659"></i>
            <span>Tambah Destinasi</span>
        </div>
    </div>

    <!-- Form Card -->
    <div class="card form-card animate-in delay-2">
        <div class="card-header">
            <i class="bi bi-map-fill me-2 cs-9baaad"></i>
            Detail Informasi Destinasi
        </div>
        <div class="card-body">

            <form method="POST" enctype="multipart/form-data">

                <!-- Basic Info Section -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">
                            <i class="bi bi-card-heading"></i> Nama Destinasi
                        </label>
                        <input type="text" name="nama" class="form-control form-control-custom"
                               placeholder="Masukkan nama objek wisata" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">
                            <i class="bi bi-geo-alt"></i> Lokasi / Alamat
                        </label>
                        <input type="text" name="lokasi" class="form-control form-control-custom"
                               placeholder="Contoh: Lembang, Bandung Barat" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label-custom">
                            <i class="bi bi-tags"></i> Kategori
                        </label>
                        <select name="kategori" class="form-select form-select-custom" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Pantai">🏖️ Pantai</option>
                            <option value="Gunung">⛰️ Gunung</option>
                            <option value="Hutan">🌲 Hutan</option>
                            <option value="Bahari">🐠 Bahari</option>
                            <option value="Danau">🏞️ Danau</option>
                            <option value="Taman Nasional">🦁 Taman Nasional</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label-custom">
                            <i class="bi bi-cash-coin"></i> Harga Tiket (Rp)
                        </label>
                        <input type="number" name="harga" class="form-control form-control-custom"
                               placeholder="Contoh: 50000" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label-custom">
                            <i class="bi bi-star"></i> Rating
                        </label>
                        <input type="number" name="rating" class="form-control form-control-custom"
                               min="1" max="5" step="0.1" placeholder="Nilai 1.0 - 5.0" required>
                    </div>
                </div>

                <!-- Additional Info Section -->
                <div class="section-divider">
                    <div class="line"></div>
                    <span><i class="bi bi-info-circle me-1"></i> Informasi Tambahan & Media</span>
                    <div class="line"></div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">
                            <i class="bi bi-calendar-range"></i> Waktu Terbaik Dikunjungi
                        </label>
                        <input type="text" name="terbaik_dikunjungi" class="form-control form-control-custom"
                               placeholder="Contoh: April - Oktober">
                        <div class="form-hint">Musim atau rentang bulan yang disarankan</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">
                            <i class="bi bi-image"></i> Upload Foto Destinasi
                        </label>
                        <input type="file" name="foto" class="form-control form-control-custom" required>
                        <div class="form-hint">Gunakan format gambar JPG, JPEG, atau PNG</div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label-custom">
                        <i class="bi bi-card-text"></i> Deskripsi Lengkap
                    </label>
                    <textarea name="deskripsi" class="form-control form-control-custom" rows="4"
                              placeholder="Tulis penjelasan mendalam mengenai destinasi wisata ini..."></textarea>
                </div>

                <!-- Buttons -->
                <div class="d-flex gap-3 mt-4">
                    <button type="submit" name="simpan" class="btn-simpan">
                        <i class="bi bi-check-circle"></i> Simpan Destinasi
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