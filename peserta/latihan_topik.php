<?php
require_once __DIR__ . '/../includes/functions.php';
requirePeserta();

$pageTitle = 'Latihan per Topik - ' . APP_NAME;

// Topik yang ada soalnya
$topikRes = $conn->query("SELECT jenis_tes, topik, COUNT(*) as total FROM soal GROUP BY jenis_tes, topik ORDER BY jenis_tes, topik");
$topikByJenis = [];
while ($r = $topikRes->fetch_assoc()) {
    $topikByJenis[$r['jenis_tes']][] = $r;
}

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar_peserta.php';
?>

<div class="container py-4">
    <h4 class="fw-bold mb-3"><i class="bi bi-journal-check"></i> Latihan Soal per Topik</h4>
    <p class="text-muted small">Pilih topik yang ingin Anda latihkan.</p>

    <?php foreach (['twk'=>'TWK','tiu'=>'TIU','tkp'=>'TKP'] as $k=>$v): if (empty($topikByJenis[$k])) continue; ?>
    <div class="card mb-3">
        <div class="card-header bg-<?= getJenisTesColor($k) ?> text-white fw-bold"><i class="bi bi-tag"></i> <?= $v ?></div>
        <div class="list-group list-group-flush">
            <?php foreach ($topikByJenis[$k] as $row): ?>
            <a href="latihan_kerja.php?jenis=<?= $k ?>&topik=<?= urlencode($row['topik']) ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                <div>
                    <span class="fw-semibold"><?= e($row['topik']) ?></span>
                    <small class="text-muted d-block"><?= $row['total'] ?> soal tersedia</small>
                </div>
                <span class="badge bg-<?= getJenisTesColor($k) ?> rounded-pill">Latihan <i class="bi bi-chevron-right"></i></span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<nav class="bottom-nav-mobile d-lg-none">
    <a href="dashboard.php"><i class="bi bi-house fs-4"></i> Beranda</a>
    <a href="tryout_list.php"><i class="bi bi-pencil-square fs-4"></i> Try-Out</a>
    <a href="belajar.php"><i class="bi bi-book fs-4"></i> Belajar</a>
    <a href="rapor.php"><i class="bi bi-file-earmark-bar-graph fs-4"></i> Rapor</a>
</nav>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
