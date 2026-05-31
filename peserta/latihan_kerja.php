<?php
require_once __DIR__ . '/../includes/functions.php';
requirePeserta();

$jenis = $_GET['jenis'] ?? '';
$topik = $_GET['topik'] ?? '';
$jumlah = intval($_GET['jumlah'] ?? 10);

if (!$jenis || !$topik || !in_array($jenis, ['twk','tiu','tkp'])) {
    flash('error', 'Parameter tidak valid.');
    redirect('peserta/latihan_topik.php');
}

$where = "jenis_tes = '$jenis' AND topik = '" . $conn->real_escape_string($topik) . "'";
$soalRes = $conn->query("SELECT * FROM soal WHERE $where ORDER BY RAND() LIMIT $jumlah");
$soalAll = [];
while ($r = $soalRes->fetch_assoc()) $soalAll[] = $r;

if (empty($soalAll)) {
    flash('error', 'Soal tidak tersedia.');
    redirect('peserta/latihan_topik.php');
}

$_SESSION['latihan_soal'] = array_column($soalAll, 'id');
$_SESSION['latihan_jenis'] = $jenis;
$_SESSION['latihan_topik'] = $topik;

$nomor = intval($_GET['n'] ?? 1);
if ($nomor < 1) $nomor = 1;
if ($nomor > count($soalAll)) $nomor = count($soalAll);
$soalAktif = $soalAll[$nomor - 1];

$opsiList = [];
$stmt = $conn->prepare('SELECT * FROM opsi_jawaban WHERE soal_id = ? ORDER BY label');
$stmt->bind_param('i', $soalAktif['id']);
$stmt->execute();
$opsiList = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
shuffle($opsiList);

$pageTitle = 'Latihan ' . e($topik);
require_once __DIR__ . '/../includes/header.php';
?>

<body class="bg-light mode-ujian">

<div class="sticky-top bg-white border-bottom py-2">
    <div class="container d-flex justify-content-between align-items-center">
        <small class="text-muted">Latihan <?= strtoupper($jenis) ?> - <?= e($topik) ?></small>
        <span class="badge bg-<?= getJenisTesColor($jenis) ?>"><?= getJenisTesLabel($jenis) ?></span>
        <div class="small fw-bold"><?= $nomor ?> / <?= count($soalAll) ?></div>
    </div>
</div>

<div class="container py-3">
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <p class="fw-semibold fs-5"><?= nl2br(e($soalAktif['pertanyaan'])) ?></p>
            <?php if ($soalAktif['gambar_url']): ?>
                <img src="<?= BASE_URL . e($soalAktif['gambar_url']) ?>" class="img-fluid rounded mb-3" alt="Gambar Soal">
            <?php endif; ?>
            <div class="mt-3">
                <?php foreach ($opsiList as $o):
                    $isSelected = ($_SESSION['latihan_jawaban'][$soalAktif['id']] ?? null) == $o['id'];
                    $isRevealed = $_SESSION['latihan_reveal'][$soalAktif['id']] ?? false;
                    $isKunci = $o['is_kunci'];
                    $cls = '';
                    if ($isRevealed && $isKunci) $cls = 'bg-success text-white border-success';
                    elseif ($isRevealed && $isSelected && !$isKunci) $cls = 'bg-danger text-white border-danger';
                    elseif ($isSelected) $cls = 'terpilih';
                ?>
                <div class="opsi-jawaban <?= $cls ?> latihan-opsi <?= $isRevealed ? 'disabled-opsi' : '' ?>" data-soal-id="<?= $soalAktif['id'] ?>" data-opsi-id="<?= $o['id'] ?>">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-secondary me-2 fs-6"><?= $o['label'] ?></span>
                        <span><?= e($o['teks_jawaban']) ?></span>
                        <?php if ($isRevealed && $isKunci): ?><i class="bi bi-check-lg ms-auto text-white"></i><?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <?php if ($isRevealed): ?>
                <div class="mt-3 p-3 bg-light rounded">
                    <h6><i class="bi bi-book"></i> Pembahasan</h6>
                    <p><?= nl2br(e($soalAktif['pembahasan'])) ?></p>
                    <?php if ($soalAktif['tips_triks']): ?>
                        <div class="tips-box"><h6><i class="bi bi-lightbulb"></i> Tips</h6><p><?= nl2br(e($soalAktif['tips_triks'])) ?></p></div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="d-flex justify-content-between mb-3">
        <?php if ($nomor > 1): ?>
            <a href="?jenis=<?= $jenis ?>&topik=<?= urlencode($topik) ?>&n=<?= $nomor - 1 ?>" class="btn btn-outline-secondary"><i class="bi bi-chevron-left"></i></a>
        <?php else: ?><span></span><?php endif; ?>

        <?php if (!$isRevealed): ?>
            <button type="button" class="btn btn-warning reveal-btn" data-soal-id="<?= $soalAktif['id'] ?>"><i class="bi bi-eye"></i> Lihat Pembahasan</button>
        <?php endif; ?>

        <?php if ($nomor < count($soalAll)): ?>
            <a href="?jenis=<?= $jenis ?>&topik=<?= urlencode($topik) ?>&n=<?= $nomor + 1 ?>" class="btn btn-primary"><i class="bi bi-chevron-right"></i></a>
        <?php else: ?>
            <a href="latihan_topik.php" class="btn btn-success"><i class="bi bi-check-lg"></i> Selesai</a>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

<script>
$('.latihan-opsi').on('click', function() {
    if ($(this).hasClass('disabled-opsi')) return;
    const soalId = $(this).data('soal-id');
    const opsiId = $(this).data('opsi-id');
    $.post('<?= BASE_URL ?>api/simpan_jawaban_latihan.php', { soal_id: soalId, opsi_id: opsiId }, function(res) {
        location.reload();
    });
});

$('.reveal-btn').on('click', function() {
    const soalId = $(this).data('soal-id');
    $.post('<?= BASE_URL ?>api/simpan_jawaban_latihan.php', { soal_id: soalId, reveal: 1 }, function(res) {
        location.reload();
    });
});
</script>
