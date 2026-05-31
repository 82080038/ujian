<?php
require_once __DIR__ . '/functions.php';

// Helper to mark active nav link
function navActive($page) {
    $current = basename($_SERVER['PHP_SELF']);
    return $current === $page ? 'active' : '';
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-success sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>peserta/dashboard.php">
            <i class="bi bi-mortarboard-fill"></i> <?= APP_NAME ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasPeserta">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse d-none d-lg-flex" id="navbarPeserta">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link <?= navActive('dashboard.php') ?>" href="<?= BASE_URL ?>peserta/dashboard.php"><i class="bi bi-house"></i> Beranda</a></li>
                <li class="nav-item"><a class="nav-link <?= navActive('tryout_list.php') ?>" href="<?= BASE_URL ?>peserta/tryout_list.php"><i class="bi bi-pencil-square"></i> Try-Out</a></li>
                <li class="nav-item"><a class="nav-link <?= navActive('mini_tryout.php') ?>" href="<?= BASE_URL ?>peserta/mini_tryout.php"><i class="bi bi-lightning-charge"></i> Mini</a></li>
                <li class="nav-item"><a class="nav-link <?= navActive('latihan_topik.php') ?>" href="<?= BASE_URL ?>peserta/latihan_topik.php"><i class="bi bi-journal-check"></i> Latihan</a></li>
                <li class="nav-item"><a class="nav-link <?= navActive('belajar.php') ?>" href="<?= BASE_URL ?>peserta/belajar.php"><i class="bi bi-book"></i> Belajar</a></li>
                <li class="nav-item"><a class="nav-link <?= navActive('flashcard.php') ?>" href="<?= BASE_URL ?>peserta/flashcard.php"><i class="bi bi-card-text"></i> Flashcard</a></li>
                <li class="nav-item"><a class="nav-link <?= navActive('psikologi.php') ?>" href="<?= BASE_URL ?>peserta/psikologi.php"><i class="bi bi-emoji-smile"></i> Psikologi</a></li>
                <li class="nav-item"><a class="nav-link <?= navActive('rapor.php') ?>" href="<?= BASE_URL ?>peserta/rapor.php"><i class="bi bi-file-earmark-bar-graph"></i> Rapor</a></li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i> <?= e($_SESSION['nama'] ?? 'Peserta') ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>peserta/profil.php"><i class="bi bi-person"></i> Profil</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>peserta/leaderboard.php"><i class="bi bi-trophy"></i> Leaderboard</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>peserta/forum.php"><i class="bi bi-chat-square-text"></i> Forum Tanya</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Offcanvas Mobile Menu -->
<div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="offcanvasPeserta" aria-labelledby="offcanvasPesertaLabel">
    <div class="offcanvas-header bg-success text-white">
        <h5 class="offcanvas-title fw-bold" id="offcanvasPesertaLabel"><i class="bi bi-mortarboard-fill"></i> <?= APP_NAME ?></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
        <div class="p-3 border-bottom">
            <div class="d-flex align-items-center">
                <i class="bi bi-person-circle fs-1 me-2 text-success"></i>
                <div>
                    <div class="fw-bold"><?= e($_SESSION['nama'] ?? 'Peserta') ?></div>
                    <small class="text-muted"><?= strtoupper($_SESSION['role'] ?? 'peserta') ?></small>
                </div>
            </div>
        </div>
        <div class="list-group list-group-flush">
            <a href="<?= BASE_URL ?>peserta/dashboard.php" class="list-group-item list-group-item-action <?= navActive('dashboard.php') ?>"><i class="bi bi-house me-2"></i> Beranda</a>
            <a href="<?= BASE_URL ?>peserta/tryout_list.php" class="list-group-item list-group-item-action <?= navActive('tryout_list.php') ?>"><i class="bi bi-pencil-square me-2"></i> Try-Out</a>
            <a href="<?= BASE_URL ?>peserta/mini_tryout.php" class="list-group-item list-group-item-action <?= navActive('mini_tryout.php') ?>"><i class="bi bi-lightning-charge me-2"></i> Mini Try-Out</a>
            <a href="<?= BASE_URL ?>peserta/latihan_topik.php" class="list-group-item list-group-item-action <?= navActive('latihan_topik.php') ?>"><i class="bi bi-journal-check me-2"></i> Latihan Soal</a>
            <a href="<?= BASE_URL ?>peserta/belajar.php" class="list-group-item list-group-item-action <?= navActive('belajar.php') ?>"><i class="bi bi-book me-2"></i> Materi Belajar</a>
            <a href="<?= BASE_URL ?>peserta/flashcard.php" class="list-group-item list-group-item-action <?= navActive('flashcard.php') ?>"><i class="bi bi-card-text me-2"></i> Flashcard</a>
            <a href="<?= BASE_URL ?>peserta/psikologi.php" class="list-group-item list-group-item-action <?= navActive('psikologi.php') ?>"><i class="bi bi-emoji-smile me-2"></i> Tes Psikologi</a>
            <a href="<?= BASE_URL ?>peserta/rapor.php" class="list-group-item list-group-item-action <?= navActive('rapor.php') ?>"><i class="bi bi-file-earmark-bar-graph me-2"></i> Rapor & Analisis</a>
            <a href="<?= BASE_URL ?>peserta/leaderboard.php" class="list-group-item list-group-item-action <?= navActive('leaderboard.php') ?>"><i class="bi bi-trophy me-2"></i> Leaderboard</a>
            <a href="<?= BASE_URL ?>peserta/forum.php" class="list-group-item list-group-item-action <?= navActive('forum.php') ?>"><i class="bi bi-chat-square-text me-2"></i> Forum Tanya</a>
            <a href="<?= BASE_URL ?>peserta/profil.php" class="list-group-item list-group-item-action <?= navActive('profil.php') ?>"><i class="bi bi-person me-2"></i> Profil Saya</a>
        </div>
        <div class="p-3 border-top mt-auto">
            <a href="<?= BASE_URL ?>logout.php" class="btn btn-outline-danger w-100"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </div>
    </div>
</div>
