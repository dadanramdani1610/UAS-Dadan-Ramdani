<?php
/**
 * Admin - Export data booking (misalnya ke Excel/CSV/print).
 */
session_start();

if (!isset($_SESSION['user']) || !is_array($_SESSION['user'])) {
    session_destroy();
    header("Location: ../../login.php");
    exit;
}

if ($_SESSION['user']['role'] != 'admin') {
    header("Location: ../../index.php");
    exit;
}

include "../../includes/koneksi.php";

$status_filter = isset($_GET['status']) ? mysqli_real_escape_string($koneksi, $_GET['status']) : '';

$sql = "SELECT booking.*, destinasi.nama AS destinasi, destinasi.lokasi 
        FROM booking 
        JOIN destinasi ON booking.id_destinasi = destinasi.id";

if (!empty($status_filter)) {
    $sql .= " WHERE booking.status = '$status_filter'";
}

$sql .= " ORDER BY booking.id_booking DESC";
$query = mysqli_query($koneksi, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Booking</title>
    <link rel="stylesheet" href="../../style/css/style.css">
</head>
<body class="export-print">

    <div class="header">
        <h1>NusantaraWild</h1>
        <p>Laporan Resmi Data Transaksi & Booking Wisata Indonesia</p>
    </div>

    <table class="info-table">
        <tr>
            <td width="15%"><strong>Dicetak Oleh</strong></td>
            <td width="35%">: Admin NusantaraWild</td>
            <td width="20%"><strong>Tanggal Cetak</strong></td>
            <td width="30%">: <?= date('d F Y H:i:s') ?></td>
        </tr>
        <tr>
            <td><strong>Filter Status</strong></td>
            <td>: <?= !empty($status_filter) ? htmlspecialchars($status_filter) : 'Semua Status' ?></td>
            <td><strong>Jumlah Record</strong></td>
            <td>: <?= mysqli_num_rows($query) ?> baris</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th width="3%" class="text-center">No</th>
                <th width="10%">Kode</th>
                <th width="18%">Nama Pemesan</th>
                <th width="18%">Destinasi</th>
                <th width="15%">Lokasi</th>
                <th width="12%" class="text-center">Tanggal</th>
                <th width="5%" class="text-center">Org</th>
                <th width="12%" class="text-right">Total</th>
                <th width="10%" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            if (mysqli_num_rows($query) > 0) {
                while ($row = mysqli_fetch_assoc($query)) {
                    // Badge Styling
                    $badgeClass = '';
                    switch ($row['status']) {
                        case 'Menunggu':
                            $badgeClass = 'badge-waiting';
                            break;
                        case 'Dikonfirmasi':
                            $badgeClass = 'badge-confirmed';
                            break;
                        case 'Selesai':
                            $badgeClass = 'badge-success';
                            break;
                        case 'Dibatalkan':
                            $badgeClass = 'badge-danger';
                            break;
                    }
                    ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><strong><?= htmlspecialchars($row['kode_booking']) ?></strong></td>
                        <td><?= htmlspecialchars($row['nama_pemesan']) ?></td>
                        <td><?= htmlspecialchars($row['destinasi']) ?></td>
                        <td><?= htmlspecialchars($row['lokasi']) ?></td>
                        <td class="text-center"><?= date('d-m-Y', strtotime($row['tanggal_kunjungan'])) ?></td>
                        <td class="text-center"><?= $row['jumlah_orang'] ?></td>
                        <td class="text-right">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                        <td class="text-center">
                            <span class="badge <?= $badgeClass ?>"><?= $row['status'] ?></span>
                        </td>
                    </tr>
                    <?php 
                }
            } else {
                echo '<tr><td colspan="9" class="text-center">Tidak ada data booking ditemukan</td></tr>';
            }
            ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini dihasilkan secara otomatis oleh sistem NusantaraWild. Hak Cipta Dilindungi Undang-Undang.</p>
    </div>

    <!-- Script auto-print to PDF/Printer -->
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            window.print();
        });
    </script>
</body>
</html>
