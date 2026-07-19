<?php
/**
 * Admin - Form tambah data kontak/sosial media baru.
 */
// === KONEKSI DATABASE ===
// Memasukkan file koneksi untuk menghubungkan ke database
include "../../includes/koneksi.php";

// === PROSES SIMPAN DATA (JIKA FORM DISUBMIT) ===
if (isset($_POST['simpan'])) {
    
    // Mencegah SQL Injection dengan membersihkan input
    $nama     = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email    = mysqli_real_escape_string($koneksi, $_POST['email']);
    $subjek   = mysqli_real_escape_string($koneksi, $_POST['subjek']);
    $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $pesan    = mysqli_real_escape_string($koneksi, $_POST['pesan']);
    $no_hp    = mysqli_real_escape_string($koneksi, $_POST['no_hp']);

    // Query untuk menyimpan data kontak baru ke dalam tabel 'kontak'
    mysqli_query($koneksi, "
        INSERT INTO kontak (nama, email, no_hp, subjek, kategori, pesan)
        VALUES ('$nama', '$email', '$no_hp', '$subjek', '$kategori', '$pesan')
    ");

    // Menampilkan alert sukses dan kembali ke halaman index
    echo "
    <script>
        alert('Pesan berhasil ditambahkan');
        window.location='index.php';
    </script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pesan</title>

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
        <div class="card-header bg-success text-white">
            <h4 class="mb-0">
                <i class="bi bi-plus-circle"></i> Tambah Pesan
            </h4>
        </div>

        <!-- Body Card -->
        <div class="card-body">
            <form method="POST">
                
                <!-- Input Nama -->
                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-control" required placeholder="Masukkan nama Anda">
                </div>

                <!-- Input Email -->
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required placeholder="Contoh: email@domain.com">
                </div>

                <!-- Input No HP -->
                <div class="mb-3">
                    <label class="form-label">No HP</label>
                    <input type="text" name="no_hp" class="form-control" required placeholder="Contoh: 081234567890">
                </div>

                <!-- Input Subjek -->
                <div class="mb-3">
                    <label class="form-label">Subjek</label>
                    <input type="text" name="subjek" class="form-control" required placeholder="Perihal pesan ini">
                </div>

                <!-- Pilihan Kategori -->
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="kategori" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Informasi Destinasi">Informasi Destinasi</option>
                        <option value="Pemesanan & Tiket">Pemesanan & Tiket</option>
                        <option value="Kemitraan">Kemitraan</option>
                        <option value="Saran & Masukan">Saran & Masukan</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <!-- Input Area Pesan -->
                <div class="mb-3">
                    <label class="form-label">Pesan</label>
                    <textarea name="pesan" rows="6" class="form-control" required placeholder="Tuliskan pesan Anda secara detail..."></textarea>
                </div>

                <!-- Tombol Aksi -->
                <div class="d-flex gap-2">
                    <button type="submit" name="simpan" class="btn btn-success">
                        <i class="bi bi-save"></i> Simpan
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