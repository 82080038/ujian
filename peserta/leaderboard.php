<?php
require_once __DIR__ . '/../includes/functions.php';
requirePeserta();

$pageTitle = 'Leaderboard - ' . APP_NAME;

// Peringkat internal (max 10 peserta) berdasarkan rata-rata skor kumulatif
$leaderboard = $conn->query("SELECT u.nama, u.target_ujian, ROUND(AVG(h.skor_kumulatif),1) as rata_skor, COUNT(h.id) as total_ujian FROM users u LEFT JOIN hasil_ujian h ON u.id = h.user_id AND h.status_lulus != 'proses' WHERE u.role = 'peserta' GROUP BY u.id ORDER BY rata_skor DESC LIMIT 10");

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar_peserta.php';
?>

<div class="container py-4">
    <h4 class="fw-bold mb-3"><i class="bi bi-trophy"></i> Leaderboard</h4>
    <p class="text-muted small">Peringkat peserta berdasarkan rata-rata skor kumulatif.</p>

    <div class="card">
        <div class="list-group list-group-flush">
            <?php $rank = 1; while ($row = $leaderboard->fetch_assoc()): ?>
            <div class="list-group-item d-flex align-items-center <?= $row['nama'] === ($_SESSION['nama'] ?? '') ? 'bg-primary-subtle' : '' ?>">
                <div class="me-3 text-center" style="width:40px;">
                    <?php if ($rank <= 3): ?>
                        <i class="bi bi-trophy-fill text-<?= ['warning','secondary','danger'][$rank-1] ?> fs-4"></i>
                    <?php else: ?>
                        <span class="fw-bold text-muted">#<?= $rank ?></span>
                    <?php endif; ?>
                </div>
                <div class="flex-grow-1">
                    <div class="fw-bold"><?= e($row['nama']) ?> <?= $row['nama'] === ($_SESSION['nama'] ?? '') ? '<span class="badge bg-primary">Anda</span>' : '' ?></div>
                    <small class="text-muted"><?= strtoupper($row['target_ujian']) ?> - <?= $row['total_ujian'] ?> ujian</small>
                </div>
                <div class="text-end">
                    <div class="fw-bold text-primary"><?= $row['rata_skor'] ?? 0 ?></div>
                    <small class="text-muted">rata-rata</small>
                </div>
            </div>
            <?php $rank++; endwhile; ?>
            <?php if ($leaderboard->num_rows === 0): ?>
                <div class="text-center text-muted py-5">Belum ada data ujian.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<nav class="bottom-nav-mobile d-lg-none">
    <a href="dashboard.php"><i class="bi bi-house fs-4"></i> Beranda</a>
    <a href="tryout_list.php"><i class="bi bi-pencil-square fs-4"></i> Try-Out</a>
    <a href="belajar.php"><i class="bi bi-book fs-4"></i> Belajar</a>
    <a href="rapor.php"><i class="bi bi-file-earmark-bar-graph fs-4"></i> Rapor</a>
</nav>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
