<?php
/**
 * Admin - Daftar pesan/data kontak yang masuk dari pengunjung.
 */
// === KONEKSI DATABASE ===
// Memasukkan file koneksi untuk menghubungkan ke database
include "../../includes/koneksi.php";

// === QUERY: AMBIL DATA KONTAK ===
// Mengambil semua data pesan dari tabel kontak, diurutkan berdasarkan tanggal terbaru
$query = mysqli_query($koneksi, "SELECT * FROM kontak ORDER BY tanggal DESC");

// Menghitung total jumlah pesan masuk
$totalKontak = mysqli_num_rows($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Kontak - NusantaraWild</title>

    <!-- Memuat Bootstrap CSS dan Ikon -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Memuat Google Fonts (Inter) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styling Kustom -->
    <link rel="stylesheet" href="../../style/css/style.css">
</head>
<body class="admin-page">

<div class="container">

    <!-- Header Halaman -->
    <div class="admin-page-header animate-in delay-1">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2><i class="bi bi-envelope-paper-fill me-2"></i>Pesan Kontak</h2>
                <p>Pantau dan kelola seluruh pesan masuk dari pengunjung web NusantaraWild</p>
            </div>
            <!-- Statistik Jumlah Pesan -->
            <div class="stat-badge">
                <i class="bi bi-chat-left-dots me-1"></i>
                <?= $totalKontak ?> Pesan Masuk
            </div>
        </div>
    </div>

    <!-- Toolbar Atas: Tombol Tambah Pesan -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4 animate-in delay-2">
        <div></div> <!-- Spacer -->
        <a href="tambah.php" class="btn-tambah">
            <i class="bi bi-plus-circle"></i> Tambah Pesan Baru
        </a>
    </div>

    <!-- Tabel Data Pesan Masuk -->
    <div class="card section-card animate-in delay-3 mb-4">
        <div class="card-body p-0">

            <?php if ($totalKontak > 0): ?>
            <!-- Menampilkan tabel jika data pesan tersedia -->
            <div class="table-responsive">
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th class="text-center" width="50">No</th>
                            <th>Pengirim</th>
                            <th>Subjek & Kategori</th>
                            <th>Isi Pesan</th>
                            <th>Waktu Kirim</th>
                            <th class="text-center" width="130">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1; // Variabel untuk penomoran
                        // Melakukan perulangan untuk menampilkan setiap baris data
                        while ($data = mysqli_fetch_assoc($query)):
                            // Menentukan warna badge berdasarkan kategori pesan
                            $badgeClass = 'cat-default';
                            if ($data['kategori'] == "Informasi Destinasi") {
                                $badgeClass = "cat-info";
                            } elseif ($data['kategori'] == "Pemesanan & Tiket") {
                                $badgeClass = "cat-booking";
                            } elseif ($data['kategori'] == "Kemitraan") {
                                $badgeClass = "cat-kemitraan";
                            } elseif ($data['kategori'] == "Saran & Masukan") {
                                $badgeClass = "cat-saran";
                            }
                        ?>
                        <tr>
                            <!-- Kolom Nomor -->
                            <td class="text-center text-muted"><?= $no++; ?></td>
                            
                            <!-- Kolom Info Pengirim -->
                            <td>
                                <div class="fw-bold text-dark"><?= htmlspecialchars($data['nama']); ?></div>
                                <div class="text-muted mb-1 cs-345895">
                                    <i class="bi bi-envelope text-muted me-1"></i><?= htmlspecialchars($data['email']); ?>
                                </div>
                                <div class="text-muted cs-345895">
                                    <i class="bi bi-phone text-muted me-1"></i><?= htmlspecialchars($data['no_hp']); ?>
                                </div>
                            </td>

                            <!-- Kolom Subjek & Kategori -->
                            <td>
                                <div class="fw-semibold text-dark mb-1"><?= htmlspecialchars($data['subjek']); ?></div>
                                <span class="badge-category <?= $badgeClass ?>">
                                    <?= htmlspecialchars($data['kategori']); ?>
                                </span>
                            </td>

                            <!-- Kolom Isi Pesan -->
                            <td class="text-muted cs-91679c">
                                <?= nl2br(htmlspecialchars($data['pesan'])); ?>
                            </td>

                            <!-- Kolom Waktu -->
                            <td>
                                <div class="fw-medium text-dark cs-766745">
                                    <?= date('d M Y', strtotime($data['tanggal'])); ?>
                                </div>
                                <div class="text-muted cs-345895">
                                    <?= date('H:i', strtotime($data['tanggal'])); ?> WIB
                                </div>
                            </td>

                            <!-- Kolom Aksi -->
                            <td class="text-center">
                                <!-- Tombol Edit -->
                                <a href="edit.php?id=<?= $data['id_kontak']; ?>" class="btn-action btn-edit me-1" title="Edit Data">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <!-- Tombol Hapus -->
                                <a href="hapus.php?id=<?= $data['id_kontak']; ?>" class="btn-action btn-hapus"
                                   onclick="return confirm('Yakin ingin menghapus pesan ini?')" title="Hapus Data">
                                    <i class="bi bi-trash3"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <!-- Menampilkan Empty State jika tidak ada data -->
            <div class="empty-state">
                <i class="bi bi-envelope-open d-block"></i>
                <h5>Belum ada pesan masuk</h5>
                <p>Kotak masuk Anda kosong untuk saat ini.</p>
            </div>
            <?php endif; ?>

        </div>
    </div>

    <!-- Tombol Kembali ke Dashboard -->
    <div class="d-flex justify-content-start animate-in delay-3">
        <a href="../dashboard.php" class="btn-kembali">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

</div>

</body>
</html>