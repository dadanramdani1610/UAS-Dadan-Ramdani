<?php
/**
 * Kumpulan data/konfigurasi bersama yang dipakai di beberapa halaman.
 */

include 'koneksi.php';

$query = mysqli_query($koneksi,
"SELECT * FROM destinasi");

$semua_destinasi = [];

while($row = mysqli_fetch_assoc($query)){
    $semua_destinasi[] = $row;
}

function formatRupiah($angka){
    return "Rp " . number_format($angka,0,',','.');
}
?>