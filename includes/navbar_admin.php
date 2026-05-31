<?php
require_once __DIR__ . '/functions.php';
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>admin/dashboard.php">
            <i class="bi bi-mortarboard-fill"></i> <?= APP_NAME ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdmin">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarAdmin">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>admin/dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>admin/kelola_soal.php"><i class="bi bi-journal-text"></i> Soal</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>admin/kelola_materi.php"><i class="bi bi-book"></i> Materi</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>admin/kelola_paket.php"><i class="bi bi-calendar-check"></i> Paket Ujian</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>admin/laporan.php"><i class="bi bi-graph-up"></i> Laporan</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>admin/analisis_butir.php"><i class="bi bi-clipboard-data"></i> Butir Soal</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>admin/catatan_pengajar.php"><i class="bi bi-sticky"></i> Catatan</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>admin/jawab_forum.php"><i class="bi bi-chat-square-text"></i> Forum</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>admin/export.php"><i class="bi bi-download"></i> Export</a></li>
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
