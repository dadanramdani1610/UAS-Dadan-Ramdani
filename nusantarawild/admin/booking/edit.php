<?php
/**
 * Admin - Form edit/ubah data booking yang sudah ada.
 */
// === KONEKSI DATABASE ===
// Memasukkan file koneksi untuk menghubungkan ke database
include "../../includes/koneksi.php";

// === MENGAMBIL ID DARI URL ===
// Mendapatkan ID booking dari parameter URL untuk menentukan data mana yang akan diedit
$id = $_GET['id'];

// === QUERY: AMBIL DATA BOOKING AKTIF ===
// Membaca data reservasi booking yang sedang diedit berdasarkan ID-nya
$query_booking = mysqli_query($koneksi, "SELECT * FROM booking WHERE id_booking='$id'");
$data = mysqli_fetch_assoc($query_booking);

// === QUERY: AMBIL SEMUA DATA DESTINASI ===
// Digunakan untuk mengisi pilihan dropdown destinasi pada form
$destinasi = mysqli_query($koneksi, "SELECT * FROM destinasi");

// === PROSES UPDATE DATA (JIKA FORM DISUBMIT) ===
if (isset($_POST['update'])) {
    // Membaca input dari form
    $id_user = !empty($_POST['id_user']) ? $_POST['id_user'] : NULL;
    $id_destinasi = $_POST['id_destinasi'];
    $nama = $_POST['nama_pemesan'];
    $email = $_POST['email'];
    $tanggal = $_POST['tanggal_kunjungan'];
    $jumlah = $_POST['jumlah_orang'];
    $metode = $_POST['metode_pembayaran'];
    $catatan = $_POST['catatan'];
    $status = $_POST['status'];

    // === AMBIL HARGA DESTINASI TERPILIH ===
    // Digunakan untuk menghitung ulang total harga yang harus dibayar
    $q_harga = mysqli_query($koneksi, "SELECT harga FROM destinasi WHERE id='$id_destinasi'");
    $d = mysqli_fetch_assoc($q_harga);

    // === HITUNG TOTAL HARGA BARU ===
    // Total harga didapat dari perkalian harga tiket destinasi dengan jumlah orang
    $total = $d['harga'] * $jumlah;

    // Menangani nilai NULL untuk id_user agar tidak error di query
    $id_user_val = ($id_user == NULL) ? "NULL" : "'$id_user'";

    // === EXECUTE QUERY: UPDATE DATA BOOKING ===
    $update_query = "UPDATE booking SET
        id_user=$id_user_val,
        id_destinasi='$id_destinasi',
        nama_pemesan='$nama',
        email='$email',
        tanggal_kunjungan='$tanggal',
        jumlah_orang='$jumlah',
        metode_pembayaran='$metode',
        catatan='$catatan',
        total_harga='$total',
        status='$status'
        WHERE id_booking='$id'";
        
    mysqli_query($koneksi, $update_query);

    // === REDIRECT KEMBALI KE HALAMAN INDEX ===
    // Setelah berhasil update, arahkan pengguna kembali ke daftar booking
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Booking</title>
    <!-- Memuat Bootstrap CSS untuk styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../style/css/style.css">
</head>
<body class="admin-page">
    <div class="container mt-5">
        <h2>Edit Booking</h2>
        <!-- Form edit data booking -->
        <form method="POST">
            <!-- Input ID User -->
            <div class="mb-3">
                <label class="form-label">ID User</label>
                <input type="number" name="id_user" class="form-control" value="<?= $data['id_user']; ?>">
            </div>

            <!-- Pilihan Destinasi -->
            <div class="mb-3">
                <label class="form-label">Destinasi</label>
                <select name="id_destinasi" class="form-select">
                    <?php while ($d = mysqli_fetch_assoc($destinasi)) { ?>
                        <option value="<?= $d['id']; ?>" <?= ($d['id'] == $data['id_destinasi']) ? "selected" : ""; ?>>
                            <?= $d['nama']; ?> (Rp <?= number_format($d['harga'], 0, ',', '.'); ?>)
                        </option>
                    <?php } ?>
                </select>
            </div>

            <!-- Input Nama Pemesan -->
            <div class="mb-3">
                <label class="form-label">Nama Pemesan</label>
                <input type="text" name="nama_pemesan" class="form-control" value="<?= $data['nama_pemesan']; ?>" required>
            </div>

            <!-- Input Email -->
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= $data['email']; ?>" required>
            </div>

            <!-- Input Tanggal Kunjungan -->
            <div class="mb-3">
                <label class="form-label">Tanggal Kunjungan</label>
                <input type="date" name="tanggal_kunjungan" class="form-control" value="<?= $data['tanggal_kunjungan']; ?>" required>
            </div>

            <!-- Input Jumlah Orang -->
            <div class="mb-3">
                <label class="form-label">Jumlah Orang</label>
                <input type="number" name="jumlah_orang" class="form-control" value="<?= $data['jumlah_orang']; ?>" required min="1">
            </div>

            <!-- Pilihan Metode Pembayaran -->
            <div class="mb-3">
                <label class="form-label">Metode Pembayaran</label>
                <select name="metode_pembayaran" class="form-select">
                    <?php
                    $metode = ["Transfer Bank", "QRIS", "Virtual Account", "Kartu Kredit"];
                    foreach ($metode as $m) { ?>
                        <option <?= ($m == $data['metode_pembayaran']) ? "selected" : ""; ?>>
                            <?= $m ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <!-- Input Catatan -->
            <div class="mb-3">
                <label class="form-label">Catatan</label>
                <textarea name="catatan" class="form-control" rows="4"><?= $data['catatan']; ?></textarea>
            </div>

            <!-- Pilihan Status Booking -->
            <div class="mb-3">
                <label class="form-label">Status Booking</label>
                <select name="status" class="form-select">
                    <option value="Menunggu" <?= $data['status'] == 'Menunggu' ? 'selected' : ''; ?>>Menunggu</option>
                    <option value="Dikonfirmasi" <?= $data['status'] == 'Dikonfirmasi' ? 'selected' : ''; ?>>Dikonfirmasi</option>
                    <option value="Selesai" <?= $data['status'] == 'Selesai' ? 'selected' : ''; ?>>Selesai</option>
                    <option value="Dibatalkan" <?= $data['status'] == 'Dibatalkan' ? 'selected' : ''; ?>>Dibatalkan</option>
                </select>
            </div>

            <!-- Tombol Aksi -->
            <button type="submit" name="update" class="btn btn-warning">Update</button>
            <a href="index.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</body>
</html>