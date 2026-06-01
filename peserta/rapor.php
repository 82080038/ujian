<?php
require_once __DIR__ . '/../includes/functions.php';
requirePeserta();

$pageTitle = 'Rapor - ' . APP_NAME;
$user_id = $_SESSION['user_id'];

// Riwayat skor untuk grafik
$stmtR = $conn->prepare('SELECT h.*, p.nama_paket FROM hasil_ujian h JOIN paket_ujian p ON h.paket_ujian_id = p.id WHERE h.user_id = ? AND h.status_lulus != "proses" ORDER BY h.created_at DESC LIMIT 10');
$stmtR->bind_param('i', $user_id);
$stmtR->execute();
$riwayat = $stmtR->get_result();

// Analisis topik (strength/weakness) - ambil dari semua ujian
$stmtA = $conn->prepare("SELECT * FROM (SELECT s.jenis_tes, s.topik, COUNT(*) as total_soal, SUM(CASE WHEN dj.nilai_diperoleh > 0 THEN 1 ELSE 0 END) as benar, ROUND(AVG(dj.waktu_detik)) as rata_waktu FROM detail_jawaban dj JOIN soal s ON dj.soal_id = s.id JOIN hasil_ujian h ON dj.hasil_ujian_id = h.id WHERE h.user_id = ? AND h.status_lulus != 'proses' GROUP BY s.jenis_tes, s.topik) t ORDER BY benar / total_soal ASC");
$stmtA->bind_param('i', $user_id);
$stmtA->execute();
$analisis = $stmtA->get_result();

// Rekomendasi aktif
$stmtRek = $conn->prepare("SELECT rb.*, m.judul as materi_judul FROM rekomendasi_belajar rb LEFT JOIN materi m ON rb.saran_materi_id = m.id WHERE rb.user_id = ? AND rb.status = 'belum_dikerjakan' ORDER BY rb.created_at DESC");
$stmtRek->bind_param('i', $user_id);
$stmtRek->execute();
$rekom = $stmtRek->get_result();

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar_peserta.php';
?>

<div class="container py-4">
    <h4 class="fw-bold mb-3"><i class="bi bi-file-earmark-bar-graph"></i> Rapor & Analisis</h4>

    <!-- Grafik Riwayat Skor -->
    <div class="card mb-4">
        <div class="card-header bg-white fw-bold"><i class="bi bi-graph-up"></i> Riwayat Skor</div>
        <div class="card-body">
            <canvas id="grafikSkor" height="200"></canvas>
        </div>
    </div>

    <!-- Grafik Radar -->
    <div class="card mb-4">
        <div class="card-header bg-white fw-bold"><i class="bi bi-bullseye"></i> Profil Kemampuan (Radar)</div>
        <div class="card-body text-center">
            <canvas id="grafikRadar" style="max-height:350px;"></canvas>
        </div>
    </div>

    <!-- Analisis Topik -->
    <div class="card mb-4">
        <div class="card-header bg-white fw-bold"><i class="bi bi-search"></i> Analisis Kekuatan & Kelemahan per Topik</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Jenis</th><th>Topik</th><th>Soal</th><th>Benar</th><th>%</th><th>Waktu/soal</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $analisis->fetch_assoc()):
                            $pct = $row['total_soal'] > 0 ? round(($row['benar'] / $row['total_soal']) * 100, 1) : 0;
                            $statusColor = $pct >= 80 ? 'success' : ($pct >= 50 ? 'warning' : 'danger');
                            $statusText = $pct >= 80 ? 'Kuat' : ($pct >= 50 ? 'Cukup' : 'Lemah');
                        ?>
                        <tr>
                            <td><span class="badge bg-<?= getJenisTesColor($row['jenis_tes']) ?>"><?= getJenisTesLabel($row['jenis_tes']) ?></span></td>
                            <td><?= e($row['topik']) ?></td>
                            <td><?= $row['total_soal'] ?></td>
                            <td><?= $row['benar'] ?></td>
                            <td class="fw-bold text-<?= $statusColor ?>"><?= $pct ?>%</td>
                            <td><?= $row['rata_waktu'] ?>s</td>
                            <td><span class="badge bg-<?= $statusColor ?>"><?= $statusText ?></span></td>
                        </tr>
                        <?php endwhile; ?>
                        <?php if ($analisis->num_rows === 0): ?>
                            <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data ujian. Kerjakan try-out terlebih dahulu.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Rekomendasi -->
    <div class="card border-success rekomendasi-card">
        <div class="card-header bg-white fw-bold"><i class="bi bi-lightbulb text-warning"></i> Rekomendasi Belajar Aktif</div>
        <div class="card-body">
            <?php if ($rekom->num_rows === 0): ?>
                <p class="text-muted">Tidak ada rekomendasi aktif. Kerjakan try-out untuk mendapatkan saran belajar.</p>
            <?php else: ?>
                <ul class="list-group list-group-flush">
                    <?php while ($r = $rekom->fetch_assoc()): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge bg-<?= getJenisTesColor($r['jenis_tes']) ?>"><?= getJenisTesLabel($r['jenis_tes']) ?></span>
                            <strong><?= e($r['topik']) ?></strong>
                            <small class="text-muted">(Skor <?= $r['skor_persentase'] ?>% - <?= $r['tingkat_kesulitan_rekomendasi'] ?>)</small>
                            <?php if ($r['materi_judul']): ?><br><small class="text-primary">Saran: <?= e($r['materi_judul']) ?></small><?php endif; ?>
                        </div>
                        <a href="belajar.php?topik=<?= urlencode($r['topik']) ?>&jenis=<?= $r['jenis_tes'] ?>" class="btn btn-sm btn-success"><i class="bi bi-book"></i> Belajar</a>
                    </li>
                    <?php endwhile; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('grafikSkor').getContext('2d');
