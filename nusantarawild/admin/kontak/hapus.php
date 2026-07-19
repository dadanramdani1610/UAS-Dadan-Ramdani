<?php
/**
 * Admin - Script hapus data kontak berdasarkan id.
 */
include "../../includes/koneksi.php";

$id = $_GET['id'];

mysqli_query($koneksi,"
DELETE FROM kontak
WHERE id_kontak='$id'
");

echo "
<script>
alert('Pesan berhasil dihapus');
window.location='index.php';
</script>
";
?>