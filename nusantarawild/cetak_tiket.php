<?php
/**
 * Halaman cetak/tampilan tiket booking yang sudah berhasil dibuat.
 */
session_start();
include 'includes/koneksi.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$id_booking = isset($_GET['id']) ? mysqli_real_escape_string($koneksi, $_GET['id']) : '';
$id_user = $_SESSION['user']['id_user'];

// Query untuk mengambil 1 order booking detail spesifik miliki user tersebut
$query = mysqli_query($koneksi, "
    SELECT b.*, d.nama AS nama_destinasi, d.lokasi, d.harga AS harga_tiket, u.nama AS nama_user, u.email AS email_user
    FROM booking b
    JOIN destinasi d ON b.id_destinasi = d.id
    JOIN users u ON b.id_user = u.id
    WHERE b.id_booking = '$id_booking' AND b.id_user = '$id_user'
    LIMIT 1
");

if (mysqli_num_rows($query) == 0) {
    exit("Data booking tidak ditemukan atau Anda tidak memiliki akses ke tiket ini.");
}

$data = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>E-Tiket NusantaraWild - <?= htmlspecialchars($data['kode_booking']) ?></title>
</head>
<body class="ticket-print">

    <div class="ticket-box">
        <div class="header">
            <h1>NusantaraWild</h1>
            <p>E-Ticket Resmi Perjalanan Wisata Alam Indonesia</p>
            <div class="ticket-code"><?= htmlspecialchars($data['kode_booking']) ?></div>
        </div>

        <table class="content-table">
            <tr>
                <td class="label">Nama Pemesan</td>
                <td class="value">: <?= htmlspecialchars($data['nama_pemesan']) ?></td>
            </tr>
            <tr>
                <td class="label">Email Terdaftar</td>
                <td class="value">: <?= htmlspecialchars($data['email']) ?></td>
            </tr>
            <tr>
                <td class="label">Destinasi Wisata</td>
                <td class="value">: <strong><?= htmlspecialchars($data['nama_destinasi']) ?></strong></td>
            </tr>
            <tr>
                <td class="label">Lokasi</td>
                <td class="value">: <?= htmlspecialchars($data['lokasi']) ?></td>
            </tr>
            <tr>
                <td class="label">Tanggal Kunjungan</td>
                <td class="value">: <?= date('d F Y', strtotime($data['tanggal_kunjungan'])) ?></td>
            </tr>
            <tr>
                <td class="label">Jumlah Orang</td>
                <td class="value">: <?= $data['jumlah_orang'] ?> Wisatawan</td>
            </tr>
            <tr>
                <td class="label">Metode Pembayaran</td>
                <td class="value">: <?= htmlspecialchars($data['metode_pembayaran']) ?></td>
            </tr>
            <tr>
                <td class="label">Status Tiket</td>
                <td class="value">: 
                    <?php
                    $statusClass = 'badge-warning';
                    switch ($data['status']) {
                        case 'Menunggu':
                            $statusClass = 'badge-warning';
                            break;
                        case 'Dikonfirmasi':
                            $statusClass = 'badge-primary';
                            break;
                        case 'Selesai':
                            $statusClass = 'badge-success';
                            break;
                        case 'Dibatalkan':
                            $statusClass = 'badge-danger';
                            break;
                    }
                    ?>
                    <span class="badge <?= $statusClass ?>"><?= $data['status'] ?></span>
                </td>
            </tr>
            <?php if (!empty($data['catatan'])): ?>
            <tr>
                <td class="label">Catatan Tambahan</td>
                <td class="value">: <?= nl2br(htmlspecialchars($data['catatan'])) ?></td>
            </tr>
            <?php endif; ?>
        </table>

        <div class="total-box">
            <small class="cs-ff93da">TOTAL PEMBAYARAN</small>
            <h3>Rp <?= number_format($data['total_harga'], 0, ',', '.') ?></h3>
        </div>

        <div class="footer-note">
            Tunjukkan E-Ticket ini (dalam bentuk cetak atau dari layar ponsel) kepada petugas tiket di lokasi masuk wisata NusantaraWild untuk melakukan verifikasi.<br>
            <strong>Terima kasih atas kunjungan Anda dan semoga perjalanan Anda menyenangkan!</strong>
        </div>
    </div>

    <!-- Script auto-print to PDF/Printer -->
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            window.print();
        });
    </script>
</body>
</html>
