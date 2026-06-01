<?php
require_once __DIR__ . '/../includes/functions.php';
requirePeserta();

$pageTitle = 'Leaderboard - ' . APP_NAME;
$user_id = $_SESSION['user_id'];

// Peringkat internal (max 10 peserta) berdasarkan rata-rata skor kumulatif
$leaderboard = $conn->query("SELECT u.id, u.nama, u.target_ujian, ROUND(AVG(h.skor_kumulatif),1) as rata_skor, COUNT(h.id) as total_ujian FROM users u LEFT JOIN hasil_ujian h ON u.id = h.user_id AND h.status_lulus != 'proses' WHERE u.role = 'peserta' GROUP BY u.id ORDER BY rata_skor DESC LIMIT 10");

// Cek apakah user sendiri ada di top 10
$inTop10 = false;
$top10Ids = [];
$leaderboard->data_seek(0);
while ($r = $leaderboard->fetch_assoc()) { $top10Ids[] = $r['id']; }
$inTop10 = in_array($user_id, $top10Ids);

// Jika user tidak di top 10, ambil data ranking sendiri
$myRank = null;
if (!$inTop10) {
    $stmtMe = $conn->prepare("SELECT u.nama, u.target_ujian, ROUND(AVG(h.skor_kumulatif),1) as rata_skor, COUNT(h.id) as total_ujian FROM users u LEFT JOIN hasil_ujian h ON u.id = h.user_id AND h.status_lulus != 'proses' WHERE u.role = 'peserta' AND u.id = ? GROUP BY u.id");
    $stmtMe->bind_param('i', $user_id);
    $stmtMe->execute();
    $myData = $stmtMe->get_result()->fetch_assoc();
    if ($myData) {
        // Hitung posisi ranking
        $stmtRank = $conn->prepare("SELECT COUNT(*) + 1 as pos FROM (SELECT u.id, ROUND(AVG(h.skor_kumulatif),1) as rata_skor FROM users u LEFT JOIN hasil_ujian h ON u.id = h.user_id AND h.status_lulus != 'proses' WHERE u.role = 'peserta' GROUP BY u.id HAVING rata_skor > (SELECT COALESCE(ROUND(AVG(h2.skor_kumulatif),1),0) FROM hasil_ujian h2 WHERE h2.user_id = ? AND h2.status_lulus != 'proses')) t");
        $stmtRank->bind_param('i', $user_id);
        $stmtRank->execute();
        $myRank = $stmtRank->get_result()->fetch_assoc()['pos'] ?? '-';
    }
}

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar_peserta.php';
?>

<div class="container py-4">
    <h4 class="fw-bold mb-3"><i class="bi bi-trophy text-warning"></i> Leaderboard</h4>
    <p class="text-muted small">Peringkat peserta berdasarkan rata-rata skor kumulatif.</p>

    <?php if ($myData && !$inTop10): ?>
    <div class="card border-primary mb-3">
        <div class="card-body d-flex align-items-center">
            <div class="me-3 text-center" style="width:50px;">
                <span class="fw-bold text-primary fs-5">#<?= $myRank ?></span>
            </div>
            <div class="flex-grow-1">
                <div class="fw-bold"><?= e($myData['nama']) ?> <span class="badge bg-primary">Anda</span></div>
                <small class="text-muted"><?= strtoupper($myData['target_ujian']) ?> - <?= $myData['total_ujian'] ?> ujian</small>
            </div>
            <div class="text-end">
                <div class="fw-bold text-primary"><?= $myData['rata_skor'] ?? 0 ?></div>
                <small class="text-muted">rata-rata</small>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="card">
        <div class="list-group list-group-flush">
            <?php $rank = 1; $leaderboard->data_seek(0); while ($row = $leaderboard->fetch_assoc()): ?>
            <div class="list-group-item d-flex align-items-center <?= $row['id'] == $user_id ? 'bg-primary-subtle' : '' ?>">
                <div class="me-3 text-center" style="width:50px;">
                    <?php if ($rank === 1): ?>
                        <span class="badge bg-warning text-dark fs-6 rounded-pill">1</span>
                    <?php elseif ($rank === 2): ?>
                        <span class="badge bg-secondary fs-6 rounded-pill">2</span>
                    <?php elseif ($rank === 3): ?>
                        <span class="badge bg-danger fs-6 rounded-pill">3</span>
                    <?php else: ?>
                        <span class="fw-bold text-muted">#<?= $rank ?></span>
                    <?php endif; ?>
                </div>
                <div class="flex-grow-1">
                    <div class="fw-bold"><?= e($row['nama']) ?> <?= $row['id'] == $user_id ? '<span class="badge bg-primary">Anda</span>' : '' ?></div>
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
