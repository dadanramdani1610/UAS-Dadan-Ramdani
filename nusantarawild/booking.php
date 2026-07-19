<?php
/**
 * Halaman pemesanan (booking) tiket untuk sebuah destinasi.
 * Menampilkan form booking beserta ringkasan destinasi yang dipilih.
 */
session_start();
include 'includes/koneksi.php';

// ==============================
// FUNCTION
// ==============================

function formatRupiah($angka)
{
    return "Rp " . number_format($angka, 0, ',', '.');
}

function renderBintang($rating)
{

    $bintang = "";

    for ($i = 1; $i <= 5; $i++) {

        if ($i <= floor($rating)) {
            $bintang .= "⭐";
        } else {
            $bintang .= "☆";
        }

    }

    return $bintang;
}

$filter_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$query = mysqli_query($koneksi, "
SELECT *
FROM destinasi
WHERE id='$filter_id'
");
$dest = mysqli_fetch_assoc($query);

$errors = [];
$booking_sukses = false;
$booking_data = [];

// === PROSES FORM BOOKING ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_pemesan = trim($_POST['nama_pemesan'] ?? '');
    $email_pemesan = trim($_POST['email_pemesan'] ?? '');
    $tanggal = trim($_POST['tanggal'] ?? '');
    $jumlah_orang = (int) ($_POST['jumlah_orang'] ?? 0);
    $catatan = trim($_POST['catatan'] ?? '');
    $dest_id = (int) ($_POST['dest_id'] ?? 0);
    $metode = trim($_POST['metode_bayar'] ?? '');

    // Temukan destinasi yang dipesan
    $query = mysqli_query($koneksi, "
        SELECT *
        FROM destinasi
        WHERE id='$dest_id'
        ");
    $dest_dipesan = mysqli_fetch_assoc($query);

    // === BRANCHING: Validasi form booking ===
    if (strlen($nama_pemesan) < 3) {
        $errors[] = "Nama pemesan minimal 3 karakter.";
    }
    if (!filter_var($email_pemesan, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid.";
    }
    if (empty($tanggal) || strtotime($tanggal) < strtotime(date('Y-m-d'))) {
        $errors[] = "Tanggal kunjungan harus hari ini atau setelahnya.";
    }
    if ($jumlah_orang < 1 || $jumlah_orang > 50) {
        $errors[] = "Jumlah orang harus antara 1 - 50.";
    }
    if (empty($metode)) {
        $errors[] = "Pilih metode pembayaran.";
    }
    if (!$dest_dipesan) {
        $errors[] = "Destinasi tidak ditemukan.";
    }

    // === BRANCHING: Proses jika valid ===
    if (empty($errors)) {
        $total_harga = $dest_dipesan['harga'] * $jumlah_orang;
        $kode_booking = strtoupper(substr(md5(uniqid()), 0, 8));
$id_user = $_SESSION['user']['id_user'] ?? NULL;
        mysqli_query($koneksi,"
INSERT INTO booking(
kode_booking,
id_user,
id_destinasi,
lokasi,
nama_pemesan,
email,
tanggal_kunjungan,
jumlah_orang,
metode_pembayaran,
catatan,
total_harga
)
VALUES(
'$kode_booking',
".($id_user===NULL ? "NULL" : "'$id_user'").",
'$dest_id',
'".$dest_dipesan['lokasi']."',
'$nama_pemesan',
'$email_pemesan',
'$tanggal',
'$jumlah_orang',
'$metode',
'$catatan',
'$total_harga'
)
");
        $booking_data = [
            'kode' => $kode_booking,
            'nama' => $nama_pemesan,
            'email' => $email_pemesan,
            'tanggal' => $tanggal,
            'jumlah_orang' => $jumlah_orang,
            'destinasi' => $dest_dipesan['nama'],
            'lokasi' => $dest_dipesan['lokasi'],
            'total' => $total_harga,
            'metode' => $metode,
            'catatan' => $catatan,
        ];

        $_SESSION['last_booking'] = $booking_data;

        $booking_sukses = true;
        $dest = $dest_dipesan;

        $booking_data = [
            'kode' => $kode_booking,
            'nama' => $nama_pemesan,
            'email' => $email_pemesan,
            'tanggal' => $tanggal,
            'jumlah_orang' => $jumlah_orang,
            'destinasi' => $dest_dipesan['nama'],
            'lokasi' => $dest_dipesan['lokasi'],
            'total' => $total_harga,
            'metode' => $metode,
            'catatan' => $catatan,
        ];

        // Simpan ke session
        $_SESSION['last_booking'] = $booking_data;

        $booking_sukses = true;
        $dest = $dest_dipesan;
    }
}

// === FUNCTION: Hitung total harga ===
function hitungTotal($harga_satuan, $jumlah)
{
    return $harga_satuan * $jumlah;
}

// === FUNCTION: Generate kode booking ===
function generateKode($prefix = "NW")
{
    return $prefix . "-" . strtoupper(substr(md5(uniqid()), 0, 6));
}

$today = date('Y-m-d');
$nama_user = $_SESSION['user']['nama'] ?? '';
$email_user = $_SESSION['user']['email'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Wisata - NusantaraWild</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="style/css/style.css">
</head>

<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="page-header cs-6541cf">
        <div class="page-header-overlay"></div>
        <div class="container text-white text-center position-relative">
            <h1 class="page-title">Pemesanan Wisata</h1>
            <p class="page-sub">Lengkapi data di bawah untuk memesan tiket wisata</p>
        </div>
    </div>

    <div class="container my-5">

        <?php if ($booking_sukses): ?>
            <!-- SUCCESS STATE -->
            <div class="booking-success-card text-center">
                <div class="success-icon"><i class="bi bi-check-circle-fill"></i></div>
                <h2 class="mt-3 fw-bold text-success">Pemesanan Berhasil!</h2>
                <p class="text-muted">Terima kasih, <strong><?= htmlspecialchars($booking_data['nama']) ?></strong>! Detail
                    booking telah dikirim ke email Anda.</p>

                <div class="booking-receipt mt-4">
                    <div class="receipt-header">
                        <span class="receipt-logo"><i class="bi bi-compass me-2"></i>NusantaraWild</span>
                        <span class="receipt-kode"><?= $booking_data['kode'] ?></span>
                    </div>
                    <div class="receipt-body">
                        <div class="receipt-row">
                            <span>Destinasi</span><strong><?= htmlspecialchars($booking_data['destinasi']) ?></strong>
                        </div>
                        <div class="receipt-row">
                            <span>Lokasi</span><strong><?= htmlspecialchars($booking_data['lokasi']) ?></strong>
                        </div>
                        <div class="receipt-row">
                            <span>Tanggal</span><strong><?= date("d F Y", strtotime($booking_data['tanggal'])) ?></strong>
                        </div>
                        <div class="receipt-row"><span>Jumlah Orang</span><strong><?= $booking_data['jumlah_orang'] ?>
                                orang</strong></div>
                        <div class="receipt-row"><span>Metode
                                Bayar</span><strong><?= htmlspecialchars($booking_data['metode']) ?></strong></div>
                        <?php if (!empty($booking_data['catatan'])): ?>
                            <div class="receipt-row">
                                <span>Catatan</span><strong><?= htmlspecialchars($booking_data['catatan']) ?></strong>
                            </div>
                        <?php endif; ?>
                        <div class="receipt-total">
                            <span>Total Pembayaran</span>
                            <strong><?= formatRupiah($booking_data['total']) ?></strong>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-3 justify-content-center mt-4 flex-wrap">
                    <a href="destinasi.php" class="btn btn-green"><i class="bi bi-compass me-2"></i>Jelajahi Lagi</a>
                    <a href="index.php" class="btn btn-outline-green"><i class="bi bi-house me-2"></i>Beranda</a>
                </div>
            </div>

        <?php else: ?>
            <!-- FORM BOOKING -->
            <div class="row g-5">
                <div class="col-lg-8">
                    <div class="booking-form-card">
                        <h4 class="fw-bold mb-4"><i class="bi bi-calendar-check me-2 text-success"></i>Isi Data Pemesanan
                        </h4>

                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0 ps-3">
                                    <?php foreach ($errors as $e): ?>
                                        <li><?= htmlspecialchars($e) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <!-- FORM INPUT HTML-PHP: Booking -->
                        <form action="booking.php" method="POST" id="formBooking">
                            <input type="hidden" name="dest_id" value="<?= $dest ? $dest['id'] : '' ?>">

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nama Pemesan <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="nama_pemesan" class="form-control" placeholder="Nama lengkap"
                                        value="<?= htmlspecialchars($nama_user ?: ($_POST['nama_pemesan'] ?? '')) ?>"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email_pemesan" class="form-control"
                                        placeholder="nama@email.com"
                                        value="<?= htmlspecialchars($email_user ?: ($_POST['email_pemesan'] ?? '')) ?>"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Tanggal Kunjungan <span
                                            class="text-danger">*</span></label>
                                    <input type="date" name="tanggal" class="form-control" min="<?= $today ?>"
                                        value="<?= htmlspecialchars($_POST['tanggal'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Jumlah Orang <span
                                            class="text-danger">*</span></label>
                                    <input type="number" name="jumlah_orang" class="form-control" min="1" max="50"
                                        placeholder="1" value="<?= htmlspecialchars($_POST['jumlah_orang'] ?? '1') ?>"
                                        id="jumlahOrang" onchange="updateTotal()" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Metode Pembayaran <span
                                            class="text-danger">*</span></label>
                                    <div class="row g-2">
                                        <?php
                                        $metode_bayar = ["Transfer Bank", "QRIS", "Virtual Account", "Kartu Kredit"];
                                        // === LOOPING: Render metode pembayaran ===
                                        foreach ($metode_bayar as $idx => $metode):
                                            $checked = (isset($_POST['metode_bayar']) && $_POST['metode_bayar'] === $metode) ? 'checked' : ($idx === 0 ? 'checked' : '');
                                            ?>
                                            <div class="col-6 col-md-3">
                                                <label class="metode-label">
                                                    <input type="radio" name="metode_bayar" value="<?= $metode ?>" <?= $checked ?> class="d-none metode-radio">
                                                    <div class="metode-box">
                                                        <i class="bi bi-credit-card me-1"></i><?= $metode ?>
                                                    </div>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Catatan Khusus</label>
                                    <textarea name="catatan" class="form-control" rows="3"
                                        placeholder="Misalnya: alergi makanan, kebutuhan khusus, dll."><?= htmlspecialchars($_POST['catatan'] ?? '') ?></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-green btn-lg w-100">
                                        <i class="bi bi-check-circle me-2"></i>Konfirmasi Pemesanan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- SIDEBAR RINGKASAN -->
                <div class="col-lg-4">
                    <?php if ($dest): ?>
                        <div class="booking-summary-card sticky-top cs-10aa0f">
                            <img src="image/<?= $dest['foto']; ?>" alt="<?= $dest['nama']; ?>" class="w-100 rounded-3 mb-3 cs-cfdaba">
                            <h5 class="fw-bold"><?= $dest['nama'] ?></h5>
                            <p class="badge bg-success">
                                <?= $dest['kategori']; ?>
                            </p>
                            <p class="text-muted small"><i class="bi bi-geo-alt-fill me-1"></i><?= $dest['lokasi'] ?></p>
                            <p class="mb-3">
                                <?= renderBintang($dest['rating']); ?>
                                <strong><?= $dest['rating']; ?>/5</strong>
                            </p>
                            <hr>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Harga/orang</span>
                                <strong><?= formatRupiah($dest['harga']) ?></strong>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">
                                    Terbaik Dikunjungi
                                </span>
                                <strong>
                                    <?= $dest['terbaik_dikunjungi']; ?>
                                </strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Jumlah orang</span>
                                <strong id="summaryJumlah">1</strong>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold">Total</span>
                                <strong class="text-success fs-5" id="summaryTotal"><?= formatRupiah($dest['harga']) ?></strong>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Pilih destinasi terlebih dahulu dari halaman
                            <a href="destinasi.php">Destinasi</a>.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
    <?php include 'includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Update total harga secara real-time
        const hargaSatuan = <?= $dest ? $dest['harga'] : 0 ?>;
        function formatRp(n) {
            return 'Rp ' + n.toLocaleString('id-ID');
        }
        function updateTotal() {
            const jml = parseInt(document.getElementById('jumlahOrang')?.value || 1);
            document.getElementById('summaryJumlah').textContent = jml;
            document.getElementById('summaryTotal').textContent = formatRp(hargaSatuan * jml);
        }

        // Metode pembayaran styling
        document.querySelectorAll('.metode-radio').forEach(radio => {
            radio.addEventListener('change', function () {
                document.querySelectorAll('.metode-box').forEach(b => b.classList.remove('active'));
                if (this.checked) this.closest('.metode-label').querySelector('.metode-box').classList.add('active');
            });
            if (radio.checked) radio.closest('.metode-label').querySelector('.metode-box').classList.add('active');
        });
    </script>
</body>

</html>