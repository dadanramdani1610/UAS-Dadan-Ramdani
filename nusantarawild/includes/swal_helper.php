<?php
/**
 * Helper: set flash message untuk SweetAlert2
 * $type: 'success' | 'error' | 'warning' | 'info'
 */
function swal_flash(string $type, string $title, string $text = '', string $redirect = '') {
    $_SESSION['swal'] = compact('type', 'title', 'text', 'redirect');
}

/**
 * Helper: render SweetAlert2 dari session flash
 * Sisipkan di <body> setelah include SweetAlert2 CDN
 */
function swal_render(): string {
    if (empty($_SESSION['swal'])) return '';
    $s = $_SESSION['swal'];
    unset($_SESSION['swal']);
    $type     = htmlspecialchars($s['type']);
    $title    = addslashes($s['title']);
    $text     = addslashes($s['text'] ?? '');
    $redirect = htmlspecialchars($s['redirect'] ?? '');
    $then = $redirect ? "then(() => { window.location.href = '$redirect'; })" : '';
    return "
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        Swal.fire({
            icon: '$type',
            title: '$title',
            text: '$text',
            confirmButtonColor: '#198754',
            timer: " . ($redirect ? "2000" : "0") . ",
            " . ($redirect ? "timerProgressBar: true," : "") . "
            showConfirmButton: " . ($redirect ? "false" : "true") . ",
        }).$then;
    });
    </script>";
}
?>
