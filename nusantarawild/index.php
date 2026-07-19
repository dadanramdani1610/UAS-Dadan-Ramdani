<?php
/**
 * Halaman Beranda (Landing Page) NusantaraWild.
 * Menampilkan kategori wisata, destinasi unggulan, alasan memilih kami, dan CTA daftar/login.
 */
session_start();

// === PHP VARIABLE ===
date_default_timezone_set('Asia/Jakarta');
$nama_website = "NusantaraWild";
$tagline = "Jelajahi Keajaiban Alam Indonesia";
$tahun = date("Y");
$visitor_count = 128450;

// === ARRAY destinasi unggulan ===
include 'includes/koneksi.php';

$query = mysqli_query($koneksi, "
    SELECT * FROM destinasi
    ORDER BY id DESC
    LIMIT 6
");

$destinasi_unggulan = [];

while ($row = mysqli_fetch_assoc($query)) {
    $destinasi_unggulan[] = $row;
}
// === FUNCTION: Format Rupiah ===
function formatRupiah($angka)
{
    return "Rp " . number_format($angka, 0, ',', '.');
}

// === FUNCTION: Render Bintang Rating ===
function renderBintang($rating)
{
    $bintang = "";
    $full = floor($rating);
    for ($i = 0; $i < 5; $i++) {
        if ($i < $full) {
            $bintang .= '<i class="bi bi-star-fill text-warning"></i>';
        } elseif ($i == $full && ($rating - $full) >= 0.5) {
            $bintang .= '<i class="bi bi-star-half text-warning"></i>';
        } else {
            $bintang .= '<i class="bi bi-star text-warning"></i>';
        }
    }
    return $bintang;
}

// === BRANCHING 1: Cek status login ===
$status_login = isset($_SESSION['user']) ? "login" : "tamu";
$nama_user = ($status_login === "login") ? $_SESSION['user']['nama'] : "Wisatawan";

// === BRANCHING 2: Cek cookie salam ===
$salam = "";
$jam = (int) date("H");
if ($jam >= 5 && $jam < 12) {
    $salam = "Selamat Pagi";
} elseif ($jam >= 12 && $jam < 15) {
    $salam = "Selamat Siang";
} elseif ($jam >= 15 && $jam < 18) {
    $salam = "Selamat Sore";
} else {
    $salam = "Selamat Malam";
}

// Set cookie kunjungan & reset popup on login
if (isset($_SESSION['user']) && !isset($_SESSION['consent_reset'])) {
    // Pengguna baru saja login, hapus cookie consent agar popup muncul lagi
    setcookie('cookie_consent', '', time() - 3600, "/");
    $_SESSION['consent_reset'] = true;
    
    // Reset/tambah kunjungan saat login baru
    $kunjungan_ke = isset($_COOKIE['kunjungan']) ? (int)$_COOKIE['kunjungan'] + 1 : 2;
    setcookie('kunjungan', $kunjungan_ke, time() + (86400 * 30), "/");
} else {
    if (!isset($_COOKIE['kunjungan'])) {
        setcookie('kunjungan', 1, time() + (86400 * 30), "/");
        $kunjungan_ke = 1;
    } else {
        $kunjungan_ke = (int) $_COOKIE['kunjungan'];
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $nama_website ?> - <?= $tagline ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="style/css/style.css">
    <script>
        function acceptCookies() {
            // Set cookie consent accepted
            document.cookie = "cookie_consent=accepted; max-age=" + (60 * 60 * 24 * 30) + "; path=/";
            
            // Sembunyikan pop-up overlay
            const overlay = document.getElementById("cookie-consent-overlay");
            if (overlay) {
                overlay.style.display = "none";
            }
        }

        function declineCookies() {
            // Set cookie consent declined
            document.cookie = "cookie_consent=declined; max-age=" + (60 * 60 * 24 * 30) + "; path=/";
            
            // Sembunyikan pop-up overlay
            const overlay = document.getElementById("cookie-consent-overlay");
            if (overlay) {
                overlay.style.display = "none";
            }
        }

        // Cek langsung saat load
        document.addEventListener("DOMContentLoaded", function() {
            if (document.cookie.indexOf("cookie_consent=") !== -1) {
                const overlay = document.getElementById("cookie-consent-overlay");
                if (overlay) {
                    overlay.style.display = "none";
                }
            }

            // Real-time Greeting Handler
            function updateGreeting() {
                const badge = document.getElementById("hero-greeting-badge");
                if (!badge) return;
                
                const namaUser = badge.getAttribute("data-user");
                const jam = new Date().getHours();
                let salam = "Selamat Malam";
                
                if (jam >= 5 && jam < 12) {
                    salam = "Selamat Pagi";
                } else if (jam >= 12 && jam < 15) {
                    salam = "Selamat Siang";
                } else if (jam >= 15 && jam < 18) {
                    salam = "Selamat Sore";
                }
                
                badge.innerHTML = salam + ", " + namaUser + "!";
            }
            
            updateGreeting();
            // Perbarui setiap 60 detik agar selalu real-time
            setInterval(updateGreeting, 60000);
        });
    </script>
</head>

<body>

    <?php include 'includes/navbar.php'; ?>

    <!-- HERO SECTION -->
    <section class="hero-section" id="hero">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <div class="container text-center text-white">
                <span class="hero-badge" id="hero-greeting-badge" data-user="<?= htmlspecialchars($nama_user) ?>"><?= $salam ?>, <?= $nama_user ?>!</span>
                <h1 class="hero-title"><?= $tagline ?></h1>
                <p class="hero-subtitle">Dari sabang sampai merauke, ribuan destinasi menakjubkan menanti Anda</p>
                <div class="hero-search">
                    <form action="destinasi.php" method="GET" class="d-flex gap-2 justify-content-center flex-wrap">
                        <input type="text" name="cari" class="form-control search-input"
                            placeholder="Cari destinasi wisata...">
                        <button type="submit" class="btn btn-cari">
                            <i class="bi bi-search me-2"></i>Cari
                        </button>
                    </form>
                </div>
                <div class="hero-stats">
                    <div class="stat-item">
                        <span class="stat-number">500+</span>
                        <span class="stat-label">Destinasi</span>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat-item">
                        <span class="stat-number">34</span>
                        <span class="stat-label">Provinsi</span>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat-item">
                        <span class="stat-number"><?= number_format($visitor_count) ?></span>
                        <span class="stat-label">Pengunjung</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="hero-scroll"><i class="bi bi-chevron-double-down"></i></div>
    </section>

    <!-- POP-UP COOKIE CONSENT & KUNJUNGAN NOTIFICATION -->
    <div id="cookie-consent-overlay" class="cookie-popup-overlay animate-fade">
        <div id="cookie-consent" class="cookie-popup-card animate-scale">
            <div class="cookie-popup-icon">
                <i class="bi bi-shield-fill-check"></i>
            </div>
            <h4>Persetujuan Cookie</h4>
            <p class="cookie-text">
                Kami menggunakan cookies untuk meningkatkan pengalaman Anda di website ini. 
                Dengan mengklik "Terima", Anda menyetujui kebijakan penggunaan cookie kami.
            </p>
            
            <!-- Notifikasi Kunjungan Terintegrasi -->
            <div class="cookie-visit-badge my-3">
                <?php if ($kunjungan_ke == 1): ?>
                    <span class="badge bg-success-subtle text-success border border-success border-opacity-25 px-3 py-2 rounded-pill">
                        <i class="bi bi-hand-wave-fill me-1"></i> Selamat datang! Ini kunjungan pertama Anda.
                    </span>
                <?php else: ?>
                    <span class="badge bg-info-subtle text-info border border-info border-opacity-25 px-3 py-2 rounded-pill">
                        <i class="bi bi-arrow-return-right me-1"></i> Selamat kembali! Ini kunjungan ke-<strong><?= $kunjungan_ke ?></strong> Anda.
                    </span>
                <?php endif; ?>
            </div>

            <div class="cookie-popup-buttons d-flex gap-2 justify-content-center">
                <button onclick="acceptCookies()" class="btn btn-green px-4 py-2 fw-semibold">
                    <i class="bi bi-check2-circle me-1"></i> Terima
                </button>
                <button onclick="declineCookies()" class="btn btn-outline-danger px-4 py-2 fw-semibold">
                    <i class="bi bi-x-circle me-1"></i> Tolak
                </button>
            </div>
        </div>
    </div>

        <!-- KATEGORI SECTION -->
        <section class="section-padding bg-light">
            <div class="container">
                <div class="section-header text-center">
                    <span class="section-badge">Kategori</span>
                    <h2 class="section-title">Jelajahi Berdasarkan Tipe</h2>
                </div>
                <div class="row g-4 justify-content-center">
                    <?php
                    // === LOOPING 1: Array kategori ===
                    $kategori_list = [
                        ["nama" => "Pantai", "icon" => "bi-water", "warna" => "#0ea5e9", "link" => "destinasi.php?kategori=Pantai"],
                        ["nama" => "Gunung", "icon" => "bi-triangle", "warna" => "#10b981", "link" => "destinasi.php?kategori=Gunung"],
                        ["nama" => "Hutan", "icon" => "bi-tree", "warna" => "#22c55e", "link" => "destinasi.php?kategori=Hutan"],
                        ["nama" => "Bahari", "icon" => "bi-tsunami", "warna" => "#3b82f6", "link" => "destinasi.php?kategori=Bahari"],
                        ["nama" => "Danau", "icon" => "bi-droplet-half", "warna" => "#8b5cf6", "link" => "destinasi.php?kategori=Danau"],
                        ["nama" => "Taman Nasional", "icon" => "bi-flower1", "warna" => "#f59e0b", "link" => "destinasi.php?kategori=Taman+Nasional"],
                    ];
                    foreach ($kategori_list as $kat):
                        ?>
                        <div class="col-6 col-md-4 col-lg-2">
                            <a href="<?= $kat['link'] ?>" class="text-decoration-none">
                                <div class="kategori-card text-center p-4">
                                    <!-- style inline sengaja dipakai: warna ikon kategori berbeda-beda sesuai data array $kategori_list -->
                                    <div class="kategori-icon"
                                        style="background: <?= $kat['warna'] ?>20; color: <?= $kat['warna'] ?>">
                                        <i class="bi <?= $kat['icon'] ?>"></i>
                                    </div>
                                    <p class="kategori-nama mb-0"><?= $kat['nama'] ?></p>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- DESTINASI UNGGULAN -->
        <section class="section-padding">
            <div class="container">
                <div class="section-header d-flex justify-content-between align-items-end flex-wrap gap-3">
                    <div>
                        <span class="section-badge">Populer</span>
                        <h2 class="section-title mb-0">Destinasi Unggulan</h2>
                    </div>
                    <a href="destinasi.php" class="btn btn-outline-green">Lihat Semua <i
                            class="bi bi-arrow-right ms-1"></i></a>
                </div>
                <div class="row g-4">
                    <?php

                    foreach ($destinasi_unggulan as $index => $dest):
                        // === BRANCHING 3: Badge khusus untuk destinasi top ===
                        $badge = "";
                        if ($dest['rating'] >= 4.9) {
                            $badge = '<span class="dest-badge badge-top">🏆 Top Pick</span>';
                        } elseif ($dest['rating'] >= 4.7) {
                            $badge = '<span class="dest-badge badge-hot">🔥 Populer</span>';
                        }
                        ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="dest-card">
                                <div class="dest-img-wrap">
                                    <img src="image/<?= $dest['foto'] ?>" alt="<?= $dest['nama'] ?>" class="dest-img">
                                    <?= $badge ?>
                                    <div class="dest-kategori"><?= $dest['kategori'] ?></div>
                                </div>
                                <div class="dest-body">
                                    <h5 class="dest-title"><?= $dest['nama'] ?></h5>
                                    <p class="dest-lokasi"><i class="bi bi-geo-alt-fill me-1"></i><?= $dest['lokasi'] ?></p>
                                    <p class="dest-desc"><?= $dest['deskripsi'] ?></p>
                                    <div class="dest-footer">
                                        <div class="dest-rating">
                                            <?= renderBintang($dest['rating']) ?>
                                            <span class="ms-1"><?= $dest['rating'] ?></span>
                                        </div>
                                        <div class="dest-harga"><?= formatRupiah($dest['harga']) ?><small>/orang</small>
                                        </div>
                                    </div>
                                    <a href="destinasi.php?id=<?= $dest['id'] ?>" class="btn btn-green w-100 mt-3">
                                        <i class="bi bi-compass me-1"></i> Jelajahi
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- KENAPA KAMI -->
        <section class="section-padding why-section">
            <div class="container">
                <div class="section-header text-center">
                    <span class="section-badge">Keunggulan</span>

                </div>
                <div class="row g-4">
                    <?php
                    $keunggulan = [
                        ["icon" => "bi-shield-check", "judul" => "Terpercaya", "desc" => "Informasi destinasi akurat & terverifikasi dari sumber terpercaya"],
                        ["icon" => "bi-map", "judul" => "Lengkap", "desc" => "Ribuan destinasi wisata dari Sabang sampai Merauke"],
                        ["icon" => "bi-headset", "judul" => "Dukungan 24/7", "desc" => "Tim kami siap membantu perjalanan wisata Anda kapan saja"],
                        ["icon" => "bi-heart", "judul" => "Ramah Lingkungan", "desc" => "Mendukung ekowisata dan pelestarian alam Indonesia"],
                    ];
                    // === LOOPING 3: while loop keunggulan ===
                    $i = 0;
                    while ($i < count($keunggulan)):
                        $item = $keunggulan[$i];
                        ?>
                        <div class="col-md-6 col-lg-3">
                            <div class="why-card text-center">
                                <div class="why-icon"><i class="bi <?= $item['icon'] ?>"></i></div>
                                <h5 class="why-title"><?= $item['judul'] ?></h5>
                                <p class="why-desc"><?= $item['desc'] ?></p>
                            </div>
                        </div>
                        <?php $i++; endwhile; ?>
                </div>
            </div>
        </section>

        <!-- CTA SECTION -->
        <section class="cta-section">
            <div class="container text-center text-white">
                <h2 class="cta-title">Siap Memulai Petualangan?</h2>
                <p class="cta-sub">Daftar sekarang dan dapatkan rekomendasi wisata personal untuk Anda</p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <?php if ($status_login !== "login"): ?>
                        <a href="register.php" class="btn btn-cta-white">
                            <i class="bi bi-person-plus me-2"></i>Daftar Gratis
                        </a>
                        <a href="login.php" class="btn btn-cta-outline">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                        </a>
                    <?php else: ?>
                        <a href="destinasi.php" class="btn btn-cta-white">
                            <i class="bi bi-compass me-2"></i>Jelajahi Destinasi
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <?php include 'includes/footer.php'; ?>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="assets/js/main.js"></script>
</body>

</html>