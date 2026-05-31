<?php
require_once __DIR__ . '/functions.php';
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-success sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>peserta/dashboard.php">
            <i class="bi bi-mortarboard-fill"></i> <?= APP_NAME ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarPeserta">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarPeserta">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>peserta/dashboard.php"><i class="bi bi-house"></i> Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>peserta/tryout_list.php"><i class="bi bi-pencil-square"></i> Try-Out</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>peserta/mini_tryout.php"><i class="bi bi-lightning-charge"></i> Mini</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>peserta/latihan_topik.php"><i class="bi bi-journal-check"></i> Latihan</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>peserta/belajar.php"><i class="bi bi-book"></i> Belajar</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>peserta/flashcard.php"><i class="bi bi-card-text"></i> Flashcard</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>peserta/rapor.php"><i class="bi bi-file-earmark-bar-graph"></i> Rapor</a></li>
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
