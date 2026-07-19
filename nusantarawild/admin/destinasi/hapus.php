<?php
/**
 * Admin - Script hapus data destinasi berdasarkan id.
 */
include "../../includes/koneksi.php";

$id = $_GET['id'];

mysqli_query($koneksi,
"DELETE FROM destinasi WHERE id='$id'");

header("Location:index.php");