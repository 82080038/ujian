<?php
require_once __DIR__ . '/../includes/functions.php';
requirePeserta();

$jenis = $_GET['jenis'] ?? '';
$jumlah = intval($_GET['jumlah'] ?? 20);
$topik = $_GET['topik'] ?? '';
$level = $_GET['level'] ?? '';

if (!in_array($jenis, ['twk','tiu','tkp']) || $jumlah < 1 || $jumlah > 50) {
    flash('error', 'Parameter tidak valid.');
    redirect('peserta/mini_tryout.php');
}

// Ambil nomor soal lebih awal agar bisa cek apakah sesi perlu di-reset
$nomor = intval($_GET['n'] ?? 1);
if ($nomor < 1) $nomor = 1;

// Kunci sesi unik per kombinasi parameter
$miniKey = 'mini_ids_' . md5($jenis . '_' . $topik . '_' . $level . '_' . $jumlah);
$isSesiBaru = empty($_SESSION[$miniKey])
    || ($_SESSION['mini_jenis'] ?? '') !== $jenis;

if ($isSesiBaru) {
    // Build query untuk mengambil ID saja
    $params = [$jenis];
    $types  = 's';
    $where  = "jenis_tes = ?";
    if ($topik) { $where .= " AND topik = ?"; $params[] = $topik; $types .= 's'; }
    if ($level) { $where .= " AND tingkat_kesulitan = ?"; $params[] = $level; $types .= 's'; }

    $stmt = $conn->prepare("SELECT id FROM soal WHERE $where ORDER BY RAND() LIMIT ?");
    $types .= 'i';
    $params[] = $jumlah;
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $ids = array_column($stmt->get_result()->fetch_all(MYSQLI_ASSOC), 'id');

    if (empty($ids)) {
        flash('error', 'Soal tidak tersedia untuk filter tersebut.');
        redirect('peserta/mini_tryout.php');
    }

    $_SESSION[$miniKey]       = $ids;
    $_SESSION['mini_soal']    = $ids;
    $_SESSION['mini_jenis']   = $jenis;
    $_SESSION['mini_jumlah']  = count($ids);
    $_SESSION['mini_start']   = time();
    // Bersihkan jawaban sesi lama saat mulai ulang
    if ($nomor === 1) {
        unset($_SESSION['mini_jawaban'], $_SESSION['mini_ragu']);
    }
} else {
    $ids = $_SESSION[$miniKey];
}

// Ambil soal berdasarkan ID tersimpan (urutan tetap)
$placeholders = implode(',', array_map('intval', $ids));
$soalRes = $conn->query("SELECT * FROM soal WHERE id IN ($placeholders)");
$soalById = [];
while ($r = $soalRes->fetch_assoc()) $soalById[$r['id']] = $r;
$soalAll = [];
foreach ($ids as $sid) {
    if (isset($soalById[$sid])) $soalAll[] = $soalById[$sid];
}

if (empty($soalAll)) {
    flash('error', 'Soal tidak tersedia untuk filter tersebut.');
    redirect('peserta/mini_tryout.php');
}

if ($nomor > count($soalAll)) $nomor = count($soalAll);
$soalAktif = $soalAll[$nomor - 1];

// Opsi
$opsiList = [];
$stmt = $conn->prepare('SELECT * FROM opsi_jawaban WHERE soal_id = ? ORDER BY label');
$stmt->bind_param('i', $soalAktif['id']);
$stmt->execute();
$opsiList = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
shuffle($opsiList);

$pageTitle = 'Mini Try-Out ' . strtoupper($jenis);
$bodyClass = 'bg-light mode-ujian';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="sticky-top bg-white border-bottom py-2">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <small class="text-muted">Mini Try-Out <?= strtoupper($jenis) ?></small>
                <div class="timer-box text-primary" id="timer">00:00</div>
            </div>
            <div class="text-end">
                <span class="badge bg-<?= getJenisTesColor($jenis) ?>"><?= getJenisTesLabel($jenis) ?></span>
                <div class="small fw-bold">Soal <?= $nomor ?> / <?= count($soalAll) ?></div>
            </div>
        </div>
    </div>
</div>

