<?php
/**
 * Admin - Daftar data staf/tim.
 */
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../../login.php");
    exit;
}

include "../../includes/koneksi.php";

$query = mysqli_query($koneksi, "
    SELECT *
    FROM staf
    ORDER BY id_staf DESC
");

$totalStaf = mysqli_num_rows($query);
$user = $_SESSION['user'];
$nama_user = $user['nama'] ?? 'Admin';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Staf - NusantaraWild</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../../style/css/style.css">
</head>
<body class="admin-page">

<!-- Main Content Container (No Sidebar) -->
<div class="container">

    <!-- Page Header -->
    <div class="admin-page-header animate-in delay-1">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2><i class="bi bi-person-badge-fill me-2"></i>Kelola Staf</h2>
                <p>Kelola dan atur tim staf pelaksana lapangan NusantaraWild</p>
            </div>
            <div class="stat-badge">
                <i class="bi bi-people me-1"></i>
                <?= $totalStaf ?> Anggota Staf
            </div>
        </div>
    </div>

    <!-- Toolbar -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4 animate-in delay-2">
        <div></div> <!-- Spacer -->
        <!-- Add Button -->
        <a href="tambah.php" class="btn-tambah">
            <i class="bi bi-plus-circle"></i> Tambah Anggota Staf
        </a>
    </div>

    <!-- Table Card -->
    <div class="card section-card animate-in delay-3 mb-4">
        <div class="card-body p-0">

            <?php if ($totalStaf > 0): ?>
            <div class="table-responsive">
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th class="text-center" width="60">No</th>
                            <th width="80">Foto</th>
                            <th>Nama Staf</th>
                            <th>Jabatan</th>
                            <th>Kontak</th>
                            <th>Keterangan / Deskripsi</th>
                            <th class="text-center" width="180">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($query)):
                        ?>
                        <tr>
                            <td class="text-center text-muted"><?= $no++; ?></td>
                            <td>
                                <?php if (!empty($row['foto'])): ?>
                                    <img src="../../image/<?= htmlspecialchars($row['foto']); ?>"
                                         alt="<?= htmlspecialchars($row['nama_staf']); ?>"
                                         class="avatar-staf">
                                <?php else: ?>
                                    <img src="../../image/default-user.png"
                                         alt="Default User"
                                         class="avatar-staf">
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-bold text-dark"><?= htmlspecialchars($row['nama_staf']) ?></div>
                                <div class="text-muted cs-6ec708">ID: Staf-<?= $row['id_staf'] ?></div>
                            </td>
                            <td>
                                <span class="badge-position"><?= htmlspecialchars($row['jabatan']) ?></span>
                            </td>
                            <td>
                                <div class="mb-1 cs-766745">
                                    <i class="bi bi-envelope text-muted me-1"></i><?= htmlspecialchars($row['email'] ?: '-') ?>
                                </div>
                                <div class="cs-766745">
                                    <i class="bi bi-phone text-muted me-1"></i><?= htmlspecialchars($row['no_hp'] ?: '-') ?>
                                </div>
                            </td>
                            <td class="text-muted cs-8e6a45">
                                <?= htmlspecialchars($row['deskripsi'] ?: '-') ?>
                            </td>
                            <td class="text-center">
                                <a href="edit.php?id=<?= $row['id_staf']; ?>" class="btn-action btn-edit me-1">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <a href="hapus.php?id=<?= $row['id_staf']; ?>"
                                   onclick="return confirm('Yakin ingin menghapus staf ini?')"
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
                <i class="bi bi-people d-block"></i>
                <h5>Belum ada data staf</h5>
                <p>Klik tombol "Tambah Anggota Staf" untuk menambahkan anggota pertama.</p>
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