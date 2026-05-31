<?php
require_once __DIR__ . '/../includes/functions.php';
requirePeserta();

if (!isset($_GET['paket'])) {
    flash('error', 'Paket ujian tidak valid.');
    redirect('peserta/tryout_list.php');
}

$paket_id = intval($_GET['paket']);
$user_id = $_SESSION['user_id'];

// Ambil data paket
$stmt = $conn->prepare('SELECT p.*, k.nama as kategori_nama, k.waktu_pengerjaan as default_waktu FROM paket_ujian p JOIN kategori_ujian k ON p.kategori_ujian_id = k.id WHERE p.id = ? AND p.status = "aktif"');
$stmt->bind_param('i', $paket_id);
$stmt->execute();
$paket = $stmt->get_result()->fetch_assoc();

if (!$paket) {
    flash('error', 'Paket tidak ditemukan.');
    redirect('peserta/tryout_list.php');
}

// Cek apakah sudah ada hasil PROSES (sedang mengerjakan)
$stmt2 = $conn->prepare('SELECT id FROM hasil_ujian WHERE user_id = ? AND paket_ujian_id = ? AND status_lulus = "proses" ORDER BY id DESC LIMIT 1');
$stmt2->bind_param('ii', $user_id, $paket_id);
$stmt2->execute();
$existing = $stmt2->get_result()->fetch_assoc();

if ($existing) {
    $hasil_id = $existing['id'];
} else {
    // Buat hasil baru dengan status proses
    $stmt3 = $conn->prepare('INSERT INTO hasil_ujian (user_id, paket_ujian_id, tanggal_mulai, status_lulus) VALUES (?, ?, NOW(), "proses")');
    $stmt3->bind_param('ii', $user_id, $paket_id);
    $stmt3->execute();
    $hasil_id = $stmt3->insert_id;

    // Masukkan soal ke detail_jawaban (kosong)
    $soalPaket = $conn->query("SELECT soal_id FROM paket_soal WHERE paket_ujian_id = $paket_id ORDER BY RAND()");
    $insert = $conn->prepare('INSERT INTO detail_jawaban (hasil_ujian_id, soal_id) VALUES (?, ?)');
    while ($s = $soalPaket->fetch_assoc()) {
        $insert->bind_param('ii', $hasil_id, $s['soal_id']);
        $insert->execute();
    }
}

// Ambil soal yang belum dijawab (atau semua)
$stmt4 = $conn->prepare('SELECT dj.id as dj_id, dj.opsi_dipilih_id, dj.is_ragu, s.* FROM detail_jawaban dj JOIN soal s ON dj.soal_id = s.id WHERE dj.hasil_ujian_id = ? ORDER BY dj.id');
$stmt4->bind_param('i', $hasil_id);
$stmt4->execute();
$soalAll = $stmt4->get_result()->fetch_all(MYSQLI_ASSOC);

// Hitung TWK/TIU/TKP berurutan (atau acak tapi kelompok)
$soalUrut = [];
foreach (['twk', 'tiu', 'tkp'] as $jt) {
    foreach ($soalAll as $s) {
        if ($s['jenis_tes'] === $jt) $soalUrut[] = $s;
    }
}

// Soal aktif
$nomor = isset($_GET['n']) ? intval($_GET['n']) : 1;
if ($nomor < 1) $nomor = 1;
if ($nomor > count($soalUrut)) $nomor = count($soalUrut);
$soalAktif = $soalUrut[$nomor - 1] ?? null;

// Ambil opsi soal aktif
$opsiList = [];
if ($soalAktif) {
    $stmt5 = $conn->prepare('SELECT * FROM opsi_jawaban WHERE soal_id = ? ORDER BY label');
    $stmt5->bind_param('i', $soalAktif['id']);
    $stmt5->execute();
    $opsiList = $stmt5->get_result()->fetch_all(MYSQLI_ASSOC);
    // Acak opsi
    shuffle($opsiList);
}

$pageTitle = 'Try-Out - ' . e($paket['nama_paket']);
$extraCss = '';
require_once __DIR__ . '/../includes/header.php';
?>

<body class="bg-light mode-ujian">

<div class="sticky-top bg-white border-bottom py-2">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <small class="text-muted"><?= e($paket['nama_paket']) ?></small>
                <div class="timer-box text-primary" id="timer">00:00</div>
            </div>
            <div class="text-end">
                <span class="badge bg-<?= getJenisTesColor($soalAktif['jenis_tes'] ?? '') ?>"><?= getJenisTesLabel($soalAktif['jenis_tes'] ?? '') ?></span>
                <div class="small fw-bold">Soal <?= $nomor ?> / <?= count($soalUrut) ?></div>
            </div>
        </div>
    </div>
</div>

