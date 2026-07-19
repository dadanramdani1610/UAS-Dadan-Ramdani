<?php
/**
 * Admin - Form edit/ubah data destinasi wisata.
 */
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../../login.php");
    exit;
}

include "../../includes/koneksi.php";

$id = $_GET['id'];

$data = mysqli_fetch_assoc(
    mysqli_query($koneksi, "SELECT * FROM destinasi WHERE id='$id'")
);

if (isset($_POST['update'])) {
    $nama = $_POST['nama'];
    $lokasi = $_POST['lokasi'];
    $kategori = $_POST['kategori'];
    $harga = $_POST['harga'];
    $terbaik_dikunjungi = $_POST['terbaik_dikunjungi'];
    $rating = $_POST['rating'];
    $deskripsi = $_POST['deskripsi'];
    $foto = $_FILES['foto']['name'];
    $foto_lama = $_POST['foto_lama'];

    if ($foto != '') {
        $tmp = $_FILES['foto']['tmp_name'];

        // Hapus foto lama jika ada
        if (!empty($foto_lama)) {
            $target_lama = __DIR__ . "/../../image/" . $foto_lama;
            if (file_exists($target_lama)) {
                unlink($target_lama);
            }
        }

        move_uploaded_file($tmp, "../../image/" . $foto);
        $nama_foto = $foto;
    } else {
        $nama_foto = $foto_lama;
    }

    mysqli_query($koneksi, "
        UPDATE destinasi
        SET
        nama='$nama',
        lokasi='$lokasi',
        kategori='$kategori',
        rating='$rating',
        harga='$harga',
        terbaik_dikunjungi='$terbaik_dikunjungi',
        deskripsi='$deskripsi',
        foto='$nama_foto'
        WHERE id='$id'
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
    <title>Edit Destinasi - NusantaraWild</title>

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
        <h2><i class="bi bi-pencil-square me-2"></i>Edit Destinasi Wisata</h2>
        <p>Perbarui informasi detail untuk objek wisata: <strong><?= htmlspecialchars($data['nama']) ?></strong></p>
        <div class="breadcrumb-nav">
            <a href="../dashboard.php">Dashboard</a>
            <i class="bi bi-chevron-right cs-5d7659"></i>
            <a href="index.php">Kelola Destinasi</a>
            <i class="bi bi-chevron-right cs-5d7659"></i>
            <span>Edit Destinasi</span>
        </div>
    </div>

    <!-- Form Card -->
    <div class="card form-card animate-in delay-2">
        <div class="card-header">
            <i class="bi bi-map-fill me-2 cs-00c66c"></i>
            Edit Informasi Destinasi (ID: <?= $data['id'] ?>)
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
                               value="<?= htmlspecialchars($data['nama']) ?>" placeholder="Masukkan nama objek wisata" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">
                            <i class="bi bi-geo-alt"></i> Lokasi / Alamat
                        </label>
                        <input type="text" name="lokasi" class="form-control form-control-custom"
                               value="<?= htmlspecialchars($data['lokasi']) ?>" placeholder="Contoh: Lembang, Bandung Barat" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label-custom">
                            <i class="bi bi-tags"></i> Kategori
                        </label>
                        <select name="kategori" class="form-select form-select-custom" required>
                            <option value="Pantai" <?= ($data['kategori'] == "Pantai") ? "selected" : ""; ?>>🏖️ Pantai</option>
                            <option value="Gunung" <?= ($data['kategori'] == "Gunung") ? "selected" : ""; ?>>⛰️ Gunung</option>
                            <option value="Hutan" <?= ($data['kategori'] == "Hutan") ? "selected" : ""; ?>>🌲 Hutan</option>
                            <option value="Bahari" <?= ($data['kategori'] == "Bahari") ? "selected" : ""; ?>>🐠 Bahari</option>
                            <option value="Danau" <?= ($data['kategori'] == "Danau") ? "selected" : ""; ?>>🏞️ Danau</option>
                            <option value="Taman Nasional" <?= ($data['kategori'] == "Taman Nasional") ? "selected" : ""; ?>>🦁 Taman Nasional</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label-custom">
                            <i class="bi bi-cash-coin"></i> Harga Tiket (Rp)
                        </label>
                        <input type="number" name="harga" class="form-control form-control-custom"
                               value="<?= htmlspecialchars($data['harga']) ?>" placeholder="Contoh: 50000" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label-custom">
                            <i class="bi bi-star"></i> Rating
                        </label>
                        <input type="number" name="rating" class="form-control form-control-custom"
                               min="1" max="5" step="0.1" value="<?= htmlspecialchars($data['rating']) ?>" placeholder="Nilai 1.0 - 5.0" required>
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
                               value="<?= htmlspecialchars($data['terbaik_dikunjungi']) ?>" placeholder="Contoh: April - Oktober">
                        <div class="form-hint">Musim atau rentang bulan yang disarankan</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">
                            <i class="bi bi-image"></i> Ganti Foto Destinasi (Opsional)
                        </label>
                        <input type="file" name="foto" class="form-control form-control-custom">
                        <input type="hidden" name="foto_lama" value="<?= htmlspecialchars($data['foto']) ?>">
                        <div class="form-hint">Biarkan kosong jika tidak ingin mengganti foto saat ini</div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">
                            <i class="bi bi-image-fill"></i> Foto Saat Ini
                        </label>
                        <div class="mt-2">
                            <?php if (!empty($data['foto'])): ?>
                                <img src="../../image/<?= htmlspecialchars($data['foto']) ?>"
                                     class="img-thumbnail cs-c7d726"
                                     alt="Foto Destinasi">
                            <?php else: ?>
                                <p class="text-muted italic">Tidak ada foto</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="mb-3 mt-3">
                    <label class="form-label-custom">
                        <i class="bi bi-card-text"></i> Deskripsi Lengkap
                    </label>
                    <textarea name="deskripsi" class="form-control form-control-custom" rows="4"
                              placeholder="Tulis penjelasan mendalam mengenai destinasi wisata ini..."><?= htmlspecialchars($data['deskripsi']) ?></textarea>
                </div>

                <!-- Buttons -->
                <div class="d-flex gap-3 mt-4">
                    <button type="submit" name="update" class="btn-update">
                        <i class="bi bi-arrow-repeat"></i> Update Destinasi
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