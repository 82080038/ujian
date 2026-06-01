<?php
require_once __DIR__ . '/../includes/functions.php';
requirePeserta();

$pageTitle = 'Forum Tanya - ' . APP_NAME;

$user_id = $_SESSION['user_id'];

// Proses tanya
$hasil = [];
$pertanyaan_user = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tanya'])) {
    $pertanyaan_user = trim($_POST['pertanyaan'] ?? '');
    $keywords = explode(' ', strtolower(preg_replace('/[^a-zA-Z0-9\s]/', '', $pertanyaan_user)));
    $keywords = array_filter($keywords, fn($w) => strlen($w) >= 3);

    if (!empty($keywords)) {
        // 1. Cari di materi
        $matQ = "SELECT id, judul, topik, jenis_tes, konten_html, 'materi' as sumber FROM materi WHERE ";
        $conds = [];
        foreach ($keywords as $k) {
            $safe = $conn->real_escape_string($k);
            $conds[] = "(judul LIKE '%$safe%' OR topik LIKE '%$safe%' OR konten_html LIKE '%$safe%')";
        }
        $matQ .= implode(' OR ', $conds) . " LIMIT 5";
        $res = $conn->query($matQ);
        while ($r = $res->fetch_assoc()) {
            $r['relevansi'] = 3;
            $hasil[] = $r;
        }

        // 2. Cari di soal (pembahasan + tips)
        $soalQ = "SELECT id, topik, jenis_tes, pertanyaan, pembahasan, tips_triks, 'soal' as sumber FROM soal WHERE ";
        $conds = [];
        foreach ($keywords as $k) {
            $safe = $conn->real_escape_string($k);
            $conds[] = "(topik LIKE '%$safe%' OR pembahasan LIKE '%$safe%' OR tips_triks LIKE '%$safe%' OR pertanyaan LIKE '%$safe%')";
        }
        $soalQ .= implode(' OR ', $conds) . " LIMIT 5";
        $res = $conn->query($soalQ);
        while ($r = $res->fetch_assoc()) {
            $r['relevansi'] = 4;
            $hasil[] = $r;
        }

        // 3. Cari di flashcard
        $flashQ = "SELECT id, judul, topik, jenis_tes, konten_html as pembahasan, 'flashcard' as sumber FROM materi WHERE tipe='flashcard' AND (";
        $conds = [];
        foreach ($keywords as $k) {
            $safe = $conn->real_escape_string($k);
            $conds[] = "(judul LIKE '%$safe%' OR topik LIKE '%$safe%' OR konten_html LIKE '%$safe%')";
        }
        $flashQ .= implode(' OR ', $conds) . ") LIMIT 3";
        $res = $conn->query($flashQ);
        while ($r = $res->fetch_assoc()) {
            $r['relevansi'] = 2;
            $hasil[] = $r;
        }
    }

    // Jika tidak ada hasil, simpan ke tanya_admin
    if (empty($hasil)) {
        $stmt = $conn->prepare('INSERT INTO tanya_admin (user_id, pertanyaan) VALUES (?, ?)');
        $stmt->bind_param('is', $user_id, $pertanyaan_user);
        $stmt->execute();
    }
}

// Riwayat tanya user
$stmtRiw = $conn->prepare('SELECT * FROM tanya_admin WHERE user_id = ? ORDER BY created_at DESC LIMIT 20');
$stmtRiw->bind_param('i', $user_id);
$stmtRiw->execute();
$riwayat = $stmtRiw->get_result();

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar_peserta.php';
?>

