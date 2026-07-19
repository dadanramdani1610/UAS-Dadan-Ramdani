<?php
/**
 * Cetak PDF - Laporan Data User NusantaraWild.
 * Halaman ini di-render HTML dengan CSS print-optimized, lalu auto-trigger window.print()
 * sehingga browser membuka dialog Save as PDF / Print.
 */
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../../login.php");
    exit;
}

include "../../includes/koneksi.php";

// Ambil parameter pencarian jika ada
$cari = "";
if (isset($_GET['cari'])) {
    $cari = mysqli_real_escape_string($koneksi, $_GET['cari']);
    $query = mysqli_query(
        $koneksi,
        "SELECT * FROM users
         WHERE nama LIKE '%$cari%'
         OR email LIKE '%$cari%'
         ORDER BY id DESC"
    );
} else {
    $query = mysqli_query(
        $koneksi,
        "SELECT * FROM users ORDER BY id DESC"
    );
}

$totalUsers = mysqli_num_rows($query);
$user = $_SESSION['user'];
$nama_admin = $user['nama'] ?? 'Admin';
$tanggal_cetak = date('d F Y');
$waktu_cetak = date('H:i:s');

// Hitung statistik
$queryStats = mysqli_query($koneksi, "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN role = 'admin' THEN 1 ELSE 0 END) as total_admin,
    SUM(CASE WHEN role = 'user' THEN 1 ELSE 0 END) as total_user
    FROM users");
$stats = mysqli_fetch_assoc($queryStats);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data User - NusantaraWild</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* ===== RESET & BASE ===== */
        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: #f0f2f5;
            color: #1a1a2e;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* ===== SCREEN-ONLY: Preview Toolbar ===== */
        .preview-toolbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            padding: 14px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 24px rgba(0,0,0,.25);
            backdrop-filter: blur(10px);
        }

        .preview-toolbar .toolbar-title {
            color: #e2e8f0;
            font-weight: 600;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .preview-toolbar .toolbar-title i {
            font-size: 20px;
            color: #38bdf8;
        }

        .preview-toolbar .toolbar-actions {
            display: flex;
            gap: 10px;
        }

        .btn-toolbar {
            padding: 10px 22px;
            border: none;
            border-radius: 10px;
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.25s ease;
            text-decoration: none;
        }

        .btn-print {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
            box-shadow: 0 4px 16px rgba(102, 126, 234, .35);
        }

        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 24px rgba(102, 126, 234, .5);
        }

        .btn-back {
            background: rgba(255,255,255,.08);
            color: #94a3b8;
            border: 1px solid rgba(255,255,255,.1);
        }

        .btn-back:hover {
            background: rgba(255,255,255,.14);
            color: #e2e8f0;
        }

        /* ===== DOCUMENT WRAPPER ===== */
        .document-wrapper {
            max-width: 900px;
            margin: 90px auto 60px;
            padding: 0 20px;
        }

        .document {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 40px rgba(0,0,0,.08), 0 1px 4px rgba(0,0,0,.04);
            overflow: hidden;
        }

        /* ===== HEADER ===== */
        .doc-header {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #164e63 100%);
            padding: 40px 48px 36px;
            position: relative;
            overflow: hidden;
        }

        .doc-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(56, 189, 248, .12) 0%, transparent 70%);
            border-radius: 50%;
        }

        .doc-header::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(99, 102, 241, .1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .header-content {
            position: relative;
            z-index: 1;
        }

        .brand-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .brand-info {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .brand-logo {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #38bdf8, #818cf8);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            color: #fff;
            box-shadow: 0 4px 16px rgba(56, 189, 248, .3);
        }

        .brand-text h1 {
            color: #fff;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .brand-text p {
            color: #94a3b8;
            font-size: 12px;
            font-weight: 400;
            margin-top: 2px;
        }

        .doc-badge {
            background: rgba(56, 189, 248, .12);
            border: 1px solid rgba(56, 189, 248, .25);
            color: #7dd3fc;
            padding: 8px 18px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .header-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.15), transparent);
            margin-bottom: 20px;
        }

        .header-meta {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .meta-item {
            color: #cbd5e1;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .meta-item i {
            color: #38bdf8;
            font-size: 14px;
        }

        .meta-item strong {
            color: #f1f5f9;
            font-weight: 600;
        }

        /* ===== STATISTICS BAR ===== */
        .stats-bar {
            display: flex;
            border-bottom: 1px solid #e5e7eb;
        }

        .stat-item {
            flex: 1;
            padding: 22px 24px;
            text-align: center;
            border-right: 1px solid #e5e7eb;
            position: relative;
        }

        .stat-item:last-child {
            border-right: none;
        }

        .stat-item .stat-number {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -1px;
            line-height: 1;
            margin-bottom: 4px;
        }

        .stat-item .stat-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #64748b;
        }

        .stat-item.stat-total .stat-number { color: #0f172a; }
        .stat-item.stat-admin .stat-number { color: #7c3aed; }
        .stat-item.stat-user .stat-number { color: #0ea5e9; }

        .stat-item::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 30%;
            right: 30%;
            height: 3px;
            border-radius: 3px 3px 0 0;
        }

        .stat-item.stat-total::after { background: #0f172a; }
        .stat-item.stat-admin::after { background: #7c3aed; }
        .stat-item.stat-user::after { background: #0ea5e9; }

        /* ===== TABLE ===== */
        .table-section {
            padding: 28px 36px 20px;
        }

        .section-title {
            font-size: 14px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title i {
            color: #667eea;
        }

        .data-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
        }

        .data-table thead th {
            background: linear-gradient(180deg, #f8fafc, #f1f5f9);
            color: #475569;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 14px 16px;
            border-bottom: 2px solid #e2e8f0;
            text-align: left;
            white-space: nowrap;
        }

        .data-table thead th:first-child {
            text-align: center;
            width: 50px;
        }

        .data-table tbody tr {
            transition: background 0.15s ease;
        }

        .data-table tbody tr:nth-child(even) {
            background: #fafbfc;
        }

        .data-table tbody tr:hover {
            background: #f0f4ff;
        }

        .data-table tbody td {
            padding: 12px 16px;
            font-size: 13px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
            vertical-align: middle;
        }

        .data-table tbody td:first-child {
            text-align: center;
            font-weight: 600;
            color: #94a3b8;
        }

        .data-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Cell styles */
        .cell-name {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .name-avatar {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }

        .name-text {
            font-weight: 600;
            color: #1e293b;
        }

        .cell-email {
            color: #64748b;
            font-size: 12px;
        }

        .cell-phone {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            color: #475569;
            background: #f8fafc;
            padding: 4px 8px;
            border-radius: 6px;
            display: inline-block;
        }

        .cell-provinsi {
            font-size: 12px;
            color: #64748b;
        }

        .badge-role {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.3px;
        }

        .badge-admin-pdf {
            background: linear-gradient(135deg, #ede9fe, #f3e8ff);
            color: #7c3aed;
            border: 1px solid #ddd6fe;
        }

        .badge-user-pdf {
            background: linear-gradient(135deg, #e0f2fe, #ecfeff);
            color: #0284c7;
            border: 1px solid #bae6fd;
        }

        /* ===== FOOTER ===== */
        .doc-footer {
            background: #f8fafc;
            border-top: 1px solid #e5e7eb;
            padding: 24px 36px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer-left {
            font-size: 11px;
            color: #94a3b8;
            line-height: 1.6;
        }

        .footer-left strong {
            color: #64748b;
        }

        .footer-signature {
            text-align: center;
        }

        .footer-signature .sig-label {
            font-size: 11px;
            color: #94a3b8;
            margin-bottom: 40px;
        }

        .footer-signature .sig-line {
            width: 180px;
            height: 1px;
            background: #cbd5e1;
            margin: 0 auto 6px;
        }

        .footer-signature .sig-name {
            font-size: 12px;
            font-weight: 600;
            color: #334155;
        }

        .footer-signature .sig-role {
            font-size: 10px;
            color: #94a3b8;
        }

        /* ===== WATERMARK ===== */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-35deg);
            font-size: 100px;
            font-weight: 900;
            color: rgba(0, 0, 0, .018);
            letter-spacing: 12px;
            text-transform: uppercase;
            pointer-events: none;
            z-index: 0;
            white-space: nowrap;
        }

        /* ===== FILTER NOTICE ===== */
        .filter-notice {
            margin: 0 36px;
            padding: 10px 16px;
            background: linear-gradient(135deg, #fef3c7, #fef9c3);
            border: 1px solid #fde68a;
            border-radius: 8px;
            font-size: 12px;
            color: #92400e;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-notice i {
            font-size: 16px;
        }

        /* ===== PRINT STYLES ===== */
        @media print {
            @page {
                size: A4 landscape;
                margin: 12mm 10mm;
            }

            body {
                background: #fff;
            }

            .preview-toolbar {
                display: none !important;
            }

            .document-wrapper {
                margin: 0;
                padding: 0;
                max-width: 100%;
            }

            .document {
                box-shadow: none;
                border-radius: 0;
            }

            .doc-header {
                padding: 28px 32px 24px;
            }

            .table-section {
                padding: 20px 24px 16px;
            }

            .data-table tbody tr:hover {
                background: inherit;
            }

            .data-table tbody tr:nth-child(even) {
                background: #fafbfc !important;
            }

            .doc-footer {
                padding: 20px 24px;
            }

            .watermark {
                font-size: 80px;
            }
        }

        /* ===== SCREEN-ONLY ANIMATION ===== */
        @media screen {
            .document {
                animation: slideUp 0.5s ease forwards;
            }

            @keyframes slideUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        }
    </style>
</head>
<body>

    <!-- Watermark -->
    <div class="watermark">NUSANTARAWILD</div>

    <!-- Preview Toolbar (hanya muncul di layar, hidden saat print) -->
    <div class="preview-toolbar">
        <div class="toolbar-title">
            <i class="bi bi-file-earmark-pdf-fill"></i>
            Preview Laporan Data User
        </div>
        <div class="toolbar-actions">
            <a href="index.php" class="btn-toolbar btn-back">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <button onclick="window.print()" class="btn-toolbar btn-print">
                <i class="bi bi-printer-fill"></i> Cetak / Simpan PDF
            </button>
        </div>
    </div>

    <!-- Document -->
    <div class="document-wrapper">
        <div class="document">

            <!-- Header -->
            <div class="doc-header">
                <div class="header-content">
                    <div class="brand-row">
                        <div class="brand-info">
                            <div class="brand-logo">
                                <i class="bi bi-compass"></i>
                            </div>
                            <div class="brand-text">
                                <h1>NusantaraWild</h1>
                                <p>Sistem Informasi Wisata Alam Indonesia</p>
                            </div>
                        </div>
                        <div class="doc-badge">
                            <i class="bi bi-file-earmark-bar-graph me-1"></i>
                            Laporan Resmi
                        </div>
                    </div>

                    <div class="header-divider"></div>

                    <div class="header-meta">
                        <div class="meta-item">
                            <i class="bi bi-file-text"></i>
                            <span>Jenis: <strong>Laporan Data User</strong></span>
                        </div>
                        <div class="meta-item">
                            <i class="bi bi-calendar3"></i>
                            <span>Tanggal: <strong><?= $tanggal_cetak ?></strong></span>
                        </div>
                        <div class="meta-item">
                            <i class="bi bi-clock"></i>
                            <span>Waktu: <strong><?= $waktu_cetak ?> WIB</strong></span>
                        </div>
                        <div class="meta-item">
                            <i class="bi bi-person-badge"></i>
                            <span>Dicetak oleh: <strong><?= htmlspecialchars($nama_admin) ?></strong></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Bar -->
            <div class="stats-bar">
                <div class="stat-item stat-total">
                    <div class="stat-number"><?= $stats['total'] ?? 0 ?></div>
                    <div class="stat-label">Total Pengguna</div>
                </div>
                <div class="stat-item stat-admin">
                    <div class="stat-number"><?= $stats['total_admin'] ?? 0 ?></div>
                    <div class="stat-label">Administrator</div>
                </div>
                <div class="stat-item stat-user">
                    <div class="stat-number"><?= $stats['total_user'] ?? 0 ?></div>
                    <div class="stat-label">User Biasa</div>
                </div>
            </div>

            <!-- Filter Notice (jika ada filter pencarian) -->
            <?php if (!empty($cari)): ?>
            <div style="padding-top: 20px;">
                <div class="filter-notice">
                    <i class="bi bi-funnel-fill"></i>
                    <span>Data difilter berdasarkan pencarian: <strong>"<?= htmlspecialchars($cari) ?>"</strong> — Menampilkan <?= $totalUsers ?> hasil</span>
                </div>
            </div>
            <?php endif; ?>

            <!-- Table Section -->
            <div class="table-section">
                <div class="section-title">
                    <i class="bi bi-table"></i>
                    Daftar Pengguna Terdaftar
                </div>

                <?php if ($totalUsers > 0): ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Lengkap</th>
                            <th>Email</th>
                            <th>No. HP</th>
                            <th>Provinsi</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $colors = ['#667eea','#11998e','#f5576c','#4facfe','#a18cd1','#f093fb','#38ef7d','#f6d365','#fa709a','#00c6fb'];
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($query)):
                            $initials = strtoupper(substr($row['nama'], 0, 1));
                            $color = $colors[($no - 1) % count($colors)];
                        ?>
                        <tr>
                            <td><?= $no ?></td>
                            <td>
                                <div class="cell-name">
                                    <div class="name-avatar" style="background: <?= $color ?>;">
                                        <?= $initials ?>
                                    </div>
                                    <span class="name-text"><?= htmlspecialchars($row['nama']) ?></span>
                                </div>
                            </td>
                            <td class="cell-email"><?= htmlspecialchars($row['email']) ?></td>
                            <td><span class="cell-phone"><?= htmlspecialchars($row['no_hp']) ?></span></td>
                            <td class="cell-provinsi"><?= htmlspecialchars($row['provinsi']) ?></td>
                            <td>
                                <?php if ($row['role'] == 'admin'): ?>
                                    <span class="badge-role badge-admin-pdf">🛡️ Admin</span>
                                <?php else: ?>
                                    <span class="badge-role badge-user-pdf">👤 User</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php $no++; endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <div style="text-align: center; padding: 40px; color: #94a3b8;">
                    <i class="bi bi-inbox" style="font-size: 48px; display: block; margin-bottom: 12px;"></i>
                    <p>Tidak ada data user yang ditemukan.</p>
                </div>
                <?php endif; ?>
            </div>

            <!-- Footer -->
            <div class="doc-footer">
                <div class="footer-left">
                    <strong>NusantaraWild</strong> — Sistem Informasi Wisata Alam Indonesia<br>
                    Dokumen ini digenerate secara otomatis pada <?= $tanggal_cetak ?> pukul <?= $waktu_cetak ?> WIB<br>
                    Total data: <strong><?= $totalUsers ?> pengguna</strong>
                </div>
                <div class="footer-signature">
                    <div class="sig-label">Mengetahui,</div>
                    <div class="sig-line"></div>
                    <div class="sig-name"><?= htmlspecialchars($nama_admin) ?></div>
                    <div class="sig-role">Administrator Sistem</div>
                </div>
            </div>

        </div>
    </div>

    <script>
        // Auto print saat halaman selesai dimuat
        window.addEventListener('DOMContentLoaded', () => {
            // Delay sedikit agar styling & font ter-render sempurna
            setTimeout(() => {
                window.print();
            }, 600);
        });
    </script>

</body>
</html>
