<?php
require_once __DIR__ . '/../includes/functions.php';
requirePeserta();

$pageTitle = 'Mini Try-Out - ' . APP_NAME;

// Ambil topik unik per jenis tes
$topikList = [];
foreach (['twk','tiu','tkp'] as $jt) {
    $res = $conn->query("SELECT DISTINCT topik FROM soal WHERE jenis_tes = '$jt' ORDER BY topik");
    while ($r = $res->fetch_assoc()) $topikList[$jt][] = $r['topik'];
}

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar_peserta.php';
?>

<div class="container py-4">
    <h4 class="fw-bold mb-3"><i class="bi bi-lightning-charge"></i> Mini Try-Out</h4>
    <p class="text-muted small">Latihan cepat per subtes. Pilih jenis tes, jumlah soal, dan topik.</p>

    <div class="card">
        <div class="card-body">
            <form method="GET" action="mini_tryout_kerja.php">
                <div class="mb-3">
                    <label class="form-label fw-bold">Jenis Tes</label>
                    <div class="row g-2">
                        <?php foreach (['twk'=>'TWK','tiu'=>'TIU','tkp'=>'TKP'] as $k=>$v): ?>
                        <div class="col-4">
                            <div class="form-check card p-2 text-center">
                                <input class="form-check-input" type="radio" name="jenis" value="<?= $k ?>" id="jenis<?= $k ?>" <?= $k==='twk'?'checked':'' ?> required>
                                <label class="form-check-label fw-bold text-<?= getJenisTesColor($k) ?>" for="jenis<?= $k ?>"><?= $v ?></label>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Jumlah Soal</label>
                    <select name="jumlah" class="form-select" required>
                        <option value="10">10 Soal</option>
                        <option value="20" selected>20 Soal</option>
                        <option value="30">30 Soal</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Topik (Opsional)</label>
                    <select name="topik" class="form-select">
                        <option value="">Semua Topik</option>
                        <?php foreach ($topikList as $jt => $topics): ?>
                            <optgroup label="<?= strtoupper($jt) ?>" class="opt-<?= $jt ?>">
                                <?php foreach ($topics as $t): ?>
                                    <option value="<?= e($t) ?>"><?= e($t) ?></option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Tingkat Kesulitan (Opsional)</label>
                    <select name="level" class="form-select">
                        <option value="">Campur</option>
                        <?php foreach (['sangat_mudah','mudah','sedang','sulit','sangat_sulit'] as $l): ?>
                            <option value="<?= $l ?>"><?= ucwords(str_replace('_',' ',$l)) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary w-100 btn-lg fw-bold">
                    <i class="bi bi-play-fill"></i> Mulai Mini Try-Out
                </button>
            </form>
        </div>
    </div>
</div>

<script>
// Show/hide topik optgroup based on jenis selection
document.addEventListener('DOMContentLoaded', function() {
    const radios = document.querySelectorAll('input[name="jenis"]');
    radios.forEach(function(radio) {
        radio.addEventListener('change', function() {
            const jenis = this.value;
            document.querySelectorAll('optgroup').forEach(function(og) { og.style.display = 'none'; });
            document.querySelectorAll('.opt-' + jenis).forEach(function(og) { og.style.display = 'block'; });
        });
    });
    const checked = document.querySelector('input[name="jenis"]:checked');
    if (checked) checked.dispatchEvent(new Event('change'));
});
</script>

<nav class="bottom-nav-mobile d-lg-none">
    <a href="dashboard.php"><i class="bi bi-house fs-4"></i> Beranda</a>
    <a href="tryout_list.php"><i class="bi bi-pencil-square fs-4"></i> Try-Out</a>
    <a href="belajar.php"><i class="bi bi-book fs-4"></i> Belajar</a>
    <a href="rapor.php"><i class="bi bi-file-earmark-bar-graph fs-4"></i> Rapor</a>
</nav>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
