<?php
/**
 * Halaman daftar semua destinasi wisata.
 * Mendukung pencarian & filter kategori, serta menampilkan detail satu destinasi jika parameter id dikirim.
 */
session_start();

include 'includes/koneksi.php';
function formatRupiah($angka)
{
    return "Rp " . number_format($angka, 0, ',', '.');
}

function renderBintang($rating)
{
    $bintang = "";
    $full = floor($rating);

    for ($i = 0; $i < 5; $i++) {
        if ($i < $full) {
            $bintang .= "⭐";
        } else {
            $bintang .= "☆";
        }
    }

    return $bintang;
}
// Ambil data dari database
$query = mysqli_query($koneksi, "SELECT * FROM destinasi");

$semua_destinasi = [];

while ($row = mysqli_fetch_assoc($query)) {
    $semua_destinasi[] = $row;
}

// === PHP VARIABLE ===
$halaman_judul = "Destinasi Wisata";

// === PHP VARIABLE ===
$halaman_judul = "Destinasi Wisata";
$cari = isset($_GET['cari']) ? htmlspecialchars(trim($_GET['cari'])) : "";
$filter_kategori = isset($_GET['kategori']) ? htmlspecialchars($_GET['kategori']) : "";
$filter_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;


// Simpan riwayat pencarian di session
if (!empty($cari)) {
    if (!isset($_SESSION['riwayat_cari'])) {
        $_SESSION['riwayat_cari'] = [];
    }
    // Hindari duplikat
    if (!in_array($cari, $_SESSION['riwayat_cari'])) {
        array_unshift($_SESSION['riwayat_cari'], $cari);
        // Simpan max 5 riwayat
        $_SESSION['riwayat_cari'] = array_slice($_SESSION['riwayat_cari'], 0, 5);
    }
}
// === FUNCTION: Filter destinasi ===
function filterDestinasi($data, $cari, $kategori)
{
    $hasil = [];
    foreach ($data as $item) {
        $cocok_cari = empty($cari) || stripos($item['nama'], $cari) !== false || stripos($item['lokasi'], $cari) !== false;
        $cocok_kat = empty($kategori) || $item['kategori'] === $kategori;
        if ($cocok_cari && $cocok_kat) {
            $hasil[] = $item;
        }
    }
    return $hasil;
}
// === FUNCTION: Cari destinasi berdasarkan ID ===
function cariDestinasiById($data, $id)
{
    foreach ($data as $item) {
        if ($item['id'] == $id)
            return $item;
    }
    return null;
}

$destinasi_tampil = filterDestinasi($semua_destinasi, $cari, $filter_kategori);

// Detail view
$detail = null;
if ($filter_id > 0) {
    $detail = cariDestinasiById($semua_destinasi, $filter_id);
}

$jam = (int) date("H");
if ($jam >= 5 && $jam < 12)
    $salam = "Selamat Pagi";
elseif ($jam >= 12 && $jam < 15)
    $salam = "Selamat Siang";
elseif ($jam >= 15 && $jam < 18)
    $salam = "Selamat Sore";
else
    $salam = "Selamat Malam";
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $halaman_judul ?> - NusantaraWild</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="style/css/style.css">
</head>

