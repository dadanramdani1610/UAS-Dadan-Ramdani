<?php
/**
 * Admin - Daftar semua data booking beserta pencarian/filter.
 */
// === KONEKSI DATABASE ===
// Menyertakan file koneksi database
include "../../includes/koneksi.php";

// === FILTER STATUS BOOKING ===
// Memeriksa dan membersihkan parameter filter status dari URL (jika ada) untuk keamanan SQL Injection
$status_filter = isset($_GET['status']) ? mysqli_real_escape_string($koneksi, $_GET['status']) : '';

// === QUERY DASAR AMBIL DATA BOOKING ===
// Menghubungkan tabel booking dengan tabel destinasi untuk mengambil nama destinasi dan lokasinya
$sql = "SELECT booking.*, destinasi.nama AS destinasi, destinasi.lokasi 
        FROM booking 
        JOIN destinasi ON booking.id_destinasi = destinasi.id";

// === MENERAPKAN FILTER STATUS ===
// Jika filter status di URL tidak kosong, tambahkan klausa WHERE ke query
if (!empty($status_filter)) {
    $sql .= " WHERE booking.status = '$status_filter'";
}

// === PENGURUTAN DATA ===
// Mengurutkan data booking berdasarkan ID booking terbaru
$sql .= " ORDER BY booking.id_booking DESC";

// === EKSEKUSI QUERY ===
$query = mysqli_query($koneksi, $sql);

// === HITUNG TOTAL DATA HASIL QUERY ===
$totalBooking = mysqli_num_rows($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Booking - NusantaraWild</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../../style/css/style.css">
</head>
<body class="admin-page">

<div class="container-fluid px-md-5">

    <!-- Page Header -->
    <div class="admin-page-header animate-in delay-1">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2><i class="bi bi-ticket-perforated-fill me-2"></i>Data Booking</h2>
                <p>Kelola dan konfirmasi semua reservasi tiket wisata NusantaraWild</p>
            </div>
            <div class="stat-badge">
                <i class="bi bi-calendar-check me-1"></i>
                <?= $totalBooking ?> Booking Ditemukan
            </div>
        </div>
    </div>

    <!-- Toolbar (Filter & Actions) -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4 animate-in delay-2">
        
        <!-- Filter Tabs -->
        <div class="d-flex gap-2 flex-wrap">
            <a href="index.php" class="filter-tab <?= empty($status_filter) ? 'active' : '' ?>">Semua</a>
            <a href="index.php?status=Menunggu" class="filter-tab <?= $status_filter == 'Menunggu' ? 'active' : '' ?>">⌛ Menunggu</a>
            <a href="index.php?status=Dikonfirmasi" class="filter-tab <?= $status_filter == 'Dikonfirmasi' ? 'active' : '' ?>">🔵 Dikonfirmasi</a>
            <a href="index.php?status=Selesai" class="filter-tab <?= $status_filter == 'Selesai' ? 'active' : '' ?>">🟢 Selesai</a>
            <a href="index.php?status=Dibatalkan" class="filter-tab <?= $status_filter == 'Dibatalkan' ? 'active' : '' ?>">🔴 Dibatalkan</a>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex gap-2">
            <a href="export.php?status=<?= urlencode($status_filter) ?>" class="btn-pdf" target="_blank">
                <i class="bi bi-file-earmark-pdf"></i> Export PDF
            </a>
            <a href="tambah.php" class="btn-tambah">
                <i class="bi bi-plus-circle"></i> Tambah Booking
            </a>
        </div>

    </div>

    <!-- Table Card -->
    <div class="card section-card animate-in delay-3 mb-4">
        <div class="card-body p-0">

            <?php if ($totalBooking > 0): ?>
            <div class="table-responsive">
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th class="text-center" width="50">No</th>
                            <th>Kode</th>
                            <th>Pemesan</th>
                            <th>Destinasi & Lokasi</th>
                            <th>Kontak</th>
                            <th>Tanggal & Qty</th>
                            <th>Pembayaran</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th class="text-center" width="130">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        while ($data = mysqli_fetch_assoc($query)):
                            $statusClass = '';
                            switch ($data['status']) {
                                case 'Menunggu': $statusClass = 'status-menunggu'; break;
                                case 'Dikonfirmasi': $statusClass = 'status-dikonfirmasi'; break;
                                case 'Selesai': $statusClass = 'status-selesai'; break;
                                case 'Dibatalkan': $statusClass = 'status-dibatalkan'; break;
                            }
                        ?>
                        <tr>
                            <td class="text-center text-muted"><?= $no++; ?></td>
                            <td>
                                <?php if (!empty($data['kode_booking'])): ?>
                                    <span class="badge bg-success text-white px-2 py-1 cs-0c9d36">
                                        <?= htmlspecialchars($data['kode_booking']); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-bold text-dark"><?= htmlspecialchars($data['nama_pemesan']); ?></div>
                                <?php if(!empty($data['catatan'])): ?>
                                    <div class="text-muted text-truncate cs-324071" title="<?= htmlspecialchars($data['catatan']); ?>">
                                        <i class="bi bi-chat-text me-1"></i><?= htmlspecialchars($data['catatan']); ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark"><?= htmlspecialchars($data['destinasi']); ?></div>
                                <div class="text-muted cs-345895">
                                    <i class="bi bi-geo-alt-fill text-danger me-1"></i><?= htmlspecialchars($data['lokasi']); ?>
                                </div>
                            </td>
                            <td>
                                <div class="cs-766745">
                                    <i class="bi bi-envelope text-muted me-1"></i><?= htmlspecialchars($data['email']); ?>
                                </div>
                            </td>
                            <td>
                                <div class="fw-medium"><?= date('d M Y', strtotime($data['tanggal_kunjungan'])); ?></div>
                                <div class="text-muted cs-345895">
                                    <i class="bi bi-people me-1"></i><?= htmlspecialchars($data['jumlah_orang']); ?> Orang
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-primary border border-primary-subtle px-2 py-1 cs-345895">
                                    <?= htmlspecialchars($data['metode_pembayaran']); ?>
                                </span>
                            </td>
                            <td>
                                <strong class="text-success cs-499df8">
                                    Rp <?= number_format($data['total_harga'], 0, ',', '.'); ?>
                                </strong>
                            </td>
                            <td>
                                <span class="badge-status <?= $statusClass ?>">
                                    <?php 
                                        if ($data['status'] == 'Menunggu') echo '⌛ ' . $data['status'];
                                        else if ($data['status'] == 'Dikonfirmasi') echo '🔵 ' . $data['status'];
                                        else if ($data['status'] == 'Selesai') echo '🟢 ' . $data['status'];
                                        else if ($data['status'] == 'Dibatalkan') echo '🔴 ' . $data['status'];
                                    ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="edit.php?id=<?= $data['id_booking']; ?>" class="btn-action btn-edit me-1">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="hapus.php?id=<?= $data['id_booking']; ?>" class="btn-action btn-hapus"
                                   onclick="return confirm('Yakin ingin menghapus booking ini?')">
                                    <i class="bi bi-trash3"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="empty-state">
                <i class="bi bi-ticket-perforated d-block"></i>
                <h5>Belum ada data booking</h5>
                <p>Tidak ada transaksi yang cocok dengan filter saat ini.</p>
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