<?php
require_once __DIR__ . '/../includes/functions.php';
requirePeserta();

$jenis = $_GET['jenis'] ?? '';
if (!in_array($jenis, ['wartegg', 'epps'])) {
    flash('error', 'Jenis tes psikologi tidak valid.');
    redirect('peserta/psikologi.php');
}

$title = $jenis === 'wartegg' ? 'Tes Wartegg' : 'Tes EPPS';

// Ambil soal psikologi sesuai jenis (limit 20)
$stmt = $conn->prepare("SELECT s.* FROM soal s WHERE s.jenis_tes = 'psikologi' AND s.topik = ? ORDER BY RAND() LIMIT 20");
$stmt->bind_param('s', $jenis);
$stmt->execute();
$soal = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Jika belum ada soal psikologi, fallback ke soal biasa (untuk demo)
if (count($soal) < 5) {
    $stmt2 = $conn->prepare("SELECT s.* FROM soal s WHERE s.jenis_tes = 'psikologi' ORDER BY RAND() LIMIT 20");
    $stmt2->execute();
    $soal = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
}

$dalamPersiapan = count($soal) < 1;

// Ambil opsi
foreach ($soal as $k => $s) {
    $o = $conn->query("SELECT * FROM opsi_jawaban WHERE soal_id = {$s['id']} ORDER BY label");
    $soal[$k]['opsi'] = $o->fetch_all(MYSQLI_ASSOC);
}

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar_peserta.php';
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4><i class="bi <?= $jenis === 'wartegg' ? 'bi-grid-3x3 text-info' : 'bi-person-check text-warning' ?>"></i> <?= $title ?></h4>
        <?php if (!$dalamPersiapan): ?>
        <div class="badge bg-dark fs-5"><i class="bi bi-clock"></i> <span id="timer">15:00</span></div>
        <?php endif; ?>
    </div>

    <?php if ($dalamPersiapan): ?>
    <div class="text-center py-5">
        <div class="display-1 text-muted mb-3"><i class="bi bi-tools"></i></div>
        <h4 class="fw-bold text-muted">Tes <?= $title ?> Dalam Persiapan</h4>
        <p class="text-muted mb-4">Soal untuk tes ini sedang disusun oleh tim pengajar.<br>Silakan kembali lagi nanti atau hubungi admin untuk informasi lebih lanjut.</p>
        <a href="psikologi.php" class="btn btn-primary"><i class="bi bi-arrow-left"></i> Kembali ke Tes Psikologi</a>
    </div>
    <?php else: ?>

    <form id="form-psikologi" method="POST" action="<?= BASE_URL ?>api/submit_psikologi.php">
        <input type="hidden" name="jenis" value="<?= $jenis ?>">

        <?php foreach ($soal as $idx => $s): ?>
        <div class="card mb-3 soal-box" id="soal-<?= $idx ?>" style="display: <?= $idx === 0 ? 'block' : 'none' ?>;">
            <div class="card-header bg-light d-flex justify-content-between">
                <span class="fw-bold">Soal <?= $idx + 1 ?> / <?= count($soal) ?></span>
                <span class="badge bg-secondary"><?= ucfirst($jenis) ?></span>
            </div>
            <div class="card-body">
                <p class="card-text fs-5"><?= nl2br(e($s['pertanyaan'])) ?></p>
                <?php if ($s['gambar_url']): ?>
                <img src="<?= BASE_URL . e($s['gambar_url']) ?>" class="img-fluid rounded mb-3" style="max-height: 250px;">
                <?php endif; ?>

                <div class="list-group">
                    <?php foreach ($s['opsi'] as $o): ?>
                    <label class="list-group-item list-group-item-action opsi-jawaban">
                        <input class="form-check-input me-2" type="radio" name="jawaban[<?= $s['id'] ?>]" value="<?= $o['id'] ?>" required>
                        <strong><?= $o['label'] ?>.</strong> <?= e($o['teks_jawaban']) ?>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <?php if ($idx > 0): ?>
                <button type="button" class="btn btn-outline-secondary btn-nav" data-target="<?= $idx - 1 ?>"><i class="bi bi-arrow-left"></i> Sebelumnya</button>
                <?php else: ?>
                <span></span>
                <?php endif; ?>

                <?php if ($idx < count($soal) - 1): ?>
                <button type="button" class="btn btn-primary btn-nav" data-target="<?= $idx + 1 ?>">Selanjutnya <i class="bi bi-arrow-right"></i></button>
                <?php else: ?>
                <button type="submit" class="btn btn-success fw-bold"><i class="bi bi-check-lg"></i> Selesai</button>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </form>
    <?php endif; ?>
</div>

<script>
const waktuMenit = 15;
const timerEl = document.getElementById('timer');
let timeLeft = waktuMenit * 60;
const timerInterval = setInterval(() => {
    timeLeft--;
    const m = Math.floor(timeLeft / 60).toString().padStart(2, '0');
    const s = (timeLeft % 60).toString().padStart(2, '0');
    timerEl.textContent = m + ':' + s;
    if (timeLeft <= 0) {
        clearInterval(timerInterval);
        document.getElementById('form-psikologi').submit();
    }
}, 1000);

document.querySelectorAll('.btn-nav').forEach(btn => {
    btn.addEventListener('click', function() {
        const target = this.dataset.target;
        document.querySelectorAll('.soal-box').forEach(box => box.style.display = 'none');
        document.getElementById('soal-' + target).style.display = 'block';
    });
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
