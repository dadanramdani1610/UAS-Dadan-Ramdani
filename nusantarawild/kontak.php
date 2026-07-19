<?php
/**
 * Halaman Kontak: menampilkan info kontak perusahaan, sosial media, dan form pesan.
 */
session_start();
include "includes/koneksi.php";

$errors = [];
$success = "";
$old = [];

// === PROSES FORM KONTAK ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $no_hp = trim($_POST['no_hp'] ?? '');
    $subjek = trim($_POST['subjek'] ?? '');
    $pesan = trim($_POST['pesan'] ?? '');
    $kategori = trim($_POST['kategori'] ?? '');

    $old = compact(
        'nama',
        'email',
        'no_hp',
        'subjek',
        'pesan',
        'kategori'
    );



    // === BRANCHING: Validasi ===
    if (strlen($nama) < 2) {
        $errors[] = "Nama minimal 2 karakter.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid.";
    }
    if (strlen($no_hp) < 10) {
        $errors[] = "Nomor HP minimal 10 digit.";
    }
    if (empty($subjek)) {
        $errors[] = "Subjek harus diisi.";
    }
    if (strlen($pesan) < 20) {
        $errors[] = "Pesan minimal 20 karakter.";
    }
    if (empty($kategori)) {
        $errors[] = "Pilih kategori pesan.";
    }

    // === BRANCHING: Jika lolos validasi ===
    if (empty($errors)) {

        mysqli_query($koneksi, "
         INSERT INTO kontak
         (
                nama,
                email,
                no_hp,
                subjek,
                kategori,
                pesan
            )
            VALUES
            (
                '$nama',
                '$email',
                '$no_hp',
                '$subjek',
                '$kategori',
                '$pesan'
            )
            ");

        $success = "Pesan berhasil dikirim. Kami akan segera menghubungi Anda.";
        $old = [];
    }
}

// === FUNCTION: Kontak info ===
function getKontakInfo()
{
    return [
        ["icon" => "bi-geo-alt-fill", "label" => "Alamat", "nilai" => "Jl. Wisata Alam No.1, Jl. Radin Inten II, Pondok Kelapa,Jakarta Timur,13450"],
        ["icon" => "bi-telephone-fill", "label" => "Telepon", "nilai" => "+62 822 4665 9712"],
        ["icon" => "bi-envelope-fill", "label" => "Email", "nilai" => "dadanramdani@gmail.com"],
        ["icon" => "bi-clock-fill", "label" => "Jam Operasi", "nilai" => "Senin - Jumat: 08.00 - 17.00 WIB"],
    ];
}

$kontak_info = getKontakInfo();

$kategori_opts = [
    "Informasi Destinasi",
    "Pemesanan & Tiket",
    "Kemitraan",
    "Saran & Masukan",
    "Lainnya",
];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak Kami - NusantaraWild</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="style/css/style.css">
</head>

<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="page-header cs-0c30f1">
        <div class="page-header-overlay"></div>
        <div class="container text-white text-center position-relative">
            <h1 class="page-title">Hubungi Kami</h1>
            <p class="page-sub">Kami siap membantu perjalanan wisata Anda</p>
        </div>
    </div>

    <section class="section-padding">
        <div class="container">
            <div class="row g-5">

                <!-- INFO KONTAK -->
                <div class="col-lg-4">
                    <h4 class="fw-bold mb-4">Informasi Kontak</h4>
                    <?php
                    // === LOOPING 1: foreach kontak info ===
                    foreach ($kontak_info as $info):
                        ?>
                        <div class="kontak-info-item">
                            <div class="kontak-info-icon"><i class="bi <?= $info['icon'] ?>"></i></div>
                            <div>
                                <small class="text-muted d-block"><?= $info['label'] ?></small>
                                <strong><?= $info['nilai'] ?></strong>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <hr class="my-4">
                    <h6 class="fw-bold mb-3">Ikuti Kami</h6>
                    <div class="d-flex gap-2">
                        <?php
                        $sosmed = [
                            ["icon" => "bi-instagram", "warna" => "#e1306c", "url" => "https://www.instagram.com/rmddann_/"],
                            ["icon" => "bi-facebook", "warna" => "#1877f2", "url" => "#"],
                            ["icon" => "bi-youtube", "warna" => "#ff0000", "url" => "https://www.youtube.com/channel/UCrhTNpOVYZyBFEVmbxwyIpg"],
                            ["icon" => "bi-tiktok", "warna" => "#000", "url" => "https://www.tiktok.com/@deguricafer"],
                        ];
                        // === LOOPING 2: for loop sosmed ===
                        for ($i = 0; $i < count($sosmed); $i++):
                            ?>
                            <a href="<?= $sosmed[$i]['url'] ?>" class="sosmed-btn"
                                style="background:<?= $sosmed[$i]['warna'] ?>">
                                <i class="bi <?= $sosmed[$i]['icon'] ?>"></i>
                            </a>
                        <?php endfor; ?>
                    </div>
                </div>

                <!-- FORM KONTAK -->
                <div class="col-lg-8">
                    <div class="kontak-form-card">
                        <h4 class="fw-bold mb-4"><i class="bi bi-send me-2 text-success"></i>Kirim Pesan</h4>

                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0 ps-3">
                                    <?php foreach ($errors as $e): ?>
                                        <li><?= $e ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <?php if ($success): ?>
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle-fill me-2"></i><?= $success ?>
                            </div>
                        <?php endif; ?>

                        <!-- FORM INPUT HTML-PHP: Kontak -->
                        <form action="kontak.php" method="POST">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nama Lengkap <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="nama" class="form-control" placeholder="Masukkan nama"
                                        value="<?= htmlspecialchars($old['nama'] ?? ($_SESSION['user']['nama'] ?? '')) ?>"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Email <span
                                            class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" placeholder="nama@email.com"
                                        value="<?= htmlspecialchars($old['email'] ?? ($_SESSION['user']['email'] ?? '')) ?>"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        No HP
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="no_hp" class="form-control" placeholder="08xxxxxxxxxx"
                                        value="<?= htmlspecialchars($old['no_hp'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label fw-semibold">Subjek <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="subjek" class="form-control" placeholder="Topik pesan Anda"
                                        value="<?= htmlspecialchars($old['subjek'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Kategori <span
                                            class="text-danger">*</span></label>
                                    <select name="kategori" class="form-select" required>
                                        <option value="">-- Pilih --</option>
                                        <?php foreach ($kategori_opts as $kat):
                                            $sel = (isset($old['kategori']) && $old['kategori'] === $kat) ? 'selected' : '';
                                            ?>
                                            <option value="<?= $kat ?>" <?= $sel ?>><?= $kat ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Pesan <span
                                            class="text-danger">*</span></label>
                                    <textarea name="pesan" class="form-control" rows="6"
                                        placeholder="Tulis pesan Anda di sini (minimal 20 karakter)..."
                                        required><?= htmlspecialchars($old['pesan'] ?? '') ?></textarea>
                                    <div class="form-text">Minimal 20 karakter</div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-green btn-lg px-5">
                                        <i class="bi bi-send me-2"></i>Kirim Pesan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>