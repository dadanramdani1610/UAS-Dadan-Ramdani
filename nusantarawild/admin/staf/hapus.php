<?php
/**
 * Admin - Script hapus data staf berdasarkan id.
 */
include "../../includes/koneksi.php";

$id = $_GET['id'];

// Ambil data staf
$query = mysqli_query($koneksi, "
SELECT *
FROM staf
WHERE id_staf='$id'
");

$data = mysqli_fetch_assoc($query);

// Hapus file foto jika ada
if (!empty($data['foto'])) {

    $file = "../../image/" . $data['foto'];

    if (file_exists($file)) {
        unlink($file);
    }

}

// Hapus data dari database
mysqli_query($koneksi, "
DELETE FROM staf
WHERE id_staf='$id'
");

// Kembali ke halaman index
header("Location:index.php");
exit;
?>