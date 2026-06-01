<?php
require_once __DIR__ . '/../includes/functions.php';
requirePeserta();

$pageTitle = 'Dashboard Peserta - ' . APP_NAME;
$user = getUserById($conn, $_SESSION['user_id']);

// Statistik
$stmt = $conn->prepare('SELECT COUNT(*) as total, AVG(skor_kumulatif) as rata FROM hasil_ujian WHERE user_id = ? AND status_lulus != "proses"');
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$stat = $stmt->get_result()->fetch_assoc();

// Riwayat ujian terakhir
$stmt2 = $conn->prepare('SELECT h.*, p.nama_paket FROM hasil_ujian h JOIN paket_ujian p ON h.paket_ujian_id = p.id WHERE h.user_id = ? ORDER BY h.created_at DESC LIMIT 5');
$stmt2->bind_param('i', $_SESSION['user_id']);
$stmt2->execute();
$riwayat = $stmt2->get_result();

// Rekomendasi belajar terbaru
$stmt3 = $conn->prepare('SELECT rb.*, m.judul as materi_judul FROM rekomendasi_belajar rb LEFT JOIN materi m ON rb.saran_materi_id = m.id WHERE rb.user_id = ? AND rb.status = "belum_dikerjakan" ORDER BY rb.created_at DESC LIMIT 5');
$stmt3->bind_param('i', $_SESSION['user_id']);
$stmt3->execute();
$rekom = $stmt3->get_result();

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar_peserta.php';
?>

<div class="container py-4">
    <h4 class="fw-bold mb-1">Halo, <?= e($user['nama']) ?>!</h4>
    <p class="text-muted small">Target Ujian: <span class="badge bg-primary"><?= strtoupper($user['target_ujian']) ?></span></p>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4">
            <div class="card text-white bg-success card-hover">
                <div class="card-body text-center">
                    <i class="bi bi-pencil-square fs-1"></i>
                    <h5 class="card-title mt-2"><?= $stat['total'] ?? 0 ?></h5>
                    <small>Try-Out Selesai</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="card text-white bg-primary card-hover">
                <div class="card-body text-center">
                    <i class="bi bi-graph-up fs-1"></i>
                    <h5 class="card-title mt-2"><?= number_format($stat['rata'] ?? 0, 1) ?></h5>
                    <small>Rata-rata Skor</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="card text-white bg-warning card-hover">
                <div class="card-body text-center">
                    <i class="bi bi-lightbulb fs-1"></i>
                    <h5 class="card-title mt-2"><?= $rekom->num_rows ?></h5>
                    <small>Rekomendasi Belajar</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Rekomendasi -->
    <?php if ($rekom->num_rows > 0): ?>
    <div class="card mb-4 border-success rekomendasi-card">
        <div class="card-header bg-white fw-bold"><i class="bi bi-lightbulb-fill text-warning"></i> Saran Belajar untuk Anda</div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                <?php $rekom->data_seek(0); while ($r = $rekom->fetch_assoc()): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge bg-<?= getJenisTesColor($r['jenis_tes']) ?>"><?= getJenisTesLabel($r['jenis_tes']) ?></span>
                        <strong><?= e($r['topik']) ?></strong> <small class="text-muted">(Skor <?= $r['skor_persentase'] ?>%)</small>
                        <?php if ($r['materi_judul']): ?><br><small class="text-primary">Materi: <?= e($r['materi_judul']) ?></small><?php endif; ?>
                    </div>
                    <a href="belajar.php?topik=<?= urlencode($r['topik']) ?>&jenis=<?= $r['jenis_tes'] ?>" class="btn btn-sm btn-outline-success"><i class="bi bi-book"></i> Belajar</a>
                </li>
                <?php endwhile; ?>
            </ul>
        </div>
    </div>
    <?php endif; ?>

    <!-- Riwayat Ujian -->
    <div class="card">
        <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
            <span><i class="bi bi-clock-history"></i> Riwayat Ujian</span>
            <a href="tryout_list.php" class="btn btn-sm btn-success">Mulai Try-Out</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Paket</th><th>TWK</th><th>TIU</th><th>TKP</th><th>Kumulatif</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $riwayat->fetch_assoc()): ?>
                        <tr>
                            <td><a href="tryout_hasil.php?id=<?= $row['id'] ?>" class="text-decoration-none fw-semibold"><?= e($row['nama_paket']) ?></a></td>
                            <td><?= $row['skor_twk'] ?></td>
                            <td><?= $row['skor_tiu'] ?></td>
                            <td><?= $row['skor_tkp'] ?></td>
                            <td class="fw-bold"><?= $row['skor_kumulatif'] ?></td>
                            <td>
                                <?php if ($row['status_lulus'] === 'lulus'): ?>
                                    <span class="badge bg-success">LULUS</span>
                                <?php elseif ($row['status_lulus'] === 'gugur'): ?>
                                    <span class="badge bg-danger">GUGUR</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">PROSES</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        <?php if ($riwayat->num_rows === 0): ?>
                            <tr><td colspan="6" class="text-center text-muted py-4">Belum ada riwayat ujian. <a href="tryout_list.php">Mulai sekarang!</a></td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Bottom Nav Mobile -->
<nav class="bottom-nav-mobile d-lg-none">
    <a href="dashboard.php" class="active"><i class="bi bi-house fs-4"></i> Beranda</a>
    <a href="tryout_list.php"><i class="bi bi-pencil-square fs-4"></i> Try-Out</a>
    <a href="belajar.php"><i class="bi bi-book fs-4"></i> Belajar</a>
    <a href="rapor.php"><i class="bi bi-file-earmark-bar-graph fs-4"></i> Rapor</a>
</nav>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
