<?php
require_once __DIR__ . '/../includes/functions.php';
requirePeserta();

if (empty($_SESSION['mini_soal']) || empty($_SESSION['mini_jawaban'])) {
    flash('error', 'Tidak ada data mini try-out.');
    redirect('peserta/mini_tryout.php');
}

$jenis = $_SESSION['mini_jenis'] ?? 'twk';
$jumlah = $_SESSION['mini_jumlah'] ?? 0;
$benar = 0;
$salah = 0;
$kosong = 0;
$skor = 0;
$detail = [];

foreach ($_SESSION['mini_soal'] as $soal_id) {
    $s = $conn->query("SELECT * FROM soal WHERE id = $soal_id")->fetch_assoc();
    $opsi_dipilih = $_SESSION['mini_jawaban'][$soal_id] ?? null;

    if (!$opsi_dipilih) {
        $kosong++;
        $detail[] = ['soal' => $s, 'status' => 'kosong', 'jawaban_peserta' => '-', 'jawaban_benar' => '-'];
        continue;
    }

    $opsi = $conn->query("SELECT * FROM opsi_jawaban WHERE id = $opsi_dipilih")->fetch_assoc();
    $kunci = $conn->query("SELECT * FROM opsi_jawaban WHERE soal_id = $soal_id AND is_kunci = 1")->fetch_assoc();

    if ($jenis === 'tkp') {
        $nilai = $opsi['bobot_nilai'] ?? 0;
        $skor += $nilai;
        $status = $nilai >= 4 ? 'benar' : ($nilai >= 2 ? 'cukup' : 'salah');
    } else {
        if ($opsi['is_kunci']) {
            $benar++;
            $skor += 5;
            $status = 'benar';
        } else {
            $salah++;
            $status = 'salah';
        }
    }

    $detail[] = [
        'soal' => $s,
        'status' => $status,
        'jawaban_peserta' => $opsi['teks_jawaban'] ?? '-',
        'jawaban_benar' => $kunci['teks_jawaban'] ?? '-'
    ];
}

// Bersihkan sesi
unset($_SESSION['mini_soal'], $_SESSION['mini_jawaban'], $_SESSION['mini_ragu'], $_SESSION['mini_jenis'], $_SESSION['mini_jumlah'], $_SESSION['mini_start']);

$pageTitle = 'Hasil Mini Try-Out - ' . APP_NAME;
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar_peserta.php';
?>

<div class="container py-4">
    <h4 class="fw-bold mb-3"><i class="bi bi-lightning-charge"></i> Hasil Mini Try-Out <?= strtoupper($jenis) ?></h4>

    <div class="row g-3 mb-4">
        <div class="col-6">
            <div class="card score-card text-center">
                <div class="card-body">
                    <div class="score-value text-<?= getJenisTesColor($jenis) ?>"><?= $skor ?></div>
                    <small>Skor</small>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="fw-bold text-success"><?= $benar ?></h3>
                    <small>Benar</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="fw-bold text-danger"><?= $salah ?></h5><small>Salah</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="fw-bold text-secondary"><?= $kosong ?></h5><small>Kosong</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-sm-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="fw-bold text-primary"><?= $jumlah ?></h5><small>Total</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header bg-white fw-bold"><i class="bi bi-journal-text"></i> Pembahasan</div>
        <div class="card-body">
            <?php foreach ($detail as $idx => $d):
                $badge = ['benar'=>'success','salah'=>'danger','kosong'=>'secondary','cukup'=>'warning'][$d['status']] ?? 'secondary';
            ?>
            <div class="mb-3 pb-3 border-bottom">
                <p class="fw-semibold"><?= ($idx+1) ?>. <?= strip_tags($d['soal']['pertanyaan']) ?></p>
                <span class="badge bg-<?= $badge ?>"><?= ucfirst($d['status']) ?></span>
                <?php if ($d['status'] !== 'benar'): ?>
                    <p class="small text-muted mt-1">Jawaban Anda: <span class="text-danger"><?= e($d['jawaban_peserta']) ?></span></p>
                    <p class="small text-muted">Jawaban Benar: <span class="text-success"><?= e($d['jawaban_benar']) ?></span></p>
                <?php endif; ?>
                <?php if ($d['soal']['pembahasan']): ?>
                    <div class="pembahasan-box small"><strong>Pembahasan:</strong> <?= nl2br(e($d['soal']['pembahasan'])) ?></div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="d-flex gap-2">
        <a href="mini_tryout.php" class="btn btn-primary"><i class="bi bi-arrow-repeat"></i> Mini Try-Out Lagi</a>
        <a href="dashboard.php" class="btn btn-secondary"><i class="bi bi-house"></i> Dashboard</a>
    </div>
</div>

<nav class="bottom-nav-mobile d-lg-none">
    <a href="dashboard.php"><i class="bi bi-house fs-4"></i> Beranda</a>
    <a href="tryout_list.php" class="active"><i class="bi bi-pencil-square fs-4"></i> Try-Out</a>
    <a href="belajar.php"><i class="bi bi-book fs-4"></i> Belajar</a>
    <a href="rapor.php"><i class="bi bi-file-earmark-bar-graph fs-4"></i> Rapor</a>
</nav>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
