<?php
require_once __DIR__ . '/../includes/functions.php';
requirePeserta();

$user_id = $_SESSION['user_id'];

// Ambil hasil tes psikologi terbaru (paket_id = 0 artinya psikologi)
$stmt = $conn->prepare("SELECT * FROM hasil_ujian WHERE user_id = ? AND paket_ujian_id = 0 ORDER BY created_at DESC LIMIT 1");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$hasil = $stmt->get_result()->fetch_assoc();

if (!$hasil) {
    flash('warning', 'Belum ada hasil tes psikologi. Silakan mulai tes terlebih dahulu.');
    redirect('peserta/psikologi.php');
}

$pageTitle = 'Hasil Tes Psikologi - ' . APP_NAME;
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar_peserta.php';
?>

<div class="container py-4">
    <h4 class="fw-bold mb-3"><i class="bi bi-emoji-smile text-success"></i> Hasil Tes Psikologi</h4>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card h-100 text-center">
                <div class="card-body">
                    <div class="display-3 fw-bold text-primary"><?= $hasil['skor_kumulatif'] ?></div>
                    <p class="text-muted">Total Skor</p>
                    <span class="badge bg-<?= $hasil['status_lulus'] === 'lulus' ? 'success' : 'warning' ?> fs-6"><?= strtoupper($hasil['status_lulus']) ?></span>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="bi bi-info-circle"></i> Penjelasan</h6>
                    <p class="text-muted small">Tes psikologi mengukur berbagai aspek kepribadian dan ketahanan kerja. Skor ini adalah hasil dari tes Wartegg, EPPS, atau Kraepelin yang telah Anda kerjakan.</p>
                    <ul class="text-muted small mb-0">
                        <li><strong>Wartegg:</strong> Daya imaginasi & logika visual</li>
                        <li><strong>EPPS:</strong> Motivasi & kebutuhan psikologis</li>
                        <li><strong>Kraepelin:</strong> Ketahanan kerja & konsentrasi</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-white fw-bold"><i class="bi bi-clock-history"></i> Riwayat Tes Psikologi</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Tanggal</th><th>Skor</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <?php
                        $riwayat = $conn->query("SELECT * FROM hasil_ujian WHERE user_id = $user_id AND paket_ujian_id = 0 ORDER BY created_at DESC LIMIT 10");
                        while ($r = $riwayat->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?= formatTanggal($r['created_at']) ?></td>
                            <td class="fw-bold"><?= $r['skor_kumulatif'] ?></td>
                            <td><span class="badge bg-<?= $r['status_lulus'] === 'lulus' ? 'success' : 'warning' ?>"><?= strtoupper($r['status_lulus']) ?></span></td>
                        </tr>
                        <?php endwhile; ?>
                        <?php if ($riwayat->num_rows === 0): ?>
                            <tr><td colspan="3" class="text-center text-muted py-3">Belum ada riwayat.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2">
        <a href="psikologi.php" class="btn btn-success"><i class="bi bi-arrow-repeat"></i> Tes Lagi</a>
        <a href="dashboard.php" class="btn btn-secondary"><i class="bi bi-house"></i> Dashboard</a>
    </div>
</div>

<nav class="bottom-nav-mobile d-lg-none">
    <a href="dashboard.php"><i class="bi bi-house fs-4"></i> Beranda</a>
    <a href="tryout_list.php"><i class="bi bi-pencil-square fs-4"></i> Try-Out</a>
    <a href="belajar.php"><i class="bi bi-book fs-4"></i> Belajar</a>
    <a href="rapor.php"><i class="bi bi-file-earmark-bar-graph fs-4"></i> Rapor</a>
</nav>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