<body>

    <?php include 'includes/navbar.php'; ?>

    <!-- PAGE HEADER -->
    <div class="page-header">
        <div class="page-header-overlay"></div>
        <div class="container text-white text-center position-relative">
            <?php if ($detail): ?>
                <h1 class="page-title"><?= $detail['nama'] ?></h1>
                <p class="page-sub"><i class="bi bi-geo-alt-fill me-1"></i><?= $detail['lokasi'] ?></p>
            <?php else: ?>
                <h1 class="page-title">Destinasi Wisata Alam</h1>
                <p class="page-sub">Temukan keindahan alam Indonesia yang menakjubkan</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="container my-5">

        <?php if ($detail): ?>
            <!-- ===== DETAIL DESTINASI ===== -->
            <div class="row g-5">
                <div class="col-lg-7">
                    <img src="image/<?= $detail['foto']; ?>" alt="<?= $detail['nama']; ?>"
                        class="img-fluid rounded-4 shadow w-100 detail-hero-img">
                </div>
                <div class="col-lg-5">
                    <div class="detail-info-card">
                        <!-- BRANCHING: Tampilkan badge berdasarkan rating -->
                        <?php
                        if ($detail['rating'] >= 4.9) {
                            echo '<span class="badge bg-warning text-dark mb-3 fs-6">🏆 Destinasi Terbaik</span>';
                        } elseif ($detail['rating'] >= 4.7) {
                            echo '<span class="badge bg-success mb-3 fs-6">✅ Sangat Direkomendasikan</span>';
                        } else {
                            echo '<span class="badge bg-info mb-3 fs-6">ℹ️ Destinasi Populer</span>';
                        }
                        ?>
                        <h2 class="fw-bold"><?= $detail['nama'] ?></h2>
                        <p class="text-muted"><i class="bi bi-geo-alt-fill text-danger me-1"></i><?= $detail['lokasi'] ?>
                        </p>
                        <div class="mb-3">
                            <?= renderBintang($detail['rating']) ?>
                            <span class="fw-bold ms-2"><?= $detail['rating'] ?>/5.0</span>
                        </div>
                        <p class="detail-desc"><?= $detail['deskripsi'] ?></p>

                        <div class="info-grid">
                            <div class="info-item">
                                <i class="bi bi-tag-fill text-success"></i>
                                <div><small>Kategori</small><strong><?= $detail['kategori'] ?></strong></div>
                            </div>
                            <div class="info-item">
                                <i class="bi bi-currency-dollar text-success"></i>
                                <div><small>Harga Masuk</small><strong><?= formatRupiah($detail['harga']) ?></strong></div>
                            </div>
                            <div class="info-item">
                                <i class="bi bi-clock-fill text-success"></i>
                                <div><small>Jam Buka</small><strong>07:00 - 18:00</strong></div>
                            </div>
                            <div class="info-item">
                                <i class="bi bi-calendar-check text-success"></i>
                                <div>
                                    <small>Terbaik Dikunjungi</small>
                                    <strong><?= $detail['terbaik_dikunjungi']; ?></strong>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-3 mt-4">
                            <a href="booking.php?id=<?= $detail['id'] ?>" class="btn btn-green flex-fill">
                                <i class="bi bi-calendar-plus me-2"></i>Pesan Sekarang
                            </a>
                            <a href="destinasi.php" class="btn btn-outline-green">
                                <i class="bi bi-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <!-- ===== LIST DESTINASI ===== -->

            <!-- Filter & Search -->
            <div class="filter-bar mb-5">
                <form action="destinasi.php" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Cari Destinasi</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-search text-success"></i></span>
                            <input type="text" name="cari" class="form-control" placeholder="Nama atau lokasi..."
                                value="<?= $cari ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Kategori</label>
                        <select name="kategori" class="form-select">
                            <option value="">Semua Kategori</option>
                            <?php
                            $kategori_opts = ["Pantai", "Gunung", "Hutan", "Bahari", "Danau", "Taman Nasional"];
                            foreach ($kategori_opts as $opt):
                                ?>
                                <option value="<?= $opt ?>" <?= $filter_kategori === $opt ? 'selected' : '' ?>><?= $opt ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-green w-100">Filter</button>
                    </div>
                </form>
            </div>

            <!-- Riwayat Pencarian (Session) -->
            <?php if (!empty($_SESSION['riwayat_cari'])): ?>
                <div class="mb-4">
                    <small class="text-muted me-2"><i class="bi bi-clock-history me-1"></i>Riwayat:</small>
                    <?php foreach ($_SESSION['riwayat_cari'] as $riwayat): ?>
                        <a href="destinasi.php?cari=<?= urlencode($riwayat) ?>"
                            class="badge bg-light text-dark border me-1 text-decoration-none"><?= htmlspecialchars($riwayat) ?></a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Hasil -->
            <?php if (!empty($cari) || !empty($filter_kategori)): ?>
                <p class="text-muted mb-4">
                    Menampilkan <strong><?= count($destinasi_tampil) ?></strong> destinasi
                    <?php if (!empty($cari)): ?> untuk "<strong><?= $cari ?></strong>"<?php endif; ?>
                    <?php if (!empty($filter_kategori)): ?> kategori <strong><?= $filter_kategori ?></strong><?php endif; ?>
                </p>
            <?php endif; ?>

            <?php if (empty($destinasi_tampil)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-search display-1 text-muted"></i>
                    <h4 class="mt-3 text-muted">Destinasi tidak ditemukan</h4>
                    <a href="destinasi.php" class="btn btn-green mt-3">Lihat Semua</a>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($destinasi_tampil as $dest):
                        // BRANCHING: Badge berdasarkan harga
                        if ($dest['harga'] < 300000) {
                            $harga_label = '<span class="badge bg-success-subtle text-success">Budget Friendly</span>';
                        } elseif ($dest['harga'] < 800000) {
                            $harga_label = '<span class="badge bg-warning-subtle text-warning">Mid Range</span>';
                        } else {
                            $harga_label = '<span class="badge bg-danger-subtle text-danger">Premium</span>';
                        }
                        ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="dest-card">
                                <div class="dest-img-wrap">
                                    <img src="image/<?= $dest['foto'] ?>" alt="<?= $dest['nama'] ?>" class="dest-img">
                                    <div class="dest-kategori"><?= $dest['kategori'] ?></div>
                                </div>
                                <div class="dest-body">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <h5 class="dest-title mb-0"><?= $dest['nama'] ?></h5>
                                        <?= $harga_label ?>
                                    </div>
                                    <p class="dest-lokasi"><i class="bi bi-geo-alt-fill me-1"></i><?= $dest['lokasi'] ?></p>
                                    <p class="dest-desc"><?= $dest['deskripsi'] ?></p>
                                    <div class="dest-footer">
                                        <div class="dest-rating">
                                            <?= renderBintang($dest['rating']) ?>
                                            <span class="ms-1"><?= $dest['rating'] ?></span>
                                        </div>
                                        <div class="dest-harga"><?= formatRupiah($dest['harga']) ?></div>
                                    </div>
                                    <a href="destinasi.php?id=<?= $dest['id'] ?>" class="btn btn-green w-100 mt-3">
                                        <i class="bi bi-arrow-right me-1"></i>Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

    </div><!-- /container -->

    <?php include 'includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>