<div class="container py-3">
    <div class="soal-box">
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <p class="fw-semibold fs-5"><?= nl2br(e($soalAktif['pertanyaan'])) ?></p>
                <?php if ($soalAktif['gambar_url']): ?>
                    <img src="<?= BASE_URL . e($soalAktif['gambar_url']) ?>" class="img-fluid rounded mb-3" alt="Gambar Soal">
                <?php endif; ?>

                <div class="mt-3">
                    <?php foreach ($opsiList as $o):
                        $isSelected = ($_SESSION['mini_jawaban'][$soalAktif['id']] ?? null) == $o['id'];
                    ?>
                    <div class="opsi-jawaban <?= $isSelected ? 'terpilih' : '' ?> mini-opsi" data-soal-id="<?= $soalAktif['id'] ?>" data-opsi-id="<?= $o['id'] ?>">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-secondary me-2 fs-6"><?= $o['label'] ?></span>
                            <span><?= e($o['teks_jawaban']) ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="form-check mt-3">
                    <input class="form-check-input toggle-ragu-mini" type="checkbox" id="ragu-<?= $soalAktif['id'] ?>" data-soal-id="<?= $soalAktif['id'] ?>" <?= ($_SESSION['mini_ragu'][$soalAktif['id']] ?? false) ? 'checked' : '' ?>>
                    <label class="form-check-label text-muted" for="ragu-<?= $soalAktif['id'] ?>"><i class="bi bi-flag"></i> Ragu-ragu</label>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between mb-3">
            <?php if ($nomor > 1): ?>
                <a href="?jenis=<?= $jenis ?>&jumlah=<?= $jumlah ?>&topik=<?= urlencode($topik) ?>&level=<?= $level ?>&n=<?= $nomor - 1 ?>" class="btn btn-outline-secondary btn-lg"><i class="bi bi-chevron-left"></i> Sebelumnya</a>
            <?php else: ?><span></span><?php endif; ?>

            <?php if ($nomor < count($soalAll)): ?>
                <a href="?jenis=<?= $jenis ?>&jumlah=<?= $jumlah ?>&topik=<?= urlencode($topik) ?>&level=<?= $level ?>&n=<?= $nomor + 1 ?>" class="btn btn-primary btn-lg">Selanjutnya <i class="bi bi-chevron-right"></i></a>
            <?php else: ?>
                <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#modalSubmit"><i class="bi bi-check-lg"></i> Selesai</button>
            <?php endif; ?>
        </div>
        <div class="swipe-hint"><i class="bi bi-arrow-left-right"></i> Geser kiri/kanan untuk navigasi</div>
    </div>

    <div class="card mt-3">
        <div class="card-header bg-white fw-bold small"><i class="bi bi-grid"></i> Navigasi Soal</div>
        <div class="card-body">
            <div class="navigasi-soal d-flex flex-wrap gap-2">
                <?php foreach ($soalAll as $idx => $s):
                    $no = $idx + 1;
                    $status = 'belum';
                    if (isset($_SESSION['mini_jawaban'][$s['id']])) $status = ($_SESSION['mini_ragu'][$s['id']] ?? false) ? 'ragu' : 'dijawab';
                ?>
                <a href="?jenis=<?= $jenis ?>&jumlah=<?= $jumlah ?>&topik=<?= urlencode($topik) ?>&level=<?= $level ?>&n=<?= $no ?>" class="btn btn-outline-secondary btn-soal <?= $status ?> <?= $no == $nomor ? 'active border-dark' : '' ?>"><?= $no ?></a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalSubmit" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill text-warning"></i> Konfirmasi</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body"><p>Selesaikan mini try-out?</p></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a href="mini_tryout_hasil.php" class="btn btn-success fw-bold"><i class="bi bi-check-lg"></i> Ya, Submit</a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

<script>
window.CSRF_TOKEN = '<?= e(generateCSRF()) ?>';
// Simpan jawaban mini via AJAX
$('.mini-opsi').on('click', function() {
    const soalId = $(this).data('soal-id');
    const opsiId = $(this).data('opsi-id');
    $.post('<?= BASE_URL ?>api/simpan_jawaban_mini.php', { soal_id: soalId, opsi_id: opsiId, csrf_token: window.CSRF_TOKEN }, function(res) {
        location.reload();
    });
});

$('.toggle-ragu-mini').on('change', function() {
    const soalId = $(this).data('soal-id');
    const isRagu = $(this).is(':checked') ? 1 : 0;
    $.post('<?= BASE_URL ?>api/simpan_jawaban_mini.php', { soal_id: soalId, is_ragu: isRagu, csrf_token: window.CSRF_TOKEN }, function(res) {
        location.reload();
    });
});

// Timer (20 menit default untuk mini)
if (typeof startTimer === 'function') {
    startTimer(20 * 60, '#timer', null);
}
</script>
