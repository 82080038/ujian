<?php
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

$pageTitle = 'Analisis Butir Soal - ' . APP_NAME;

$paket_id = intval($_GET['paket_id'] ?? 0);
$paket_list = $conn->query("SELECT id, nama_paket FROM paket_ujian ORDER BY nama_paket");

$analisis = [];
if ($paket_id) {
    // Ambil semua soal dalam paket
    $sql = "SELECT s.*, k.nama as kategori_nama FROM soal s 
        LEFT JOIN kategori_ujian k ON s.kategori_ujian_id = k.id 
        WHERE s.id IN (SELECT soal_id FROM paket_soal WHERE paket_ujian_id = $paket_id) 
        ORDER BY s.jenis_tes, s.topik";
    $soal_res = $conn->query($sql);

    while ($s = $soal_res->fetch_assoc()) {
        $soal_id = $s['id'];
        // Statistik jawaban
        $stat = $conn->query("SELECT COUNT(*) as total, SUM(CASE WHEN nilai_diperoleh > 0 THEN 1 ELSE 0 END) as benar, AVG(nilai_diperoleh) as rata_nilai FROM detail_jawaban WHERE soal_id = $soal_id")->fetch_assoc();
        $total = $stat['total'] ?? 0;
        $benar = $stat['benar'] ?? 0;
        $indeks_kesukaran = $total > 0 ? round($benar / $total, 2) : 0;

        // Daya pembeda: bandingkan kelompok atas 27% vs bawah 27%
        $dp = null;
        if ($total >= 10) {
            $jumlah_atas = max(1, ceil($total * 0.27));
            $atas = $conn->query("SELECT AVG(nilai_diperoleh) as avg FROM (SELECT nilai_diperoleh FROM detail_jawaban WHERE soal_id = $soal_id ORDER BY nilai_diperoleh DESC LIMIT $jumlah_atas) t")->fetch_assoc()['avg'] ?? 0;
            $bawah = $conn->query("SELECT AVG(nilai_diperoleh) as avg FROM (SELECT nilai_diperoleh FROM detail_jawaban WHERE soal_id = $soal_id ORDER BY nilai_diperoleh ASC LIMIT $jumlah_atas) t")->fetch_assoc()['avg'] ?? 0;
            $dp = round($atas - $bawah, 2);
        }

        // Klasifikasi
        $klasifikasi = '';
        $badge = '';
        if ($indeks_kesukaran >= 0.81) { $klasifikasi = 'Sangat Mudah'; $badge = 'success'; }
        elseif ($indeks_kesukaran >= 0.61) { $klasifikasi = 'Mudah'; $badge = 'success'; }
        elseif ($indeks_kesukaran >= 0.41) { $klasifikasi = 'Sedang'; $badge = 'warning'; }
        elseif ($indeks_kesukaran >= 0.21) { $klasifikasi = 'Sulit'; $badge = 'danger'; }
        else { $klasifikasi = 'Sangat Sulit'; $badge = 'danger'; }

        $analisis[] = array_merge($s, [
            'total' => $total,
            'benar' => $benar,
            'indeks' => $indeks_kesukaran,
            'daya_pembeda' => $dp,
            'klasifikasi' => $klasifikasi,
            'badge' => $badge
        ]);
    }
}

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar_admin.php';
?>

<div class="container py-4">
    <h3 class="fw-bold mb-3"><i class="bi bi-clipboard-data"></i> Analisis Butir Soal</h3>

    <form method="GET" class="mb-3">
        <div class="row g-2">
            <div class="col-md-4">
                <select name="paket_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Pilih Paket Ujian</option>
                    <?php while ($p = $paket_list->fetch_assoc()): ?>
                        <option value="<?= $p['id'] ?>" <?= $paket_id == $p['id'] ? 'selected' : '' ?>><?= e($p['nama_paket']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
    </form>

    <?php if ($paket_id && count($analisis)): ?>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead class="table-light">
                <tr>
                    <th>ID</th><th>Jenis</th><th>Topik</th><th>Pertanyaan</th>
                    <th>Dijawab</th><th>Benar</th><th>Indeks</th><th>Klasifikasi</th><th>Daya Pembeda</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($analisis as $a): ?>
                <tr>
                    <td><?= $a['id'] ?></td>
                    <td><span class="badge bg-<?= getJenisTesColor($a['jenis_tes']) ?>"><?= getJenisTesLabel($a['jenis_tes']) ?></span></td>
                    <td><?= e($a['topik']) ?></td>
                    <td><?= substr(strip_tags($a['pertanyaan']), 0, 60) ?>...</td>
                    <td><?= $a['total'] ?></td>
                    <td><?= $a['benar'] ?></td>
                    <td><?= $a['indeks'] ?></td>
                    <td><span class="badge bg-<?= $a['badge'] ?>"><?= $a['klasifikasi'] ?></span></td>
                    <td><?= $a['daya_pembeda'] !== null ? $a['daya_pembeda'] : '-' ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php elseif ($paket_id): ?>
        <div class="alert alert-info">Belum ada data jawaban untuk paket ini.</div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
