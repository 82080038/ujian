<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/functions.php';

http_response_code(404);
$pageTitle = 'Halaman Tidak Ditemukan - ' . APP_NAME;
require_once __DIR__ . '/includes/header.php';
?>

<div class="d-flex flex-column min-vh-100 justify-content-center align-items-center text-center py-5">
    <div class="display-1 text-muted mb-3"><i class="bi bi-exclamation-circle"></i></div>
    <h1 class="fw-bold">404</h1>
    <h4 class="text-muted mb-3">Halaman Tidak Ditemukan</h4>
    <p class="text-muted mb-4">Maaf, halaman yang Anda cari tidak tersedia atau telah dipindahkan.</p>
    <div class="d-flex gap-2">
        <a href="<?= BASE_URL ?>" class="btn btn-primary"><i class="bi bi-house"></i> Beranda</a>
        <a href="javascript:history.back()" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
