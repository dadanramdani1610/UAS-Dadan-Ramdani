<?php
/**
 * Komponen navbar (menu navigasi atas) yang di-include di semua halaman publik.
 */
// Dapatkan nama file saat ini untuk active state
$halaman_saat_ini = basename($_SERVER['PHP_SELF']);

// === ARRAY: Menu navigasi ===
$menu_nav = [
    ["label" => "Beranda",   "href" => "index.php",    "file" => "index.php"],
    ["label" => "Destinasi", "href" => "destinasi.php","file" => "destinasi.php"],
    ["label" => "Tentang",   "href" => "tentang.php",  "file" => "tentang.php"],
    ["label" => "Staf Kami",  "href" => "staf.php",   "file" => "staf.php"],
    ["label" => "Kontak",    "href" => "kontak.php",   "file" => "kontak.php"],
];
?>
<nav class="navbar navbar-expand-lg navbar-main fixed-top" id="mainNavbar">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="bi bi-compass me-2"></i>NusantaraWild
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <i class="bi bi-list fs-4"></i>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav mx-auto gap-1">
                <?php
                // === LOOPING: Render menu ===
                foreach ($menu_nav as $menu):
                    $active = ($halaman_saat_ini === $menu['file']) ? 'active' : '';
                ?>
                <li class="nav-item">
                    <a class="nav-link <?= $active ?>" href="<?= $menu['href'] ?>">
                        <?= $menu['label'] ?>
                    </a>
                </li>
                <?php endforeach; ?>

                <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                <li class="nav-item">
                    <a class="nav-link text-warning fw-bold" href="admin/dashboard.php">
                        <i class="bi bi-speedometer2 me-1"></i>Dashboard Admin
                    </a>
                </li>
                <?php endif; ?>

            </ul>
            <div class="navbar-actions d-flex align-items-center gap-2">
                <!-- Riwayat Booking -->
                <a href="riwayat_booking.php" class="btn btn-cart position-relative" id="btnRiwayatBooking" title="Riwayat Booking">
                    <i class="bi bi-receipt me-1"></i> Riwayat Booking
                </a>


                <?php if (isset($_SESSION['user'])): ?>
                    <!-- Dropdown user jika sudah login -->
                    <div class="dropdown">
                        <button class="btn btn-user dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i>
                            <?= htmlspecialchars(explode(' ', $_SESSION['user']['nama'])[0]) ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li><span class="dropdown-item-text small text-muted"><?= htmlspecialchars($_SESSION['user']['email']) ?></span></li>
                            <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                            <li><span class="dropdown-item text-success"><i class="bi bi-shield-check me-1"></i>Admin</span></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Keluar</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="btn btn-nav-outline">Masuk</a>
                    <a href="register.php" class="btn btn-green">Daftar</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
