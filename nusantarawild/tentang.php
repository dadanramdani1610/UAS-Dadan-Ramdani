<?php
/**
 * Halaman Tentang Kami: cerita, visi & misi NusantaraWild.
 */
session_start();

// === PHP VARIABLE ===
$nama_website = "NusantaraWild";
$tahun_berdiri = 2020;
$tahun_sekarang = (int)date("Y");
$usia = $tahun_sekarang - $tahun_berdiri;

// === ARRAY: Data tim ===


// === ARRAY: Pencapaian ===
$pencapaian = [
    ["angka" => "500+",    "label" => "Destinasi Terdaftar"],
    ["angka" => "128K+",   "label" => "Pengguna Aktif"],
    ["angka" => "34",      "label" => "Provinsi Tercakup"],
    ["angka" => $usia."th","label" => "Tahun Berpengalaman"],
];

// === ARRAY: Misi ===
$misi = [
    "Memperkenalkan keindahan alam Indonesia kepada wisatawan domestik dan mancanegara",
    "Mendukung pengelolaan wisata yang berkelanjutan dan ramah lingkungan",
    "Memberdayakan komunitas lokal melalui ekowisata yang bertanggung jawab",
    "Menyediakan informasi destinasi yang akurat, lengkap, dan terpercaya",
];

// === BRANCHING: Status session untuk pesan personal ===
if (isset($_SESSION['user'])) {
    $pesan_personal = "Halo, " . htmlspecialchars($_SESSION['user']['nama']) . "! Terima kasih telah menjadi bagian dari komunitas kami.";
} else {
    $pesan_personal = "Bergabunglah dengan komunitas kami dan mulai petualangan Anda!";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - NusantaraWild</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style/css/style.css">
</head>
<body>
<?php include 'includes/navbar.php'; ?>

<!-- PAGE HEADER -->
<div class="page-header cs-8597b1">
    <div class="page-header-overlay"></div>
    <div class="container text-white text-center position-relative">
        <h1 class="page-title">Tentang Kami</h1>
        <p class="page-sub">Mengenal <?= $nama_website ?> lebih dekat</p>
    </div>
</div>

<!-- PERSONAL MESSAGE (Branching) -->
<div class="bg-success text-white text-center py-3">
    <i class="bi bi-heart-fill me-2"></i><?= $pesan_personal ?>
    <?php if (!isset($_SESSION['user'])): ?>
        <a href="register.php" class="btn btn-sm btn-light ms-3">Daftar Sekarang</a>
    <?php endif; ?>
</div>

<!-- TENTANG SECTION -->
<section class="section-padding">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <span class="section-badge">Siapa Kami</span>
                <h2 class="section-title">Platform Wisata Alam Terpercaya Indonesia</h2>
                <p class="text-muted lh-lg">
                    <strong><?= $nama_website ?></strong> adalah platform digital terdepan yang didedikasikan untuk
                    memperkenalkan dan mempromosikan keindahan wisata alam Indonesia. Didirikan sejak tahun
                    <?= $tahun_berdiri ?>, kami telah <?= $usia ?> tahun melayani wisatawan dari seluruh penjuru negeri.
                </p>
                <p class="text-muted lh-lg">
                    Dengan tim yang berpengalaman dan berdedikasi, kami berkomitmen untuk memberikan informasi
                    yang akurat, lengkap, dan terupdate tentang ribuan destinasi wisata alam di 34 provinsi Indonesia.
                </p>
                <div class="d-flex gap-3 mt-4">
                    <a href="destinasi.php" class="btn btn-green">
                        <i class="bi bi-compass me-2"></i>Jelajahi Destinasi
                    </a>
                    <a href="kontak.php" class="btn btn-outline-green">
                        <i class="bi bi-envelope me-2"></i>Hubungi Kami
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-img-grid">
                    <img src="image/bromo.jpg" class="about-img-main" alt="Raja Ampat">
                    <img src="image/pasir.png" class="about-img-sub1" alt="Bromo">
                    <img src="image/lembah.webp" class="about-img-sub2" alt="Pantai">
                     <img src="image/lembah.webp" class="about-img-sub2" alt="Pantai">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- PENCAPAIAN -->
<section class="stats-section">
    <div class="container">
        <div class="row g-4">
            <?php
            // === LOOPING 1: foreach pencapaian ===
            foreach ($pencapaian as $item):
            ?>
            <div class="col-6 col-md-3 text-center">
                <div class="tentang-stat-card">
                    <div class="stat-number-big"><?= $item['angka'] ?></div>
                    <div class="stat-label-big"><?= $item['label'] ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- VISI MISI -->
<section class="section-padding bg-light">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-5">
                <span class="section-badge">Nilai Kami</span>
                <h2 class="section-title">Visi & Misi</h2>
                <div class="visi-card">
                    <h5 class="fw-bold"><i class="bi bi-eye-fill text-success me-2"></i>Visi</h5>
                    <p class="text-muted">
                        Menjadi platform wisata alam Indonesia terlengkap dan terpercaya yang
                        menghubungkan wisatawan dengan keindahan alam Nusantara secara bertanggung jawab.
                    </p>
                </div>
            </div>
            <div class="col-lg-7">
                <span class="section-badge">Misi Kami</span>
                <h2 class="section-title">Yang Kami Perjuangkan</h2>
                <div class="misi-list">
                    <?php
                    // === LOOPING 2: for loop misi ===
                    for ($i = 0; $i < count($misi); $i++):
                    ?>
                    <div class="misi-item">
                        <div class="misi-nomor"><?= str_pad($i + 1, 2, '0', STR_PAD_LEFT) ?></div>
                        <p class="misi-text"><?= $misi[$i] ?></p>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- TIM KAMI -->
<section class="section-padding">
    <div class="container">
        <div class="section-header text-center">
            <span class="section-badge">Tim</span>
            <h2 class="section-title">Orang-Orang di Balik <?= $nama_website ?></h2>
        </div>
        <div class="row g-4 justify-content-center">
            <?php
            // === LOOPING 3: foreach tim ===
            foreach ($tim as $anggota):
            ?>
            <div class="col-sm-6 col-md-3">
                <div class="team-card text-center">
                    <img src="<?= $anggota['foto'] ?>" alt="<?= $anggota['nama'] ?>" class="team-foto">
                    <h6 class="team-nama mt-3"><?= $anggota['nama'] ?></h6>
                    <p class="team-jabatan text-success"><?= $anggota['jabatan'] ?></p>
                    <p class="team-bio text-muted small"><?= $anggota['bio'] ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <div class="container text-center text-white">
        <h2 class="cta-title">Jadilah Bagian dari Komunitas Kami</h2>
        <p class="cta-sub">Bergabung dengan <?= number_format(128450) ?>+ wisatawan yang sudah menggunakan <?= $nama_website ?></p>
        <?php if (!isset($_SESSION['user'])): ?>
        <a href="register.php" class="btn btn-cta-white">
            <i class="bi bi-person-plus me-2"></i>Daftar Gratis Sekarang
        </a>
        <?php else: ?>
        <a href="destinasi.php" class="btn btn-cta-white">
            <i class="bi bi-compass me-2"></i>Mulai Menjelajahi
        </a>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>
