<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/functions.php';

if (isLoggedIn()) {
    if (isAdmin()) redirect('admin/dashboard.php');
    redirect('peserta/dashboard.php');
}
$pageTitle = 'Selamat Datang - ' . APP_NAME;
require_once __DIR__ . '/includes/header.php';
?>

<div class="d-flex flex-column min-vh-100">
    <!-- Hero Section -->
    <div class="bg-primary text-white py-5">
        <div class="container text-center py-4">
            <h1 class="display-5 fw-bold mb-3"><i class="bi bi-mortarboard-fill"></i> <?= APP_NAME ?></h1>
            <p class="lead mb-4">Persiapkan diri Anda menghadapi ujian CPNS, Sekolah Kedinasan, dan seleksi PNS.<br>
            Simulasi CAT lengkap dengan analisis hasil & rekomendasi belajar personal.</p>
            <a href="login.php" class="btn btn-light btn-lg fw-bold px-5"><i class="bi bi-box-arrow-in-right"></i> Masuk</a>
            <a href="register.php" class="btn btn-outline-light btn-lg fw-bold px-5 ms-2"><i class="bi bi-person-plus"></i> Daftar</a>
        </div>
    </div>

    <!-- Features -->
    <div class="container py-5 flex-grow-1">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 text-center p-4 card-hover">
                    <div class="card-body">
                        <i class="bi bi-pencil-square text-primary display-4 mb-3"></i>
                        <h5 class="card-title fw-bold">Try-Out Realistis</h5>
                        <p class="card-text text-muted">Simulasi CAT dengan timer, navigasi soal, dan soal yang diacak seperti ujian sungguhan.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 text-center p-4 card-hover">
                    <div class="card-body">
                        <i class="bi bi-graph-up text-success display-4 mb-3"></i>
                        <h5 class="card-title fw-bold">Analisis & Rapor</h5>
                        <p class="card-text text-muted">Lihat kelemahan Anda per topik, grafik perkembangan, dan saran belajar dari sistem.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 text-center p-4 card-hover">
                    <div class="card-body">
                        <i class="bi bi-book text-warning display-4 mb-3"></i>
                        <h5 class="card-title fw-bold">Bimbel Online</h5>
                        <p class="card-text text-muted">Akses materi ajar, flashcard, rumus cepat, dan tips trik menjawab soal.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kategori Ujian -->
        <div class="mt-5">
            <h3 class="text-center fw-bold mb-4">Kategori Ujian</h3>
            <div class="row g-3 justify-content-center">
                <div class="col-6 col-md-3">
                    <div class="card text-center border-primary">
                        <div class="card-body">
                            <i class="bi bi-bank text-primary fs-1"></i>
                            <h6 class="fw-bold mt-2">CPNS</h6>
                            <small class="text-muted">SKD TWK, TIU, TKP</small>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card text-center border-success">
                        <div class="card-body">
                            <i class="bi bi-coin text-success fs-1"></i>
                            <h6 class="fw-bold mt-2">STAN</h6>
                            <small class="text-muted">Keuangan Negara</small>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card text-center border-info">
                        <div class="card-body">
                            <i class="bi bi-bar-chart-line text-info fs-1"></i>
                            <h6 class="fw-bold mt-2">STIS</h6>
                            <small class="text-muted">Statistik & Matematika</small>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card text-center border-danger">
                        <div class="card-body">
                            <i class="bi bi-shield text-danger fs-1"></i>
                            <h6 class="fw-bold mt-2">IPDN</h6>
                            <small class="text-muted">Pemerintahan Daerah</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-auto">
        <small>&copy; <?= date('Y') ?> <?= APP_NAME ?> v<?= APP_VERSION ?> | Dibangun dengan PHP Native + Bootstrap + jQuery</small>
    </footer>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
