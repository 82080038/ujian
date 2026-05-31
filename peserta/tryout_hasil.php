<?php
require_once __DIR__ . '/../includes/functions.php';
requirePeserta();

if (!isset($_GET['id'])) { flash('error','Hasil tidak ditemukan.'); redirect('peserta/dashboard.php'); }
$hasil_id = intval($_GET['id']); $user_id = $_SESSION['user_id'];

$stmt = $conn->prepare('SELECT h.*, p.nama_paket, p.waktu_menit, k.nama as kategori_nama, k.passing_grade_twk, k.passing_grade_tiu, k.passing_grade_tkp, k.passing_grade_kumulatif FROM hasil_ujian h JOIN paket_ujian p ON h.paket_ujian_id = p.id JOIN kategori_ujian k ON p.kategori_ujian_id = k.id WHERE h.id = ? AND h.user_id = ?');
$stmt->bind_param('ii', $hasil_id, $user_id); $stmt->execute(); $hasil = $stmt->get_result()->fetch_assoc();
if (!$hasil) { flash('error','Hasil tidak ditemukan.'); redirect('peserta/dashboard.php'); }

$salah = $conn->query("SELECT dj.*, s.pertanyaan, s.pembahasan, s.tips_triks, s.jenis_tes, s.topik, o.teks_jawaban as jawaban_benar_teks, o2.teks_jawaban as jawaban_peserta_teks FROM detail_jawaban dj JOIN soal s ON dj.soal_id = s.id LEFT JOIN opsi_jawaban o ON o.soal_id = s.id AND o.is_kunci = 1 LEFT JOIN opsi_jawaban o2 ON o2.id = dj.opsi_dipilih_id WHERE dj.hasil_ujian_id = $hasil_id AND dj.nilai_diperoleh = 0 ORDER BY s.jenis_tes, s.id LIMIT 20");

$pageTitle = 'Hasil Try-Out - ' . APP_NAME;
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar_peserta.php';
?>

<div class="container py-4">
    <h4 class="fw-bold mb-3"><i class="bi bi-file-earmark-bar-graph"></i> Hasil Try-Out</h4>
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="card border-<?= $hasil['status_lulus'] === 'lulus' ? 'success' : 'danger' ?>">
                <div class="card-body text-center">
                    <h5 class="text-muted"><?= e($hasil['nama_paket']) ?></h5>
                    <div class="display-3 fw-bold text-<?= $hasil['status_lulus'] === 'lulus' ? 'success' : 'danger' ?>"><?= $hasil['status_lulus'] === 'lulus' ? 'LULUS' : 'GUGUR' ?></div>
                    <p class="text-muted mb-0">Skor Kumulatif: <strong><?= $hasil['skor_kumulatif'] ?></strong> / PG: <?= $hasil['passing_grade_kumulatif'] ?></p>
                    <small class="text-muted"><?= formatTanggal($hasil['tanggal_selesai'] ?? $hasil['created_at']) ?></small>
                </div>
            </div>
        </div>
        <?php foreach ([['label'=>'TWK','skor'=>$hasil['skor_twk'],'pg'=>$hasil['passing_grade_twk'],'color'=>'primary'],['label'=>'TIU','skor'=>$hasil['skor_tiu'],'pg'=>$hasil['passing_grade_tiu'],'color'=>'warning'],['label'=>'TKP','skor'=>$hasil['skor_tkp'],'pg'=>$hasil['passing_grade_tkp'],'color'=>'success']] as $sub): ?>
        <div class="col-12 col-sm-4">
            <div class="card score-card text-center h-100">
                <div class="card-body">
                    <h6 class="text-<?= $sub['color'] ?> fw-bold"><?= $sub['label'] ?></h6>
                    <div class="score-value text-<?= $sub['color'] ?>"><?= $sub['skor'] ?></div>
                    <small class="text-muted">PG: <?= $sub['pg'] ?></small>
                    <div class="progress mt-2" style="height:8px"><div class="progress-bar bg-<?= $sub['color'] ?>" style="width:<?= min(100, ($sub['skor']/($sub['pg']*2))*100) ?>"></div></div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="card">
        <div class="card-header bg-white fw-bold"><i class="bi bi-journal-text"></i> Pembahasan Soal Salah</div>
        <div class="card-body">
            <?php if ($salah->num_rows === 0): ?>
                <div class="alert alert-success"><i class="bi bi-trophy"></i> Semua soal benar!</div>
            <?php else: ?>
                <div class="accordion" id="accordionSalah">
                    <?php $idx = 1; while ($row = $salah->fetch_assoc()): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header"><button class="accordion-button <?= $idx>1?'collapsed':'' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#c<?= $idx ?>"><span class="badge bg-<?= getJenisTesColor($row['jenis_tes']) ?> me-2"><?= getJenisTesLabel($row['jenis_tes']) ?></span><small class="text-truncate d-inline-block" style="max-width:70%"><?= strip_tags($row['pertanyaan']) ?></small></button></h2>
                        <div id="c<?= $idx ?>" class="accordion-collapse collapse <?= $idx===1?'show':'' ?>" data-bs-parent="#accordionSalah">
                            <div class="accordion-body">
                                <p><strong>Jawaban Anda:</strong> <span class="text-danger"><?= e($row['jawaban_peserta_teks'] ?? 'Tidak dijawab') ?></span></p>
                                <p><strong>Jawaban Benar:</strong> <span class="text-success"><?= e($row['jawaban_benar_teks']) ?></span></p>
                                <div class="pembahasan-box"><h6><i class="bi bi-book"></i> Pembahasan:</h6><p><?= nl2br(e($row['pembahasan'])) ?></p></div>
                                <?php if ($row['tips_triks']): ?><div class="tips-box"><h6><i class="bi bi-lightbulb"></i> Tips & Trik:</h6><p><?= nl2br(e($row['tips_triks'])) ?></p></div><?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php $idx++; endwhile; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-4 d-flex gap-2">
        <a href="tryout_list.php" class="btn btn-success"><i class="bi bi-pencil-square"></i> Try-Out Lain</a>
        <a href="rapor.php" class="btn btn-primary"><i class="bi bi-file-earmark-bar-graph"></i> Lihat Rapor</a>
    </div>
</div>

<nav class="bottom-nav-mobile d-lg-none">
    <a href="dashboard.php"><i class="bi bi-house fs-4"></i> Beranda</a>
    <a href="tryout_list.php" class="active"><i class="bi bi-pencil-square fs-4"></i> Try-Out</a>
    <a href="belajar.php"><i class="bi bi-book fs-4"></i> Belajar</a>
    <a href="rapor.php"><i class="bi bi-file-earmark-bar-graph fs-4"></i> Rapor</a>
</nav>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
