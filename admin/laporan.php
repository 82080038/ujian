<?php
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

$pageTitle = 'Laporan - ' . APP_NAME;

$tab = $_GET['tab'] ?? 'peserta';

// Data peserta
$peserta = $conn->query("SELECT u.*, COUNT(h.id) as total_ujian, AVG(h.skor_kumulatif) as rata_skor FROM users u LEFT JOIN hasil_ujian h ON u.id = h.user_id WHERE u.role = 'peserta' GROUP BY u.id ORDER BY u.nama");

// Data butir soal
$butir = [];
if ($tab === 'butir') {
    $sql = "SELECT * FROM (
        SELECT s.*, k.nama as kategori_nama, COUNT(dj.id) as total_dijawab,
            SUM(CASE WHEN dj.nilai_diperoleh > 0 THEN 1 ELSE 0 END) as total_benar
        FROM soal s
        LEFT JOIN kategori_ujian k ON s.kategori_ujian_id = k.id
        LEFT JOIN detail_jawaban dj ON s.id = dj.soal_id
        GROUP BY s.id
    ) t ORDER BY total_benar / NULLIF(total_dijawab,0) ASC LIMIT 200";
    $butir = $conn->query($sql);
}

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar_admin.php';
?>

<div class="container py-4">
    <h3 class="fw-bold mb-3"><i class="bi bi-graph-up"></i> Laporan</h3>

    <ul class="nav nav-tabs mb-3">
        <li class="nav-item"><a class="nav-link <?= $tab === 'peserta' ? 'active' : '' ?>" href="?tab=peserta">Peserta</a></li>
        <li class="nav-item"><a class="nav-link <?= $tab === 'butir' ? 'active' : '' ?>" href="?tab=butir">Analisis Butir Soal</a></li>
    </ul>

    <?php if ($tab === 'peserta'): ?>
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr><th>Nama</th><th>Email</th><th>Target</th><th>Total Ujian</th><th>Rata-rata Skor</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $peserta->fetch_assoc()): ?>
                        <tr>
                            <td><?= e($row['nama']) ?></td>
                            <td><small><?= e($row['email']) ?></small></td>
                            <td><span class="badge bg-primary"><?= strtoupper($row['target_ujian']) ?></span></td>
                            <td><?= $row['total_ujian'] ?></td>
                            <td class="fw-bold"><?= number_format($row['rata_skor'] ?? 0, 1) ?></td>
                            <td>
                                <a href="detail_peserta.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info text-white"><i class="bi bi-eye"></i> Detail</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($tab === 'butir'): ?>
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr><th>Soal</th><th>Jenis</th><th>Topik</th><th>Dijawab</th><th>Benar</th><th>Indeks Kesukaran</th><th>Klasifikasi</th></tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $butir->fetch_assoc()):
                            $indeks = $row['total_dijawab'] > 0 ? round($row['total_benar'] / $row['total_dijawab'], 2) : 0;
                            list($label, $badge) = getTingkatKesulitanLabel($indeks);
                        ?>
                        <tr>
                            <td><?= mb_strimwidth(strip_tags($row['pertanyaan']), 0, 70, '...') ?></td>
                            <td><span class="badge bg-<?= getJenisTesColor($row['jenis_tes']) ?>"><?= getJenisTesLabel($row['jenis_tes']) ?></span></td>
                            <td><small><?= e($row['topik']) ?></small></td>
                            <td><?= $row['total_dijawab'] ?></td>
                            <td><?= $row['total_benar'] ?></td>
                            <td><?= $indeks ?></td>
                            <td><span class="badge bg-<?= $badge ?>"><?= $label ?></span></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
