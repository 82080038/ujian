<?php
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

if (!isset($_GET['id'])) { redirect('admin/laporan.php?tab=peserta'); }
$peserta_id = intval($_GET['id']);

$user = getUserById($conn, $peserta_id);
if (!$user || $user['role'] !== 'peserta') { redirect('admin/laporan.php?tab=peserta'); }

// Riwayat ujian
$hasil = $conn->query("SELECT h.*, p.nama_paket FROM hasil_ujian h JOIN paket_ujian p ON h.paket_ujian_id = p.id WHERE h.user_id = $peserta_id AND h.status_lulus != 'proses' ORDER BY h.created_at DESC");

// Analisis topik
$analisis = $conn->query("SELECT * FROM (SELECT s.jenis_tes, s.topik, COUNT(*) as total, SUM(CASE WHEN dj.nilai_diperoleh > 0 THEN 1 ELSE 0 END) as benar, ROUND(AVG(dj.waktu_detik)) as rata_waktu FROM detail_jawaban dj JOIN soal s ON dj.soal_id = s.id JOIN hasil_ujian h ON dj.hasil_ujian_id = h.id WHERE h.user_id = $peserta_id AND h.status_lulus != 'proses' GROUP BY s.jenis_tes, s.topik) t ORDER BY benar/total ASC");

$pageTitle = 'Detail Peserta - ' . APP_NAME;
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar_admin.php';
?>

<div class="container py-4">
    <h4 class="fw-bold mb-3"><i class="bi bi-person"></i> Detail Peserta: <?= e($user['nama']) ?></h4>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Email</h6>
                    <p class="fw-bold"><?= e($user['email']) ?></p>
                    <h6 class="text-muted">No. HP</h6>
                    <p class="fw-bold"><?= e($user['no_hp'] ?? '-') ?></p>
                    <h6 class="text-muted">Target</h6>
                    <span class="badge bg-primary"><?= strtoupper($user['target_ujian']) ?></span>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white fw-bold"><i class="bi bi-clock-history"></i> Riwayat Ujian</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr><th>Paket</th><th>TWK</th><th>TIU</th><th>TKP</th><th>Kumulatif</th><th>Status</th></tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $hasil->fetch_assoc()): ?>
                                <tr>
                                    <td><?= e($row['nama_paket']) ?></td>
                                    <td><?= $row['skor_twk'] ?></td>
                                    <td><?= $row['skor_tiu'] ?></td>
                                    <td><?= $row['skor_tkp'] ?></td>
                                    <td class="fw-bold"><?= $row['skor_kumulatif'] ?></td>
                                    <td><span class="badge bg-<?= $row['status_lulus']==='lulus'?'success':'danger' ?>"><?= strtoupper($row['status_lulus']) ?></span></td>
                                </tr>
                                <?php endwhile; ?>
                                <?php if ($hasil->num_rows === 0): ?><tr><td colspan="6" class="text-center text-muted py-3">Belum ada ujian.</td></tr><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-white fw-bold"><i class="bi bi-search"></i> Analisis Topik</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Jenis</th><th>Topik</th><th>Soal</th><th>Benar</th><th>%</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $analisis->fetch_assoc()):
                            $pct = $row['total'] > 0 ? round(($row['benar'] / $row['total']) * 100, 1) : 0;
                            $sc = $pct >= 80 ? 'success' : ($pct >= 50 ? 'warning' : 'danger');
                        ?>
                        <tr>
                            <td><span class="badge bg-<?= getJenisTesColor($row['jenis_tes']) ?>"><?= getJenisTesLabel($row['jenis_tes']) ?></span></td>
                            <td><?= e($row['topik']) ?></td>
                            <td><?= $row['total'] ?></td>
                            <td><?= $row['benar'] ?></td>
                            <td class="fw-bold text-<?= $sc ?>"><?= $pct ?>%</td>
                            <td><span class="badge bg-<?= $sc ?>"><?= $pct >= 80 ? 'Kuat' : ($pct >= 50 ? 'Cukup' : 'Lemah') ?></span></td>
                        </tr>
                        <?php endwhile; ?>
                        <?php if ($analisis->num_rows === 0): ?><tr><td colspan="6" class="text-center text-muted py-3">Belum ada data.</td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