<div class="container py-4">
    <h4 class="fw-bold mb-3"><i class="bi bi-chat-square-text"></i> Forum Tanya</h4>
    <p class="text-muted small">Tanyakan apa saja seputar CPNS, soal, materi, atau strategi belajar. Sistem akan mencari jawaban dari materi & pembahasan yang tersedia.</p>

    <div class="card mb-4">
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-bold">Apa yang ingin Anda tanyakan?</label>
                    <textarea name="pertanyaan" class="form-control" rows="3" required placeholder="Contoh: Bagaimana cara mengerjakan soal perbandingan berbalik nilai? Apa isi Pasal 33 UUD 1945? Tips TKP untuk soal integritas?"><?= e($pertanyaan_user) ?></textarea>
                </div>
                <button type="submit" name="tanya" class="btn btn-primary fw-bold"><i class="bi bi-search"></i> Cari Jawaban</button>
            </form>
        </div>
    </div>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <?php if (!empty($hasil)): ?>
            <h5 class="fw-bold mb-3"><i class="bi bi-lightbulb"></i> Jawaban yang Ditemukan</h5>
            <div class="row g-3">
                <?php foreach ($hasil as $h): ?>
                <div class="col-12">
                    <div class="card card-hover">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-<?= getJenisTesColor($h['jenis_tes'] ?? 'twk') ?> me-2"><?= strtoupper($h['jenis_tes'] ?? 'UMUM') ?></span>
                                <span class="badge bg-secondary"><?= ucfirst($h['sumber']) ?></span>
                            </div>
                            <h6 class="fw-bold"><?= e($h['judul'] ?? ($h['topik'] ?? 'Pembahasan')) ?></h6>
                            <div class="text-muted small">
                                <?php if ($h['sumber'] === 'soal'): ?>
                                    <p class="mb-1"><strong>Soal:</strong> <?= substr(strip_tags($h['pertanyaan']), 0, 120) ?>...</p>
                                    <?php if ($h['pembahasan']): ?>
                                        <div class="p-2 bg-light rounded mb-2"><strong>Pembahasan:</strong> <?= nl2br(e(substr($h['pembahasan'], 0, 300))) ?>...</div>
                                    <?php endif; ?>
                                    <?php if ($h['tips_triks']): ?>
                                        <div class="tips-box small"><i class="bi bi-lightbulb"></i> <strong>Tips:</strong> <?= nl2br(e($h['tips_triks'])) ?></div>
                                    <?php endif; ?>
                                <?php elseif ($h['sumber'] === 'materi'): ?>
                                    <p><?= nl2br(e(substr(strip_tags($h['konten_html']), 0, 300))) ?>...</p>
                                    <a href="belajar_detail.php?id=<?= $h['id'] ?>" class="btn btn-sm btn-outline-primary">Baca Materi Lengkap</a>
                                <?php elseif ($h['sumber'] === 'flashcard'): ?>
                                    <p><?= nl2br(e(substr(strip_tags($h['pembahasan']), 0, 300))) ?>...</p>
                                    <a href="flashcard_detail.php?id=<?= $h['id'] ?>" class="btn btn-sm btn-outline-primary">Buka Flashcard</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> <strong>Belum ditemukan jawaban otomatis.</strong> Pertanyaan Anda telah disimpan dan akan dijawab oleh pengajar secara manual.
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($riwayat->num_rows > 0): ?>
    <div class="mt-4">
        <h5 class="fw-bold mb-3"><i class="bi bi-clock-history"></i> Riwayat Tanya</h5>
        <div class="list-group">
            <?php while ($t = $riwayat->fetch_assoc()): ?>
            <div class="list-group-item">
                <div class="d-flex justify-content-between">
                    <span class="fw-semibold"><?= e($t['pertanyaan']) ?></span>
                    <small class="text-muted"><?= $t['created_at'] ?></small>
                </div>
                <?php if ($t['jawaban']): ?>
                    <div class="mt-2 p-2 bg-light rounded small"><i class="bi bi-chat-left-text text-primary"></i> <strong>Jawaban:</strong> <?= nl2br(e($t['jawaban'])) ?></div>
                <?php else: ?>
                    <span class="badge bg-warning text-dark mt-1">Menunggu jawaban pengajar</span>
                <?php endif; ?>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<nav class="bottom-nav-mobile d-lg-none">
    <a href="dashboard.php"><i class="bi bi-house fs-4"></i> Beranda</a>
    <a href="tryout_list.php"><i class="bi bi-pencil-square fs-4"></i> Try-Out</a>
    <a href="belajar.php"><i class="bi bi-book fs-4"></i> Belajar</a>
    <a href="rapor.php"><i class="bi bi-file-earmark-bar-graph fs-4"></i> Rapor</a>
</nav>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
