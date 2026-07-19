<?php
/**
 * Admin - Daftar semua data destinasi wisata.
 */
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../../login.php");
    exit;
}

include "../../includes/koneksi.php";

$cari = "";

if (isset($_GET['cari'])) {
    $cari = mysqli_real_escape_string($koneksi, $_GET['cari']);
    $query = mysqli_query(
        $koneksi,
        "SELECT * FROM destinasi
         WHERE nama LIKE '%$cari%'
         OR lokasi LIKE '%$cari%'
         OR kategori LIKE '%$cari%'
         ORDER BY id DESC"
    );
} else {
    $query = mysqli_query($koneksi, "SELECT * FROM destinasi ORDER BY id DESC");
}

$totalDestinasi = mysqli_num_rows($query);
$user = $_SESSION['user'];
$nama_user = $user['nama'] ?? 'Admin';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Destinasi - NusantaraWild</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../../style/css/style.css">
</head>
<body class="admin-page">

<!-- Main Content (No Sidebar) -->
<div class="container">

    <!-- Page Header -->
    <div class="admin-page-header animate-in delay-1">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2><i class="bi bi-geo-alt-fill me-2"></i>Kelola Destinasi</h2>
                <p>Kelola semua lokasi dan informasi objek wisata NusantaraWild</p>
            </div>
            <div class="stat-badge">
                <i class="bi bi-map me-1"></i>
                <?= $totalDestinasi ?> Destinasi Wisata
            </div>
        </div>
    </div>

    <!-- Toolbar -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4 animate-in delay-2">

        <!-- Search -->
        <form method="GET" class="search-box cs-775f67">
            <i class="bi bi-search search-icon"></i>
            <input
                type="text"
                name="cari"
                class="form-control"
                placeholder="Cari destinasi atau lokasi..."
                value="<?= htmlspecialchars($cari) ?>"
            >
        </form>

        <!-- Add Button -->
        <a href="tambah.php" class="btn-tambah">
            <i class="bi bi-plus-circle"></i> Tambah Destinasi Baru
        </a>

    </div>

    <!-- Table Card -->
    <div class="card section-card animate-in delay-3 mb-4">
        <div class="card-body p-0">

            <?php if ($totalDestinasi > 0): ?>
            <div class="table-responsive">
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th width="100">Foto</th>
                            <th>Nama Destinasi</th>
                            <th>Kategori & Rating</th>
                            <th>Lokasi</th>
                            <th>Harga Tiket</th>
                            <th>Waktu Terbaik</th>
                            <th class="text-center" width="200">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($query)): ?>
                        <tr>
                            <td>
                                <?php if (!empty($row['foto'])): ?>
                                    <img src="../../image/<?= htmlspecialchars($row['foto']); ?>"
                                         alt="<?= htmlspecialchars($row['nama']); ?>"
                                         width="80"
                                         height="60"
                                         class="cs-d65ed9">
                                <?php else: ?>
                                    <div class="bg-light d-flex align-items-center justify-content-center cs-117bb0">
                                        <i class="bi bi-image text-muted cs-af18ea"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-bold text-dark"><?= htmlspecialchars($row['nama']) ?></div>
                                <div class="text-muted cs-22b7e0">
                                    <?= htmlspecialchars($row['deskripsi']) ?>
                                </div>
                            </td>
                            <td>
                                <div class="mb-1">
                                    <span class="badge-category"><?= htmlspecialchars($row['kategori'] ?? 'Wisata') ?></span>
                                </div>
                                <div class="rating-star">
                                    <i class="bi bi-star-fill"></i>
                                    <span><?= htmlspecialchars($row['rating'] ?? '5.0') ?></span>
                                </div>
                            </td>
                            <td>
                                <i class="bi bi-geo-alt text-danger me-1 cs-766745"></i>
                                <?= htmlspecialchars($row['lokasi']) ?>
                            </td>
                            <td class="fw-semibold text-success">
                                Rp <?= number_format($row['harga'], 0, ',', '.'); ?>
                            </td>
                            <td>
                                <i class="bi bi-calendar3 text-muted me-1 cs-766745"></i>
                                <?= htmlspecialchars($row['terbaik_dikunjungi'] ?: '-') ?>
                            </td>
                            <td class="text-center">
                                <a href="edit.php?id=<?= $row['id']; ?>" class="btn-action btn-edit me-1">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <a href="hapus.php?id=<?= $row['id']; ?>"
                                   onclick="return confirm('Yakin ingin menghapus destinasi ini?')"
                                   class="btn-action btn-hapus">
                                    <i class="bi bi-trash3"></i> Hapus
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="empty-state">
                <i class="bi bi-geo-alt d-block"></i>
                <h5>Belum ada destinasi wisata</h5>
                <p>Klik tombol "Tambah Destinasi Baru" untuk menambahkan destinasi pertama.</p>
            </div>
            <?php endif; ?>

        </div>
    </div>

    <!-- Back Button -->
    <div class="d-flex justify-content-start animate-in delay-3">
        <a href="../dashboard.php" class="btn-kembali">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

</div>

</body>
</html>