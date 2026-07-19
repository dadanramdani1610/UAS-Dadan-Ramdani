<?php
/**
 * Admin - Daftar semua user terdaftar.
 */
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../../login.php");
    exit;
}

include "../../includes/koneksi.php";

$cari = "";

if(isset($_GET['cari'])){
    $cari = mysqli_real_escape_string($koneksi, $_GET['cari']);

    $query = mysqli_query(
        $koneksi,
        "SELECT * FROM users
         WHERE nama LIKE '%$cari%'
         OR email LIKE '%$cari%'
         ORDER BY id DESC"
    );
}else{
    $query = mysqli_query(
        $koneksi,
        "SELECT * FROM users ORDER BY id DESC"
    );
}

$totalUsers = mysqli_num_rows($query);
$user = $_SESSION['user'];
$nama_user = $user['nama'] ?? 'Admin';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Users - NusantaraWild</title>

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
                <h2><i class="bi bi-people-fill me-2"></i>Kelola Users</h2>
                <p>Kelola semua data pengguna sistem NusantaraWild</p>
            </div>
            <div class="stat-badge">
                <i class="bi bi-person-check me-1"></i>
                <?= $totalUsers ?> User Terdaftar
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
                placeholder="Cari nama atau email user..."
                value="<?= htmlspecialchars($cari) ?>"
            >
        </form>

        <!-- Action Buttons -->
        <div class="d-flex gap-2">
            <a href="cetak_pdf.php<?= !empty($cari) ? '?cari=' . urlencode($cari) : '' ?>" target="_blank" class="btn-tambah" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                <i class="bi bi-file-earmark-pdf"></i> Cetak PDF
            </a>
            <a href="tambah.php" class="btn-tambah">
                <i class="bi bi-plus-circle"></i> Tambah User Baru
            </a>
        </div>

    </div>

    <!-- Table Card -->
    <div class="card section-card animate-in delay-3 mb-4">
        <div class="card-body p-0">

            <?php if($totalUsers > 0): ?>
            <div class="table-responsive">
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>No HP</th>
                            <th>Provinsi</th>
                            <th>Role</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $colors = ['#667eea','#11998e','#f5576c','#4facfe','#a18cd1','#f093fb','#38ef7d','#f6d365'];
                        $i = 0;
                        ?>
                        <?php while($row = mysqli_fetch_assoc($query)): ?>
                        <?php
                            $initials = strtoupper(substr($row['nama'], 0, 1));
                            $color = $colors[$i % count($colors)];
                            $i++;
                        ?>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <!-- style inline sengaja dipakai: warna avatar dihasilkan acak/dinamis per user dari PHP -->
                                    <div class="user-avatar" style="background: <?= $color ?>;">
                                        <?= $initials ?>
                                    </div>
                                    <div>
                                        <div class="name"><?= htmlspecialchars($row['nama']) ?></div>
                                        <div class="email-sub">ID: <?= $row['id'] ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <i class="bi bi-envelope me-1 cs-aa3f0a"></i>
                                <?= htmlspecialchars($row['email']) ?>
                            </td>
                            <td>
                                <i class="bi bi-phone me-1 cs-aa3f0a"></i>
                                <?= htmlspecialchars($row['no_hp']) ?>
                            </td>
                            <td>
                                <i class="bi bi-geo-alt me-1 cs-aa3f0a"></i>
                                <?= htmlspecialchars($row['provinsi']) ?>
                            </td>
                            <td>
                                <span class="badge-role <?= ($row['role'] == 'admin') ? 'badge-admin' : 'badge-user' ?>">
                                    <?= ($row['role'] == 'admin') ? '🛡️ Admin' : '👤 User' ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="edit.php?id=<?= $row['id']; ?>" class="btn-action btn-edit me-1">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <a href="hapus.php?id=<?= $row['id']; ?>"
                                   onclick="return confirm('Yakin ingin menghapus user ini?')"
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
                <h5>Belum ada data user</h5>
                <p>Klik tombol "Tambah User Baru" untuk menambahkan user pertama.</p>
            </div>
            <?php endif; ?>

        </div>
    </div>

    <!-- Back Button at the Bottom -->
    <div class="d-flex justify-content-start animate-in delay-3">
        <a href="../dashboard.php" class="btn-kembali">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

</div>

</body>
</html>