<?php
/**
 * Halaman riwayat booking milik user yang sedang login.
 */
// Memulai sesi untuk mengecek apakah user sudah login atau belum
session_start();

// Menghubungkan ke file koneksi database
include 'includes/koneksi.php';

// Jika tidak ada sesi 'user' (user belum login), maka arahkan ke halaman login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Mengambil ID user dari sesi yang sedang login
$id_user = $_SESSION['user']['id_user'];

// Melakukan query ke database untuk mengambil riwayat booking milik user yang sedang login
// Menggabungkan tabel booking dengan tabel destinasi untuk mendapatkan nama destinasi dan foto
$query = mysqli_query($koneksi,"
    SELECT b.*, d.nama AS nama_destinasi, d.foto
    FROM booking b
    JOIN destinasi d ON b.id_destinasi = d.id
    WHERE b.id_user='$id_user'
    ORDER BY b.id_booking DESC
"); 

// Fungsi untuk memformat angka menjadi format mata uang Rupiah
function formatRupiah($angka)
{
    return "Rp " . number_format($angka, 0, ',', '.');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Booking - NusantaraWild</title>
    <!-- Memuat file CSS dari Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Memuat custom CSS -->
    <link rel="stylesheet" href="style/css/style.css">
</head>
<body>
    <!-- Menampilkan navigasi bar -->
    <?php include 'includes/navbar.php'; ?>

    <!-- Bagian header halaman Riwayat Booking -->
    <div class="page-header bg-success text-white py-5">
        <div class="container text-center">
            <h2 class="fw-bold">
                <i class="bi bi-receipt-cutoff me-2"></i>
                Riwayat Booking
            </h2>
            <p class="mb-0">Lihat status seluruh booking Anda</p>
        </div>
    </div>

    <!-- Container utama untuk daftar booking -->
    <div class="container my-5">
        <div class="card shadow border-0">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="bi bi-clock-history me-2"></i>
                    Daftar Booking
                </h5>
            </div>

            <div class="card-body p-0">
                <!-- Mengecek apakah user memiliki data booking -->
                <?php if (mysqli_num_rows($query) > 0): ?>
                    <div class="table-responsive">
                        <!-- Tabel untuk menampilkan detail booking -->
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" width="50">No</th>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Lokasi</th>
                                    <th>Destinasi</th>
                                    <th>Tanggal</th>
                                    <th>Orang</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th class="text-center" width="120">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1; // Variabel untuk nomor urut
                                // Melakukan perulangan untuk setiap baris data booking yang ditemukan
                                while ($row = mysqli_fetch_assoc($query)): ?>
                                    <tr>
                                        <!-- Nomor Urut -->
                                        <td class="text-center"><?= $no++ ?></td>
                                        
                                        <!-- Kode Booking -->
                                        <td>
                                            <strong><?= htmlspecialchars($row['kode_booking']) ?></strong>
                                        </td>
                                        
                                        <!-- Nama Pemesan (jika kosong, gunakan nama dari sesi user) -->
                                        <td>
                                            <?= htmlspecialchars($row['nama_pemesan'] ?? $_SESSION['user']['nama']) ?>
                                        </td>
                                        
                                        <!-- Lokasi -->
                                        <td><?= htmlspecialchars($row['lokasi']) ?></td>
                                        
                                        <!-- Nama Destinasi -->
                                        <td><?= htmlspecialchars($row['nama_destinasi']) ?></td>
                                        
                                        <!-- Tanggal Kunjungan diformat misal: 12 Agu 2024 -->
                                        <td><?= date('d M Y', strtotime($row['tanggal_kunjungan'])) ?></td>
                                        
                                        <!-- Jumlah Orang -->
                                        <td><?= $row['jumlah_orang'] ?> Orang</td>
                                        
                                        <!-- Total Harga menggunakan fungsi formatRupiah yang dibuat di atas -->
                                        <td><?= formatRupiah($row['total_harga']) ?></td>
                                        
                                        <!-- Status Booking (menampilkan warna badge yang berbeda sesuai status) -->
                                        <td>
                                            <?php
                                            switch ($row['status']) {
                                                case 'Menunggu':
                                                    echo '<span class="badge bg-warning text-dark">Menunggu</span>';
                                                    break;
                                                case 'Dikonfirmasi':
                                                    echo '<span class="badge bg-primary">Dikonfirmasi</span>';
                                                    break;
                                                case 'Selesai':
                                                    echo '<span class="badge bg-success">Selesai</span>';
                                                    break;
                                                case 'Dibatalkan':
                                                    echo '<span class="badge bg-danger">Dibatalkan</span>';
                                                    break;
                                                default:
                                                    echo '<span class="badge bg-secondary">' . htmlspecialchars($row['status']) . '</span>';
                                            }
                                            ?>
                                        </td>
                                        
                                        <!-- Aksi Cetak Tiket (PDF) -->
                                        <td class="text-center">
                                            <a href="cetak_tiket.php?id=<?= $row['id_booking'] ?>" class="btn btn-warning btn-sm" target="_blank" title="Cetak PDF">
                                                <i class="bi bi-file-earmark-pdf-fill"></i> Cetak PDF
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <!-- Tampilan jika user belum pernah melakukan booking -->
                    <div class="text-center py-5">
                        <i class="bi bi-inbox display-1 text-muted"></i>
                        <h4 class="mt-3">Belum ada booking</h4>
                        <p class="text-muted">Silakan lakukan booking destinasi wisata terlebih dahulu.</p>
                        <a href="destinasi.php" class="btn btn-success">
                            <i class="bi bi-compass me-2"></i>
                            Jelajahi Destinasi
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Menampilkan footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- Memuat file JS dari Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>