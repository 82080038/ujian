<?php
require_once __DIR__ . '/functions.php';

function navActiveAdmin($page) {
    $current = basename($_SERVER['PHP_SELF']);
    return $current === $page ? 'active' : '';
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>admin/dashboard.php">
            <i class="bi bi-mortarboard-fill"></i> <?= APP_NAME ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAdmin">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse d-none d-lg-flex" id="navbarAdmin">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link <?= navActiveAdmin('dashboard.php') ?>" href="<?= BASE_URL ?>admin/dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link <?= navActiveAdmin('kelola_soal.php') ?>" href="<?= BASE_URL ?>admin/kelola_soal.php"><i class="bi bi-journal-text"></i> Soal</a></li>
                <li class="nav-item"><a class="nav-link <?= navActiveAdmin('kelola_materi.php') ?>" href="<?= BASE_URL ?>admin/kelola_materi.php"><i class="bi bi-book"></i> Materi</a></li>
                <li class="nav-item"><a class="nav-link <?= navActiveAdmin('kelola_paket.php') ?>" href="<?= BASE_URL ?>admin/kelola_paket.php"><i class="bi bi-calendar-check"></i> Paket Ujian</a></li>
                <li class="nav-item"><a class="nav-link <?= navActiveAdmin('laporan.php') ?>" href="<?= BASE_URL ?>admin/laporan.php"><i class="bi bi-graph-up"></i> Laporan</a></li>
                <li class="nav-item"><a class="nav-link <?= navActiveAdmin('analisis_butir.php') ?>" href="<?= BASE_URL ?>admin/analisis_butir.php"><i class="bi bi-clipboard-data"></i> Butir Soal</a></li>
                <li class="nav-item"><a class="nav-link <?= navActiveAdmin('catatan_pengajar.php') ?>" href="<?= BASE_URL ?>admin/catatan_pengajar.php"><i class="bi bi-sticky"></i> Catatan</a></li>
                <li class="nav-item"><a class="nav-link <?= navActiveAdmin('jawab_forum.php') ?>" href="<?= BASE_URL ?>admin/jawab_forum.php"><i class="bi bi-chat-square-text"></i> Forum</a></li>
                <li class="nav-item"><a class="nav-link <?= navActiveAdmin('export.php') ?>" href="<?= BASE_URL ?>admin/export.php"><i class="bi bi-download"></i> Export</a></li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i> <?= e($_SESSION['nama'] ?? 'Admin') ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Offcanvas Admin Menu -->
<div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="offcanvasAdmin" aria-labelledby="offcanvasAdminLabel">
    <div class="offcanvas-header bg-primary text-white">
        <h5 class="offcanvas-title fw-bold" id="offcanvasAdminLabel"><i class="bi bi-mortarboard-fill"></i> <?= APP_NAME ?></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
        <div class="p-3 border-bottom">
            <div class="d-flex align-items-center">
                <i class="bi bi-person-circle fs-1 me-2 text-primary"></i>
                <div>
                    <div class="fw-bold"><?= e($_SESSION['nama'] ?? 'Admin') ?></div>
                    <small class="text-muted">ADMIN</small>
                </div>
            </div>
        </div>
        <div class="list-group list-group-flush">
            <a href="<?= BASE_URL ?>admin/dashboard.php" class="list-group-item list-group-item-action <?= navActiveAdmin('dashboard.php') ?>"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
            <a href="<?= BASE_URL ?>admin/kelola_soal.php" class="list-group-item list-group-item-action <?= navActiveAdmin('kelola_soal.php') ?>"><i class="bi bi-journal-text me-2"></i> Kelola Soal</a>
            <a href="<?= BASE_URL ?>admin/kelola_materi.php" class="list-group-item list-group-item-action <?= navActiveAdmin('kelola_materi.php') ?>"><i class="bi bi-book me-2"></i> Kelola Materi</a>
            <a href="<?= BASE_URL ?>admin/kelola_paket.php" class="list-group-item list-group-item-action <?= navActiveAdmin('kelola_paket.php') ?>"><i class="bi bi-calendar-check me-2"></i> Paket Ujian</a>
            <a href="<?= BASE_URL ?>admin/laporan.php" class="list-group-item list-group-item-action <?= navActiveAdmin('laporan.php') ?>"><i class="bi bi-graph-up me-2"></i> Laporan</a>
            <a href="<?= BASE_URL ?>admin/analisis_butir.php" class="list-group-item list-group-item-action <?= navActiveAdmin('analisis_butir.php') ?>"><i class="bi bi-clipboard-data me-2"></i> Analisis Butir</a>
            <a href="<?= BASE_URL ?>admin/catatan_pengajar.php" class="list-group-item list-group-item-action <?= navActiveAdmin('catatan_pengajar.php') ?>"><i class="bi bi-sticky me-2"></i> Catatan Pengajar</a>
            <a href="<?= BASE_URL ?>admin/jawab_forum.php" class="list-group-item list-group-item-action <?= navActiveAdmin('jawab_forum.php') ?>"><i class="bi bi-chat-square-text me-2"></i> Forum Tanya</a>
            <a href="<?= BASE_URL ?>admin/export.php" class="list-group-item list-group-item-action <?= navActiveAdmin('export.php') ?>"><i class="bi bi-download me-2"></i> Export Data</a>
        </div>
        <div class="p-3 border-top mt-auto">
            <a href="<?= BASE_URL ?>logout.php" class="btn btn-outline-danger w-100"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </div>
    </div>
</div>
