<?php
/**
 * Admin - Form edit data staf.
 */
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../../login.php");
    exit;
}

include "../../includes/koneksi.php";

$id = $_GET['id'];

$data = mysqli_fetch_assoc(
    mysqli_query($koneksi, "
        SELECT *
        FROM staf
        WHERE id_staf='$id'
    ")
);

if (isset($_POST['update'])) {
    $nama = $_POST['nama_staf'];
    $jabatan = $_POST['jabatan'];
    $email = $_POST['email'];
    $no_hp = $_POST['no_hp'];
    $deskripsi = $_POST['deskripsi'];

    $fotoBaru = $_FILES['foto']['name'];
    $fotoLama = $_POST['foto_lama'];

    if ($fotoBaru != "") {
        $namaFoto = time() . "_" . $fotoBaru;
        move_uploaded_file(
            $_FILES['foto']['tmp_name'],
            "../../image/" . $namaFoto
        );
        // Hapus foto lama jika bukan default
        if (!empty($fotoLama) && file_exists("../../image/" . $fotoLama)) {
            unlink("../../image/" . $fotoLama);
        }
    } else {
        $namaFoto = $fotoLama;
    }

    mysqli_query($koneksi, "
        UPDATE staf
        SET
        nama_staf='$nama',
        jabatan='$jabatan',
        email='$email',
        no_hp='$no_hp',
        foto='$namaFoto',
        deskripsi='$deskripsi'
        WHERE id_staf='$id'
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
    <title>Edit Staf - NusantaraWild</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../../style/css/style.css">
</head>
<body class="admin-page">

<!-- Sidebar -->
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

<!-- Main Content -->
<div class="main-content with-sidebar">

    <!-- Page Header -->
    <div class="admin-page-header animate-in delay-1">
        <h2><i class="bi bi-pencil-square me-2"></i>Edit Data Staf</h2>
        <p>Perbarui rincian data staf: <strong><?= htmlspecialchars($data['nama_staf']) ?></strong></p>
        <div class="breadcrumb-nav">
            <a href="../dashboard.php">Dashboard</a>
            <i class="bi bi-chevron-right cs-5d7659"></i>
            <a href="index.php">Kelola Staf</a>
            <i class="bi bi-chevron-right cs-5d7659"></i>
            <span>Edit Staf</span>
        </div>
    </div>

    <!-- Form Card -->
    <div class="card form-card animate-in delay-2">
        <div class="card-header">
            <i class="bi bi-person-vcard me-2 cs-00c66c"></i>
            Edit Profil Staf (ID: Staf-<?= $data['id_staf'] ?>)
        </div>
        <div class="card-body">

            <form method="POST" enctype="multipart/form-data">

                <!-- Current Photo Preview -->
                <div class="text-center mb-4">
                    <?php if (!empty($data['foto'])): ?>
                        <img src="../../image/<?= htmlspecialchars($data['foto']); ?>" class="foto-preview" alt="Foto Staf">
                    <?php else: ?>
                        <img src="../../image/default-user.png" class="foto-preview" alt="Default Foto">
                    <?php endif; ?>
                    <input type="hidden" name="foto_lama" value="<?= htmlspecialchars($data['foto']); ?>">
                </div>

                <!-- Personal Info Section -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">
                            <i class="bi bi-person"></i> Nama Lengkap Staf
                        </label>
                        <input type="text" name="nama_staf" class="form-control form-control-custom"
                               value="<?= htmlspecialchars($data['nama_staf']) ?>" placeholder="Masukkan nama staf" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">
                            <i class="bi bi-person-workspace"></i> Jabatan
                        </label>
                        <input type="text" name="jabatan" class="form-control form-control-custom"
                               value="<?= htmlspecialchars($data['jabatan']) ?>" placeholder="Contoh: Tour Guide" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">
                            <i class="bi bi-envelope"></i> Email Staf
                        </label>
                        <input type="email" name="email" class="form-control form-control-custom"
                               value="<?= htmlspecialchars($data['email']) ?>" placeholder="staf@email.com">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom">
                            <i class="bi bi-phone"></i> Nomor HP
                        </label>
                        <input type="text" name="no_hp" class="form-control form-control-custom"
                               value="<?= htmlspecialchars($data['no_hp']) ?>" placeholder="08xxxxxxxxxx">
                    </div>
                </div>

                <!-- Bio & Photo Section -->
                <div class="section-divider">
                    <div class="line"></div>
                    <span><i class="bi bi-image me-1"></i> Bio & Media Baru</span>
                    <div class="line"></div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label-custom">
                            <i class="bi bi-image-fill"></i> Ganti Foto Profil (Opsional)
                        </label>
                        <input type="file" name="foto" class="form-control form-control-custom" accept="image/*">
                        <div class="form-hint">Biarkan kosong jika tidak ingin memperbarui foto profil saat ini</div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label-custom">
                        <i class="bi bi-card-text"></i> Deskripsi Singkat / Keahlian
                    </label>
                    <textarea name="deskripsi" class="form-control form-control-custom" rows="4"
                              placeholder="Ceritakan singkat mengenai tanggung jawab atau biodata staf ini..."><?= htmlspecialchars($data['deskripsi']) ?></textarea>
                </div>

                <!-- Buttons -->
                <div class="d-flex gap-3 mt-4">
                    <button type="submit" name="update" class="btn-update">
                        <i class="bi bi-arrow-repeat"></i> Update Staf
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