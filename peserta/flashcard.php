<?php
require_once __DIR__ . '/../includes/functions.php';
requirePeserta();

$pageTitle = 'Flashcard - ' . APP_NAME;

// Ambil materi tipe flashcard
$materi = $conn->query("SELECT m.*, k.nama as kategori_nama FROM materi m LEFT JOIN kategori_ujian k ON m.kategori_ujian_id = k.id WHERE m.tipe = 'flashcard' ORDER BY m.jenis_tes, m.topik");

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar_peserta.php';
?>

<div class="container py-4">
    <h4 class="fw-bold mb-3"><i class="bi bi-card-text"></i> Flashcard</h4>

    <div class="row g-3">
        <?php while ($row = $materi->fetch_assoc()):
            // Parse konten: split oleh delimiter === untuk front/back
            $cards = explode('===', $row['konten_html'] ?? '');
        ?>
        <div class="col-md-6">
            <div class="card h-100 card-hover">
                <div class="card-body">
                    <span class="badge bg-<?= getJenisTesColor($row['jenis_tes']) ?>"><?= getJenisTesLabel($row['jenis_tes']) ?></span>
                    <h5 class="fw-bold mt-2"><?= e($row['judul']) ?></h5>
                    <p class="text-muted small"><?= e($row['topik']) ?> - <?= count($cards) ?> kartu</p>
                    <a href="flashcard_detail.php?id=<?= $row['id'] ?>" class="btn btn-outline-primary btn-sm w-100"><i class="bi bi-card-text"></i> Buka Flashcard</a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
        <?php if ($materi->num_rows === 0): ?>
            <div class="col-12 text-center text-muted py-5">Belum ada flashcard. Hubungi admin.</div>
        <?php endif; ?>
    </div>
</div>

<nav class="bottom-nav-mobile d-lg-none">
    <a href="dashboard.php"><i class="bi bi-house fs-4"></i> Beranda</a>
    <a href="tryout_list.php"><i class="bi bi-pencil-square fs-4"></i> Try-Out</a>
    <a href="belajar.php"><i class="bi bi-book fs-4"></i> Belajar</a>
    <a href="rapor.php"><i class="bi bi-file-earmark-bar-graph fs-4"></i> Rapor</a>
</nav>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
