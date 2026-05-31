<?php
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

$pageTitle = 'Dashboard Admin - ' . APP_NAME;

// Statistik
$stats = [];
$res = $conn->query('SELECT COUNT(*) as total FROM users WHERE role = "peserta"');
$stats['total_peserta'] = $res->fetch_assoc()['total'];

$res = $conn->query('SELECT COUNT(*) as total FROM soal');
$stats['total_soal'] = $res->fetch_assoc()['total'];

$res = $conn->query('SELECT COUNT(*) as total FROM paket_ujian');
$stats['total_paket'] = $res->fetch_assoc()['total'];

$res = $conn->query('SELECT COUNT(*) as total FROM materi');
$stats['total_materi'] = $res->fetch_assoc()['total'];

$res = $conn->query('SELECT COUNT(*) as total FROM hasil_ujian');
$stats['total_hasil'] = $res->fetch_assoc()['total'];

// Hasil ujian terbaru
$hasil = $conn->query('SELECT h.*, u.nama, p.nama_paket FROM hasil_ujian h JOIN users u ON h.user_id = u.id JOIN paket_ujian p ON h.paket_ujian_id = p.id ORDER BY h.created_at DESC LIMIT 10');

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar_admin.php';
?>

<div class="container py-4">
    <h3 class="fw-bold mb-4"><i class="bi bi-speedometer2"></i> Dashboard Admin</h3>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4 col-lg">
            <div class="card text-white bg-primary">
                <div class="card-body text-center">
                    <i class="bi bi-people fs-1"></i>
                    <h5 class="card-title mt-2"><?= $stats['total_peserta'] ?> / <?= MAX_PESERTA ?></h5>
                    <small>Peserta</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg">
            <div class="card text-white bg-success">
                <div class="card-body text-center">
                    <i class="bi bi-journal-text fs-1"></i>
                    <h5 class="card-title mt-2"><?= $stats['total_soal'] ?></h5>
                    <small>Soal</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg">
            <div class="card text-white bg-info">
                <div class="card-body text-center">
                    <i class="bi bi-calendar-check fs-1"></i>
                    <h5 class="card-title mt-2"><?= $stats['total_paket'] ?></h5>
                    <small>Paket Ujian</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg">
            <div class="card text-white bg-warning">
                <div class="card-body text-center">
                    <i class="bi bi-book fs-1"></i>
                    <h5 class="card-title mt-2"><?= $stats['total_materi'] ?></h5>
                    <small>Materi</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg">
            <div class="card text-white bg-danger">
                <div class="card-body text-center">
                    <i class="bi bi-file-earmark-bar-graph fs-1"></i>
                    <h5 class="card-title mt-2"><?= $stats['total_hasil'] ?></h5>
                    <small>Hasil Ujian</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-white fw-bold"><i class="bi bi-clock-history"></i> Hasil Ujian Terbaru</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Peserta</th>
                            <th>Paket</th>
                            <th>TWK</th>
                            <th>TIU</th>
                            <th>TKP</th>
                            <th>Kumulatif</th>
                            <th>Status</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($h = $hasil->fetch_assoc()): ?>
                        <tr>
                            <td><?= e($h['nama']) ?></td>
                            <td><?= e($h['nama_paket']) ?></td>
                            <td><?= $h['skor_twk'] ?></td>
                            <td><?= $h['skor_tiu'] ?></td>
                            <td><?= $h['skor_tkp'] ?></td>
                            <td class="fw-bold"><?= $h['skor_kumulatif'] ?></td>
                            <td>
                                <?php if ($h['status_lulus'] === 'lulus'): ?>
                                    <span class="badge bg-success">LULUS</span>
                                <?php elseif ($h['status_lulus'] === 'gugur'): ?>
                                    <span class="badge bg-danger">GUGUR</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">PROSES</span>
                                <?php endif; ?>
                            </td>
                            <td><small><?= formatTanggal($h['created_at']) ?></small></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
