<?php
/**
 * Admin - Utility debug untuk memeriksa path/foto destinasi.
 */
include "../../includes/koneksi.php";

$result = mysqli_query($koneksi, "SELECT id, nama, foto FROM destinasi ORDER BY id");

echo "<h3>Cek Foto di Database vs Folder</h3>";
echo "<table border='1' cellpadding='8' style='border-collapse:collapse;font-family:monospace'>";
echo "<tr style='background:#333;color:white'><th>ID</th><th>Nama</th><th>Foto di DB</th><th>File Exists?</th><th>Path Absolut</th></tr>";

while ($row = mysqli_fetch_assoc($result)) {
    $foto = $row['foto'];
    $path_abs = realpath(__DIR__ . "/../../image/" . $foto);
    $exists = file_exists(__DIR__ . "/../../image/" . $foto);
    $bg = $exists ? '#d4edda' : '#f8d7da';
    echo "<tr style='background:{$bg}'>";
    echo "<td>{$row['id']}</td>";
    echo "<td>{$row['nama']}</td>";
    echo "<td>" . htmlspecialchars($foto) . "</td>";
    echo "<td>" . ($exists ? "✅ ADA" : "❌ TIDAK ADA") . "</td>";
    echo "<td>" . ($path_abs ? htmlspecialchars($path_abs) : "- not found -") . "</td>";
    echo "</tr>";
}
echo "</table>";

echo "<br><h3>Semua File di Folder /image/</h3><ul style='font-family:monospace'>";
$files = scandir(__DIR__ . "/../../image/");
foreach ($files as $f) {
    if ($f !== '.' && $f !== '..') {
        echo "<li>" . htmlspecialchars($f) . "</li>";
    }
}
echo "</ul>";
?>
