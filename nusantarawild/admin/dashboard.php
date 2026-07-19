<?php
/**
 * Dashboard utama admin: menampilkan ringkasan statistik (total user, booking, destinasi, dll).
 */
// === MEMULAI SESSION ===
session_start();

// === VALIDASI LOGIN USER ===
// Memeriksa apakah session user sudah ada dan merupakan sebuah array
if (!isset($_SESSION['user']) || !is_array($_SESSION['user'])) {
    session_destroy();
    header("Location: ../login.php");
    exit;
}

// === VALIDASI ROLE ADMIN ===
// Hanya memperbolehkan user dengan role 'admin' untuk mengakses dashboard ini
if ($_SESSION['user']['role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

// === MEMBACA DATA USER DARI SESSION ===
$user = $_SESSION['user'];
$nama_user = $user['nama'] ?? 'Admin';

// === KONEKSI DATABASE ===
// Menyertakan file koneksi database
include "../includes/koneksi.php";

// === FUNGSI UTILITY: HITUNG TOTAL DATA ===
// Menghitung jumlah record dalam suatu tabel tertentu dengan penanganan error
function hitungTotal($koneksi, $tabel) {
    $result = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM $tabel");
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['total'] ?? 0;
    }
    return 0;
}

// === MEMBACA TOTAL DATA UNTUK STAT CARD ===
$totalUser      = hitungTotal($koneksi, 'users');      // Jumlah total user terdaftar
$totalDestinasi = hitungTotal($koneksi, 'destinasi');  // Jumlah total destinasi wisata
$totalBooking   = hitungTotal($koneksi, 'booking');    // Jumlah total transaksi booking
$totalStaf      = hitungTotal($koneksi, 'staf');       // Jumlah total anggota staf
$totalKontak    = hitungTotal($koneksi, 'kontak');     // Jumlah total pesan masuk

// === QUERY: 5 TRANSAKSI BOOKING TERBARU ===
// Mengambil data booking beserta nama destinasinya, diurutkan dari yang paling baru
$bookingTerbaru = mysqli_query($koneksi, "
    SELECT booking.*, destinasi.nama AS destinasi
    FROM booking
    JOIN destinasi ON booking.id_destinasi = destinasi.id
    ORDER BY booking.id_booking DESC
    LIMIT 5
");

// === QUERY: 5 PESAN KONTAK TERBARU ===
// Mengambil 5 pesan masuk terbaru dari pengunjung website
$pesanTerbaru = mysqli_query($koneksi, "
    SELECT * FROM kontak
    ORDER BY tanggal DESC
    LIMIT 5
");

// === FUNGSI UTILITY: HITUNG STATUS BOOKING ===
// Menghitung jumlah booking berdasarkan status tertentu (misal: Menunggu, Dikonfirmasi, Selesai, Dibatalkan)
function hitungStatusBooking($koneksi, $status) {
    $statusEscaped = mysqli_real_escape_string($koneksi, $status);
    $result = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM booking WHERE status = '$statusEscaped'");
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['total'] ?? 0;
    }
    return 0;
}

// === MEMBACA JUMLAH BOOKING TIAP STATUS ===
$countMenunggu     = hitungStatusBooking($koneksi, 'Menunggu');
$countDikonfirmasi = hitungStatusBooking($koneksi, 'Dikonfirmasi');
$countSelesai      = hitungStatusBooking($koneksi, 'Selesai');
$countDibatalkan   = hitungStatusBooking($koneksi, 'Dibatalkan');

// === AKUMULASI TOTAL STATUS BOOKING ===
// Digunakan sebagai pembagi persentase pada diagram status booking
$totalBookingStatus = $countMenunggu + $countDikonfirmasi + $countSelesai + $countDibatalkan;

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - NusantaraWild</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="stylesheet" href="../style/css/style.css">
</head>
<body class="admin-page">

<div class="container-fluid">
    <div class="row">

        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 admin-sidebar-col p-3">

            <h3 class="text-white mb-4">
                <i class="bi bi-compass"></i>
                NusantaraWild
            </h3>

            <a href="dashboard.php">
                <i class="bi bi-speedometer2"></i>
                Dashboard
            </a>

            <a href="users/index.php">
                <i class="bi bi-people"></i>
                Kelola Users
            </a>

            <a href="destinasi/index.php">
                <i class="bi bi-geo-alt"></i>
                Kelola Destinasi
            </a>

            <a href="booking/index.php">
                <i class="bi bi-calendar-check"></i>
                Kelola Booking
            </a>

            <a href="staf/index.php">
                <i class="bi bi-person-badge"></i>
                Kelola Staf
            </a>

            <a href="kontak/index.php">
                <i class="bi bi-envelope"></i>
                Kelola Kontak
            </a>

            <hr class="text-white">
            <hr class="text-white">

            <a href="../index.php" target="_blank">
                <i class="bi bi-globe"></i>
                Lihat Website
            </a>

            <a href="../logout.php">
                <i class="bi bi-box-arrow-right"></i>
                Logout
            </a>
          

        </div>

        <!-- Content -->
        <div class="col-md-9 col-lg-10 p-4">

            <!-- Dashboard Header -->
            <div class="dashboard-header mb-4 animate-in delay-1">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2><i class="bi bi-speedometer2 me-2"></i>Dashboard Admin</h2>
                        <p>
                            Selamat datang, <strong><?= htmlspecialchars($nama_user) ?></strong> 👋
                        </p>
                    </div>
                    <div class="date-badge d-none d-md-block">
                        <i class="bi bi-calendar3 me-1"></i>
                        <?= strftime('%A, %d %B %Y') ?: date('l, d F Y') ?>
                    </div>
                </div>
            </div>
             <!-- Stat Cards -->
            <div class="row g-3 mb-4">

                <div class="col-6 col-lg animate-in delay-2">
                    <a href="users/index.php" class="text-decoration-none">
                        <div class="card stat-card bg-gradient-blue shadow">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="stat-icon">
                                        <i class="bi bi-people-fill"></i>
                                    </div>
                                    <span class="text-white cs-e21515">
                                        <i class="bi bi-arrow-right-circle"></i>
                                    </span>
                                </div>
                                <div class="stat-number"><?= $totalUser ?></div>
                                <div class="stat-label">Total User</div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-6 col-lg animate-in delay-3">
                    <a href="destinasi/index.php" class="text-decoration-none">
                        <div class="card stat-card bg-gradient-green shadow">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="stat-icon">
                                        <i class="bi bi-geo-alt-fill"></i>
                                    </div>
                                    <span class="text-white cs-e21515">
                                        <i class="bi bi-arrow-right-circle"></i>
                                    </span>
                                </div>
                                <div class="stat-number"><?= $totalDestinasi ?></div>
                                <div class="stat-label">Total Destinasi</div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-6 col-lg animate-in delay-4">
                    <a href="booking/index.php" class="text-decoration-none">
                        <div class="card stat-card bg-gradient-orange shadow">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="stat-icon">
                                        <i class="bi bi-ticket-perforated-fill"></i>
                                    </div>
                                    <span class="text-white cs-e21515">
                                        <i class="bi bi-arrow-right-circle"></i>
                                    </span>
                                </div>
                                <div class="stat-number"><?= $totalBooking ?></div>
                                <div class="stat-label">Total Booking</div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-6 col-lg animate-in delay-5">
                    <a href="staf/index.php" class="text-decoration-none">
                        <div class="card stat-card bg-gradient-teal shadow">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="stat-icon">
                                        <i class="bi bi-person-badge-fill"></i>
                                    </div>
                                    <span class="text-white cs-e21515">
                                        <i class="bi bi-arrow-right-circle"></i>
                                    </span>
                                </div>
                                <div class="stat-number"><?= $totalStaf ?></div>
                                <div class="stat-label">Total Staff</div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-6 col-lg animate-in delay-6">
                    <a href="kontak/index.php" class="text-decoration-none">
                        <div class="card stat-card bg-gradient-purple shadow">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="stat-icon">
                                        <i class="bi bi-envelope-fill"></i>
                                    </div>
                                    <span class="text-white cs-e21515">
                                        <i class="bi bi-arrow-right-circle"></i>
                                    </span>
                                </div>
                                <div class="stat-number"><?= $totalKontak ?></div>
                                <div class="stat-label">Total Kontak</div>
                            </div>
                        </div>
                    </a>
                </div>

            </div>

            <!-- Tables Row -->
            <div class="row g-4 animate-in delay-7">

                <!-- Booking Terbaru -->
                <div class="col-lg-7">
                    <div class="card section-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>
                                <i class="bi bi-ticket-perforated"></i>
                                Booking Terbaru
                            </span>
                            <a href="booking/index.php" class="view-all">
                                Lihat Semua <i class="bi bi-chevron-right"></i>
                            </a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-modern">
                                    <thead>
                                        <tr>
                                            <th>Kode</th>
                                            <th>Nama Pemesan</th>
                                            <th>Destinasi</th>
                                            <th>Tanggal</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($bookingTerbaru && mysqli_num_rows($bookingTerbaru) > 0): ?>
                                            <?php while ($b = mysqli_fetch_assoc($bookingTerbaru)): ?>
                                                <tr>
                                                    <td>
                                                        <?php if (!empty($b['kode_booking'])): ?>
                                                            <span class="badge bg-success bg-opacity-10 text-success cs-a5d2ba">
                                                                <?= htmlspecialchars($b['kode_booking']) ?>
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="text-muted">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <strong><?= htmlspecialchars($b['nama_pemesan']) ?></strong>
                                                    </td>
                                                    <td><?= htmlspecialchars($b['destinasi']) ?></td>
                                                    <td>
                                                        <small class="text-muted">
                                                            <?= date('d M Y', strtotime($b['tanggal_kunjungan'])) ?>
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <strong class="text-success">
                                                            Rp <?= number_format($b['total_harga'], 0, ',', '.') ?>
                                                        </strong>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $statusClass = 'secondary';
                                                        $statusIcon = 'clock';
                                                        switch ($b['status']) {
                                                            case 'Menunggu':
                                                                $statusClass = 'warning';
                                                                $statusIcon = 'clock-history';
                                                                break;
                                                            case 'Dikonfirmasi':
                                                                $statusClass = 'primary';
                                                                $statusIcon = 'check-circle';
                                                                break;
                                                            case 'Selesai':
                                                                $statusClass = 'success';
                                                                $statusIcon = 'check-circle-fill';
                                                                break;
                                                            case 'Dibatalkan':
                                                                $statusClass = 'danger';
                                                                $statusIcon = 'x-circle';
                                                                break;
                                                        }
                                                        ?>
                                                        <span class="badge badge-status bg-<?= $statusClass ?><?= $statusClass == 'warning' ? ' text-dark' : '' ?>">
                                                            <i class="bi bi-<?= $statusIcon ?> me-1"></i><?= $b['status'] ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-4">
                                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                                    Belum ada data booking
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pesan Terbaru -->
                <div class="col-lg-5">
                    <div class="card section-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>
                                <i class="bi bi-chat-dots"></i>
                                Pesan Terbaru
                            </span>
                            <a href="kontak/index.php" class="view-all">
                                Lihat Semua <i class="bi bi-chevron-right"></i>
                            </a>
                        </div>
                        <div class="card-body p-0">
                            <?php if (mysqli_num_rows($pesanTerbaru) > 0): ?>
                                <div class="list-group list-group-flush">
                                    <?php while ($p = mysqli_fetch_assoc($pesanTerbaru)): ?>
                                        <div class="list-group-item px-3 py-3 border-0 cs-665ae2">
                                            <div class="d-flex align-items-start">
                                                <div class="flex-shrink-0">
                                                    <div class="cs-076f9e">
                                                        <?= strtoupper(substr($p['nama'], 0, 1)) ?>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3 cs-ad5ca1">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <strong class="cs-6c3ca2"><?= htmlspecialchars($p['nama']) ?></strong>
                                                        <small class="text-muted cs-643a7c">
                                                            <?= date('d M', strtotime($p['tanggal'])) ?>
                                                        </small>
                                                    </div>
                                                    <div class="mb-1 cs-d25fb4">
                                                        <?= htmlspecialchars($p['subjek']) ?>
                                                    </div>
                                                    <p class="cs-e6a0f9">
                                                        <?= htmlspecialchars(substr($p['pesan'], 0, 80)) ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center text-muted py-4">
                                    <i class="bi bi-chat-square-text fs-3 d-block mb-2"></i>
                                    Belum ada pesan masuk
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
            </div>

            <!-- Status Booking Diagram Section (Excel-like Vertical Column Chart) -->
            <div class="row g-4 mt-2 animate-in delay-7">
                <div class="col-12">
                    <div class="card section-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>
                                <i class="bi bi-bar-chart-fill"></i>
                                Diagram Status Booking
                            </span>
                            <small class="text-muted">Klik pada batang diagram untuk memfilter data booking</small>
                        </div>
                        <div class="card-body p-4">
                            <?php
                            // Total per status
                            $statusData = [
                                'Menunggu' => [
                                    'count' => $countMenunggu,
                                    'color' => '#ffc107',
                                    'icon' => 'bi-clock-history'
                                ],
                                'Dikonfirmasi' => [
                                    'count' => $countDikonfirmasi,
                                    'color' => '#0d6efd',
                                    'icon' => 'bi-check-circle'
                                ],
                                'Selesai' => [
                                    'count' => $countSelesai,
                                    'color' => '#198754',
                                    'icon' => 'bi-check-circle-fill'
                                ],
                                'Dibatalkan' => [
                                    'count' => $countDibatalkan,
                                    'color' => '#dc3545',
                                    'icon' => 'bi-x-circle'
                                ]
                            ];

                            // Menghitung tinggi maksimum untuk rasio chart (skala tinggi diagram)
                            $maxVal = max($countMenunggu, $countDikonfirmasi, $countSelesai, $countDibatalkan);
                            if ($maxVal == 0) $maxVal = 1; // Menghindari divisi dengan nol
                            ?>

                            <div class="chart-container d-flex align-items-end justify-content-around border-bottom border-secondary-subtle py-4 cs-103e50">
                                
                                <!-- Garis Bantu Horizontal (Y-Axis Gridlines) -->
                                <div class="w-100 position-absolute d-flex flex-column justify-content-between cs-81f8e1">
                                    <div class="border-top border-secondary-subtle border-opacity-25 w-100 text-end pe-2"><small class="text-muted cs-5d7659"><?= $maxVal ?></small></div>
                                    <div class="border-top border-secondary-subtle border-opacity-25 w-100 text-end pe-2"><small class="text-muted cs-5d7659"><?= round($maxVal * 0.75) ?></small></div>
                                    <div class="border-top border-secondary-subtle border-opacity-25 w-100 text-end pe-2"><small class="text-muted cs-5d7659"><?= round($maxVal * 0.5) ?></small></div>
                                    <div class="border-top border-secondary-subtle border-opacity-25 w-100 text-end pe-2"><small class="text-muted cs-5d7659"><?= round($maxVal * 0.25) ?></small></div>
                                    <div class="w-100 text-end pe-2"><small class="text-muted cs-5d7659">0</small></div>
                                </div>

                                <?php
                                // Catatan: beberapa atribut style="" di bawah ini SENGAJA tetap inline
                                // (bukan class CSS) karena nilainya (tinggi batang & warna) dihitung
                                // dinamis per status booking dan baru diketahui saat halaman dijalankan.
                                foreach ($statusData as $label => $data): 
                                    // Tinggi batang proporsional terhadap nilai maksimum (maksimal 250px tinggi batang)
                                    $barHeight = ($data['count'] / $maxVal) * 250;
                                    // Jika 0, set tinggi minimal 5px agar batangnya tetap kelihatan sedikit di dasar chart
                                    if ($barHeight == 0) $barHeight = 5;
                                ?>
                                    <div class="chart-column text-center position-relative d-flex flex-column align-items-center cs-217a90">
                                        
                                        <!-- Nilai di atas batang -->
                                        <div class="fw-bold mb-2 status-value-label animate-in delay-2" style="font-size: 14px; color: <?= $data['color'] ?>;">
                                            <?= $data['count'] ?>
                                        </div>
                                        
                                        <!-- Batang diagram (Clickable) -->
                                        <a href="booking/index.php?status=<?= urlencode($label) ?>" 
                                           class="chart-bar-link w-100" 
                                           title="Klik untuk memfilter status: <?= htmlspecialchars($label) ?>">
                                            <div class="chart-bar rounded-top" 
                                                 style="height: <?= $barHeight ?>px; background-color: <?= $data['color'] ?>; transition: all 0.3s ease;">
                                                <!-- Gradasi mengkilap di dalam batang -->
                                                <div class="chart-bar-shimmer"></div>
                                            </div>
                                        </a>

                                        <!-- Label Sumbu X di bawah batang -->
                                        <div class="mt-3 text-secondary cs-515f07">
                                            <i class="bi <?= $data['icon'] ?> me-1" style="color: <?= $data['color'] ?>;"></i>
                                            <span class="d-none d-md-inline"><?= htmlspecialchars($label) ?></span>
                                        </div>
                                        
                                    </div>
                                <?php endforeach; ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>

    </div>
</div>


</body>
</html>
