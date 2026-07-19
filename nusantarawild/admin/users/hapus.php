<?php
/**
 * Admin - Script hapus user berdasarkan id.
 */

session_start();

include "../../includes/koneksi.php";

$id = $_GET['id'];

mysqli_query(
    $koneksi,
    "DELETE FROM users WHERE id='$id'"
);

header("Location: index.php");
exit;