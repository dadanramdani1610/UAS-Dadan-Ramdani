<?php
/**
 * Script logout: menghancurkan session dan mengarahkan kembali ke halaman login.
 */
session_start();
include 'includes/swal_helper.php';

session_unset();
session_destroy();

// Hapus cookie remember_email
if (isset($_COOKIE['remember_email'])) {
    setcookie('remember_email', '', time() - 3600, "/");
}
// Set flash message
swal_flash('success','Logout Berhasil','Anda telah keluar.', 'login.php');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Logout</title>
    <link rel="stylesheet" href="style/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?= swal_render() ?>
</body>
</html>
<?php
header('Location: login.php');
exit;
?>
