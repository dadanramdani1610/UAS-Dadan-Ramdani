<?php
/**
 * Komponen footer (bagian bawah halaman) yang di-include di semua halaman publik.
 */
$tahun = date("Y");
$link_footer = [
    "Destinasi" => [
        ["Pantai",          "destinasi.php?kategori=Pantai"],
        ["Gunung",          "destinasi.php?kategori=Gunung"],
        ["Hutan",           "destinasi.php?kategori=Hutan"],
        ["Taman Nasional",  "destinasi.php?kategori=Taman+Nasional"],
    ],
    "Perusahaan" => [
        ["Tentang Kami",    "tentang.php"],
        ["Kontak",          "kontak.php"],
        ["Karir",           "#"],
        ["Blog",            "#"],
    ],
    "Akun" => [
        ["Masuk",           "login.php"],
        ["Daftar",          "register.php"],
        ["Pesan Wisata",    "booking.php"],
    ],
];

$sosmed_footer = [
    ["icon" => "bi-instagram",  "url" => "https://www.instagram.com/rmddann_/",                              "label" => "Instagram",  "warna" => "#e1306c"],
    ["icon" => "bi-youtube",    "url" => "https://www.youtube.com/channel/UCrhTNpOVYZyBFEVmbxwyIpg",        "label" => "YouTube",    "warna" => "#ff0000"],
    ["icon" => "bi-tiktok",     "url" => "https://www.tiktok.com/@deguricafer",                              "label" => "TikTok",     "warna" => "#010101"],
    ["icon" => "bi-facebook",   "url" => "#",                                                                 "label" => "Facebook",   "warna" => "#1877f2"],
];
?>
<footer class="footer">
    <div class="container">
        <div class="row g-5">
            <!-- Brand + Alamat + Sosmed -->
            <div class="col-lg-4">
                <a href="index.php" class="footer-logo">
                    <i class="bi bi-compass me-2"></i>NusantaraWild
                </a>
                <p class="footer-desc mt-3">
                    Platform wisata alam Indonesia terlengkap. Temukan keindahan Nusantara dari Sabang sampai Merauke.
                </p>

                <!-- Alamat -->
                <div class="d-flex align-items-start gap-2 mt-3 mb-3">
                    <i class="bi bi-geo-alt-fill text-success mt-1 flex-shrink-0"></i>
                    <span class="footer-desc small mb-0">Jl. Wisata Alam No.1, Jl. Radin Inten II,<br>Pondok Kelapa, Jakarta Timur 13450</span>
                </div>

                <!-- Sosial Media -->
                <h6 class="footer-col-title mb-2">Ikuti Kami</h6>
                <div class="d-flex gap-2 flex-wrap">
                    <?php foreach ($sosmed_footer as $sm): ?>
                    <a href="<?= $sm['url'] ?>"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="footer-sosmed-link d-inline-flex align-items-center gap-1 text-decoration-none"
                       title="<?= $sm['label'] ?>"
                       style="background:<?= $sm['warna'] ?>"
                       onmouseover="this.style.opacity='.8'" onmouseout="this.style.opacity='1'">
                        <i class="bi <?= $sm['icon'] ?>"></i>
                        <span><?= $sm['label'] ?></span>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php foreach ($link_footer as $judul => $links): ?>
            <div class="col-6 col-lg-2">
                <h6 class="footer-col-title"><?= $judul ?></h6>
                <ul class="footer-links">
                    <?php foreach ($links as $link): ?>
                    <li><a href="<?= $link[1] ?>"><?= $link[0] ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endforeach; ?>

            <div class="col-lg-2">
                <h6 class="footer-col-title">Newsletter</h6>
                <p class="footer-desc small">Dapatkan info destinasi terbaru</p>
                <div class="input-group input-group-sm">
                    <input type="email" class="form-control" placeholder="Email Anda">
                    <button class="btn btn-green btn-sm"><i class="bi bi-send"></i></button>
                </div>
            </div>
        </div>
        <hr class="footer-hr">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <p class="footer-copy mb-0">&copy; <?= $tahun ?> NusantaraWild milik Dadan. Hak cipta dilindungi.</p>
            <div class="d-flex gap-3">
                <a href="#" class="footer-small-link">Privasi</a>
                <a href="#" class="footer-small-link">Ketentuan</a>
                <a href="#" class="footer-small-link">Cookie</a>
            </div>
        </div>
    </div>
</footer>
<div class="scroll-top">
    <a href="#" id="scrollTopBtn" class="scroll-top-btn">
        <i class="bi bi-arrow-up"></i>
    </a>
</div>
