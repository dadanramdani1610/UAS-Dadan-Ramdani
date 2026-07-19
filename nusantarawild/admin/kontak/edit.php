<?php
/**
 * Admin - Form edit data kontak/sosial media.
 */
// === KONEKSI DATABASE ===
// Memasukkan file koneksi untuk menghubungkan ke database
include "../../includes/koneksi.php";

// === MENGAMBIL ID DARI URL ===
// Mendapatkan ID kontak dari parameter URL
$id = $_GET['id'];

// === QUERY: AMBIL DATA KONTAK ===
// Mengambil data spesifik pesan yang akan diedit berdasarkan ID-nya
$data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM kontak WHERE id_kontak='$id'"));

// === PROSES UPDATE DATA (JIKA FORM DISUBMIT) ===
if(isset($_POST['update'])){
    
    // Mencegah SQL Injection dengan membersihkan input
    $nama     = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email    = mysqli_real_escape_string($koneksi, $_POST['email']);
    $subjek   = mysqli_real_escape_string($koneksi, $_POST['subjek']);
    $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $pesan    = mysqli_real_escape_string($koneksi, $_POST['pesan']);
    $no_hp    = mysqli_real_escape_string($koneksi, $_POST['no_hp']);

    // Query untuk memperbarui data kontak yang ada di tabel 'kontak'
    mysqli_query($koneksi,"
        UPDATE kontak SET
            nama='$nama',
            email='$email',
            no_hp='$no_hp',
            subjek='$subjek',
            kategori='$kategori',
            pesan='$pesan'
        WHERE id_kontak='$id'
    ");

    // Menampilkan alert sukses dan kembali ke halaman index
    echo "
    <script>
        alert('Data berhasil diupdate');
        window.location='index.php';
    </script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pesan</title>

    <!-- Memuat Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Memuat Ikon Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <!-- Styling Kustom -->
    <link rel="stylesheet" href="../../style/css/style.css">
</head>
<body class="admin-page">

    <!-- Container Utama Form -->
    <div class="card shadow admin-kontak-card">
        
        <!-- Header Card -->
        <div class="card-header bg-warning">
            <h4 class="mb-0">
                <i class="bi bi-pencil-square"></i> Edit Pesan
            </h4>
        </div>

        <!-- Body Card -->
        <div class="card-body">
            <form method="POST">
                
                <!-- Input Nama -->
                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($data['nama']); ?>" required>
                </div>

                <!-- Input Email -->
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($data['email']); ?>" required>
                </div>

                <!-- Input No HP -->
                <div class="mb-3">
                    <label class="form-label">No HP</label>
                    <input type="text" name="no_hp" class="form-control" value="<?= htmlspecialchars($data['no_hp']); ?>" required>
                </div>

                <!-- Input Subjek -->
                <div class="mb-3">
                    <label class="form-label">Subjek</label>
                    <input type="text" name="subjek" class="form-control" value="<?= htmlspecialchars($data['subjek']); ?>" required>
                </div>

                <!-- Pilihan Kategori -->
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="kategori" class="form-select" required>
                        <option <?= ($data['kategori'] == "Informasi Destinasi") ? "selected" : ""; ?>>Informasi Destinasi</option>
                        <option <?= ($data['kategori'] == "Pemesanan & Tiket") ? "selected" : ""; ?>>Pemesanan & Tiket</option>
                        <option <?= ($data['kategori'] == "Kemitraan") ? "selected" : ""; ?>>Kemitraan</option>
                        <option <?= ($data['kategori'] == "Saran & Masukan") ? "selected" : ""; ?>>Saran & Masukan</option>
                        <option <?= ($data['kategori'] == "Lainnya") ? "selected" : ""; ?>>Lainnya</option>
                    </select>
                </div>

                <!-- Input Area Pesan -->
                <div class="mb-3">
                    <label class="form-label">Pesan</label>
                    <textarea name="pesan" rows="6" class="form-control" required><?= htmlspecialchars($data['pesan']); ?></textarea>
                </div>

                <!-- Tombol Aksi -->
                <div class="d-flex gap-2">
                    <button type="submit" name="update" class="btn btn-warning">
                        <i class="bi bi-save"></i> Update
                    </button>
                    <a href="index.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>

            </form>
        </div>
    </div>

</body>
</html>