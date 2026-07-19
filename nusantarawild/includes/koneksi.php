    <?php
/**
 * File koneksi ke database MySQL menggunakan mysqli.
 */

$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_nusantarawild";

$koneksi = mysqli_connect(
    $host,
    $user,
    $pass,
    $db
);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>