<div class="container py-3">
    <?php if ($soalAktif): ?>
    <div class="soal-box">
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <p class="fw-semibold fs-5"><?= nl2br(e($soalAktif['pertanyaan'])) ?></p>
                <?php if ($soalAktif['gambar_url']): ?>
                    <img src="<?= BASE_URL . e($soalAktif['gambar_url']) ?>" class="img-fluid rounded mb-3" alt="Gambar Soal">
                <?php endif; ?>

                <div class="mt-3">
                    <?php foreach ($opsiList as $o):
                        $isSelected = ($soalAktif['opsi_dipilih_id'] == $o['id']);
                    ?>
                    <div class="opsi-jawaban <?= $isSelected ? 'terpilih' : '' ?>" data-soal-id="<?= $soalAktif['id'] ?>" data-opsi-id="<?= $o['id'] ?>" data-paket-id="<?= $paket_id ?>">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-secondary me-2 fs-6"><?= $o['label'] ?></span>
                            <span><?= e($o['teks_jawaban']) ?></span>
                        </div>
                        <input type="radio" name="jawaban_<?= $soalAktif['id'] ?>" value="<?= $o['id'] ?>" class="d-none" <?= $isSelected ? 'checked' : '' ?>>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="form-check mt-3">
                    <input class="form-check-input toggle-ragu" type="checkbox" id="ragu-<?= $soalAktif['id'] ?>" data-soal-id="<?= $soalAktif['id'] ?>" <?= $soalAktif['is_ragu'] ? 'checked' : '' ?>>
                    <label class="form-check-label text-muted" for="ragu-<?= $soalAktif['id'] ?>"><i class="bi bi-flag"></i> Ragu-ragu</label>
                </div>
            </div>
        </div>

        <!-- Navigasi tombol -->
        <div class="d-flex justify-content-between mb-3">
            <?php if ($nomor > 1): ?>
                <a href="?paket=<?= $paket_id ?>&n=<?= $nomor - 1 ?>" class="btn btn-outline-secondary btn-lg"><i class="bi bi-chevron-left"></i> Sebelumnya</a>
            <?php else: ?>
                <span></span>
            <?php endif; ?>

            <?php if ($nomor < count($soalUrut)): ?>
                <a href="?paket=<?= $paket_id ?>&n=<?= $nomor + 1 ?>" class="btn btn-primary btn-lg">Selanjutnya <i class="bi bi-chevron-right"></i></a>
            <?php else: ?>
                <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#modalSubmit"><i class="bi bi-check-lg"></i> Selesai</button>
            <?php endif; ?>
        </div>
    </div>
    <?php else: ?>
        <div class="alert alert-info">Tidak ada soal dalam paket ini.</div>
    <?php endif; ?>

    <!-- Navigasi Nomor -->
    <div class="card mt-3">
        <div class="card-header bg-white fw-bold small"><i class="bi bi-grid"></i> Navigasi Soal</div>
        <div class="card-body">
            <div class="navigasi-soal d-flex flex-wrap gap-2">
                <?php foreach ($soalUrut as $idx => $s):
                    $no = $idx + 1;
                    $status = 'belum';
                    if ($s['opsi_dipilih_id']) $status = $s['is_ragu'] ? 'ragu' : 'dijawab';
                ?>
                <a href="?paket=<?= $paket_id ?>&n=<?= $no ?>" id="nav-btn-<?= $s['id'] ?>" class="btn btn-outline-secondary btn-soal <?= $status ?> <?= $no == $nomor ? 'active border-dark' : '' ?>"><?= $no ?></a>
                <?php endforeach; ?>
            </div>
            <div class="mt-2 small text-muted d-flex gap-3">
                <span><span class="badge bg-secondary">&nbsp;</span> Belum</span>
                <span><span class="badge bg-success">&nbsp;</span> Dijawab</span>
                <span><span class="badge bg-warning">&nbsp;</span> Ragu</span>
            </div>
        </div>
    </div>
</div>

<!-- Modal Submit -->
<div class="modal fade" id="modalSubmit" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill text-warning"></i> Konfirmasi Submit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menyelesaikan ujian ini?</p>
                <p class="text-muted small">Pastikan semua soal sudah diperiksa. Soal yang belum dijawab akan dianggap kosong.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form method="POST" action="<?= BASE_URL ?>api/submit_ujian.php" id="form-ujian">
                    <input type="hidden" name="hasil_id" value="<?= $hasil_id ?>">
                    <button type="submit" class="btn btn-success fw-bold"><i class="bi bi-check-lg"></i> Ya, Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

<script>
// Timer - ensure app.js is loaded first
document.addEventListener('DOMContentLoaded', function() {
    const waktuMenit = <?= $paket['waktu_menit'] ?? 90 ?>;
    if (typeof startTimer === 'function') {
        startTimer(waktuMenit * 60, '#timer', '#form-ujian');
    }
});
</script>
