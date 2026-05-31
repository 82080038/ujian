<?php
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

$pageTitle = 'Export Data - ' . APP_NAME;

$tab = $_GET['tab'] ?? 'peserta';
$format = $_GET['format'] ?? 'csv';

if ($format === 'csv' && isset($_GET['download'])) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="tryoutku_' . $tab . '_' . date('Ymd') . '.csv"');
    $output = fopen('php://output', 'w');
    fprintf($output, "\xEF\xBB\xBF"); // BOM for Excel

    if ($tab === 'peserta') {
        fputcsv($output, ['Nama','Email','No HP','Target','Total Ujian','Rata-rata Skor']);
        $res = $conn->query("SELECT u.nama, u.email, u.no_hp, u.target_ujian, COUNT(h.id) as total, ROUND(AVG(h.skor_kumulatif),1) as rata FROM users u LEFT JOIN hasil_ujian h ON u.id=h.user_id WHERE u.role='peserta' GROUP BY u.id ORDER BY u.nama");
        while ($r = $res->fetch_assoc()) fputcsv($output, [$r['nama'], $r['email'], $r['no_hp'], $r['target_ujian'], $r['total'], $r['rata']]);
    } elseif ($tab === 'hasil') {
        fputcsv($output, ['Nama Peserta','Paket','TWK','TIU','TKP','Kumulatif','Status','Tanggal']);
        $res = $conn->query("SELECT u.nama, p.nama_paket, h.skor_twk, h.skor_tiu, h.skor_tkp, h.skor_kumulatif, h.status_lulus, h.created_at FROM hasil_ujian h JOIN users u ON h.user_id=u.id JOIN paket_ujian p ON h.paket_ujian_id=p.id ORDER BY h.created_at DESC");
        while ($r = $res->fetch_assoc()) fputcsv($output, [$r['nama'], $r['nama_paket'], $r['skor_twk'], $r['skor_tiu'], $r['skor_tkp'], $r['skor_kumulatif'], $r['status_lulus'], $r['created_at']]);
    }
    fclose($output);
    exit;
}

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar_admin.php';
?>

<div class="container py-4">
    <h3 class="fw-bold mb-3"><i class="bi bi-download"></i> Export Data</h3>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="fw-bold"><i class="bi bi-people"></i> Data Peserta</h5>
                    <p class="text-muted small">Export nama, email, target ujian, total ujian, dan rata-rata skor.</p>
                    <a href="export.php?tab=peserta&format=csv&download=1" class="btn btn-success"><i class="bi bi-file-earmark-spreadsheet"></i> Download CSV</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="fw-bold"><i class="bi bi-graph-up"></i> Hasil Ujian</h5>
                    <p class="text-muted small">Export semua hasil ujian dengan skor per subtes dan status.</p>
                    <a href="export.php?tab=hasil&format=csv&download=1" class="btn btn-success"><i class="bi bi-file-earmark-spreadsheet"></i> Download CSV</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