const labels = [];
const dataTWK = []; const dataTIU = []; const dataTKP = []; const dataKum = [];

<?php
$riwayat->data_seek(0);
$labels = [];
while ($row = $riwayat->fetch_assoc()) {
    $labels[] = e($row['nama_paket']);
    echo "labels.push('" . e($row['nama_paket']) . "');\n";
    echo "dataTWK.push(" . $row['skor_twk'] . ");\n";
    echo "dataTIU.push(" . $row['skor_tiu'] . ");\n";
    echo "dataTKP.push(" . $row['skor_tkp'] . ");\n";
    echo "dataKum.push(" . $row['skor_kumulatif'] . ");\n";
}
?>

new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [
            { label: 'TWK', data: dataTWK, borderColor: '#0d6efd', fill: false, tension: 0.3 },
            { label: 'TIU', data: dataTIU, borderColor: '#ffc107', fill: false, tension: 0.3 },
            { label: 'TKP', data: dataTKP, borderColor: '#198754', fill: false, tension: 0.3 },
            { label: 'Kumulatif', data: dataKum, borderColor: '#dc3545', fill: false, tension: 0.3, borderWidth: 3 }
        ]
    },
    options: { responsive: true, scales: { y: { beginAtZero: true } } }
});

// Radar Chart
<?php
$stmtRad = $conn->prepare("SELECT ROUND(AVG(CASE WHEN s.jenis_tes='twk' THEN dj.nilai_diperoleh END),1) as twk, ROUND(AVG(CASE WHEN s.jenis_tes='tiu' THEN dj.nilai_diperoleh END),1) as tiu, ROUND(AVG(CASE WHEN s.jenis_tes='tkp' THEN dj.nilai_diperoleh END),1) as tkp, ROUND(AVG(CASE WHEN dj.nilai_diperoleh>0 THEN 100 ELSE 0 END),1) as ketelitian, ROUND(100 - AVG(dj.waktu_detik)/3,1) as kecepatan FROM detail_jawaban dj JOIN soal s ON dj.soal_id=s.id JOIN hasil_ujian h ON dj.hasil_ujian_id=h.id WHERE h.user_id=? AND h.status_lulus!='proses'");
$stmtRad->bind_param('i', $user_id);
$stmtRad->execute();
$rv = $stmtRad->get_result()->fetch_assoc();
?>
new Chart(document.getElementById('grafikRadar'), {
    type: 'radar',
    data: {
        labels: ['TWK', 'TIU', 'TKP', 'Ketelitian', 'Kecepatan'],
        datasets: [{
            label: 'Profil Anda',
            data: [<?= $rv['twk']??0 ?>, <?= $rv['tiu']??0 ?>, <?= $rv['tkp']??0 ?>, <?= $rv['ketelitian']??0 ?>, <?= max(0,$rv['kecepatan']??0) ?>],
            backgroundColor: 'rgba(13,110,253,0.2)',
            borderColor: '#0d6efd',
            pointBackgroundColor: '#0d6efd'
        }]
    },
    options: { responsive: true, scales: { r: { min:0, max:100, ticks:{stepSize:20} } } }
});
</script>

<nav class="bottom-nav-mobile d-lg-none">
    <a href="dashboard.php"><i class="bi bi-house fs-4"></i> Beranda</a>
    <a href="tryout_list.php"><i class="bi bi-pencil-square fs-4"></i> Try-Out</a>
    <a href="belajar.php"><i class="bi bi-book fs-4"></i> Belajar</a>
    <a href="rapor.php" class="active"><i class="bi bi-file-earmark-bar-graph fs-4"></i> Rapor</a>
</nav>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
