<?php
/**
 * Admin - Form tambah data booking baru.
 */
include "../../includes/koneksi.php";

// Ambil data destinasi
$destinasi = mysqli_query($koneksi, "SELECT * FROM destinasi");
$kode_booking = "NW" . strtoupper(substr(md5(uniqid()), 0, 6));
if (isset($_POST['simpan'])) {

    $id_destinasi = $_POST['id_destinasi'];
    $id_user = !empty($_POST['id_user']) ? $_POST['id_user'] : NULL;
    $nama = $_POST['nama_pemesan'];
    $email = $_POST['email'];
    $tanggal = $_POST['tanggal_kunjungan'];
    $jumlah = $_POST['jumlah_orang'];
    $metode = $_POST['metode_pembayaran'];
    $catatan = $_POST['catatan'];

    // Ambil harga destinasi
    $q = mysqli_query(
        $koneksi,
        "SELECT harga,lokasi FROM destinasi WHERE id='$id_destinasi'"
    );

    $d = mysqli_fetch_assoc($q);

    $lokasi = $d['lokasi'];

    $total = $d['harga'] * $jumlah;

    mysqli_query($koneksi, "
    INSERT INTO booking
    (
        kode_booking,
        id_user,
        id_destinasi,
        nama_pemesan,
        email,
        tanggal_kunjungan,
        jumlah_orang,
        metode_pembayaran,
        catatan,
        total_harga
        status  
    )
    VALUES
    (
        '$kode_booking',
        " . ($id_user == NULL ? "NULL" : "'$id_user'") . ",
        '$id_destinasi',
        '$nama',
        '$email',
        '$tanggal',
        '$jumlah',
        '$metode',
        '$catatan',
        '$total'
        'Menunggu'
    )
    ");

    header("Location:index.php");
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Tambah Booking</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../../style/css/style.css">
</head>
<script>
    const destinasi = document.querySelector("select[name='id_destinasi']");
    const lokasi = document.getElementById("lokasi");

    destinasi.addEventListener("change", function () {

        lokasi.value =
            this.options[this.selectedIndex].dataset.lokasi;

    });
</script>

<body class="admin-page">

    <div class="container mt-5">

        <h2>Tambah Booking</h2>
        <div class="mb-3">

            <label>Kode Booking</label>

            <input type="text" class="form-control" value="<?= $kode_booking ?>" readonly>

        </div>

        <form method="POST">

            <div class="mb-3">
                <label>ID User (Kosongkan jika tamu)</label>
                <input type="number" name="id_user" class="form-control">
            </div>

            <div class="mb-3">

                <label>Destinasi</label>

                <select name="id_destinasi" class="form-control" required>

                    <option value="">-- Pilih Destinasi --</option>

                    <?php while ($d = mysqli_fetch_assoc($destinasi)) { ?>

                        <option value="<?= $d['id']; ?>" data-lokasi="<?= $d['lokasi']; ?>">
                            <?= $d['nama']; ?>
                            (Rp <?= number_format($d['harga'], 0, ',', '.'); ?>)
                        </option>

                    <?php } ?>

                </select>

            </div>
            <div class="mb-3">

                <label>Lokasi</label>

                <input type="text" id="lokasi" class="form-control" placeholder="Lokasi akan tampil otomatis" readonly>

            </div>

            <div class="mb-3">

                <label>Nama Pemesan</label>

                <input type="text" name="nama_pemesan" class="form-control" required>

            </div>

            <div class="mb-3">

                <label>Email</label>

                <input type="email" name="email" class="form-control" required>

            </div>

            <div class="mb-3">

                <label>Tanggal Kunjungan</label>

                <input type="date" name="tanggal_kunjungan" class="form-control" required>

            </div>

            <div class="mb-3">

                <label>Jumlah Orang</label>

                <input type="number" name="jumlah_orang" class="form-control" value="1" required>

            </div>

            <div class="mb-3">

                <label>Metode Pembayaran</label>

                <select name="metode_pembayaran" class="form-control">

                    <option>Transfer Bank</option>
                    <option>QRIS</option>
                    <option>Virtual Account</option>
                    <option>Kartu Kredit</option>

                </select>

            </div>

            <div class="mb-3">

                <label>Catatan</label>

                <textarea name="catatan" class="form-control" rows="3"></textarea>

            </div>

            <button type="submit" name="simpan" class="btn btn-success">

                Simpan

            </button>

            <a href="index.php" class="btn btn-secondary">

                Kembali

            </a>

        </form>

    </div>

</body>

</html>