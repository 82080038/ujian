<?php
require_once __DIR__ . '/../includes/functions.php';
requirePeserta();

$pageTitle = 'Pilih Try-Out - ' . APP_NAME;
$user = getUserById($conn, $_SESSION['user_id']);

// Paket ujian aktif sesuai target
$target = $user['target_ujian'];
$stmt = $conn->prepare("SELECT p.*, k.nama as kategori_nama, k.passing_grade_twk, k.passing_grade_tiu, k.passing_grade_tkp, k.passing_grade_kumulatif FROM paket_ujian p JOIN kategori_ujian k ON p.kategori_ujian_id = k.id WHERE p.status = 'aktif' AND (k.nama LIKE ? OR ? = 'cpns') ORDER BY p.id DESC");
$like = '%' . $target . '%';
$stmt->bind_param('ss', $like, $target);
$stmt->execute();
$paket = $stmt->get_result();

// Cek apakah sudah pernah mengerjakan
function sudahMengerjakan($conn, $user_id, $paket_id) {
    $s = $conn->prepare('SELECT id, status_lulus FROM hasil_ujian WHERE user_id = ? AND paket_ujian_id = ? ORDER BY id DESC LIMIT 1');
    $s->bind_param('ii', $user_id, $paket_id);
    $s->execute();
    return $s->get_result()->fetch_assoc();
}

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar_peserta.php';
?>

<div class="container py-4">
    <h4 class="fw-bold mb-3"><i class="bi bi-pencil-square"></i> Pilih Try-Out</h4>

    <div class="row g-3">
        <?php while ($row = $paket->fetch_assoc()):
            $status = sudahMengerjakan($conn, $_SESSION['user_id'], $row['id']);
        ?>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-<?= getJenisTesColor('twk') ?>"><?= e($row['kategori_nama']) ?></span>
                        <?php if ($status): ?>
                            <span class="badge bg-<?= $status['status_lulus'] === 'lulus' ? 'success' : 'danger' ?>"><?= $status['status_lulus'] === 'lulus' ? 'LULUS' : 'GUGUR' ?></span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Belum</span>
                        <?php endif; ?>
                    </div>
                    <h5 class="card-title fw-bold"><?= e($row['nama_paket']) ?></h5>
                    <p class="text-muted small"><?= e($row['deskripsi']) ?></p>
                    <ul class="list-unstyled small mb-3">
                        <li><i class="bi bi-check-circle text-success"></i> TWK: <?= $row['jumlah_soal_twk'] ?> soal (PG <?= $row['passing_grade_twk'] ?>)</li>
                        <li><i class="bi bi-check-circle text-success"></i> TIU: <?= $row['jumlah_soal_tiu'] ?> soal (PG <?= $row['passing_grade_tiu'] ?>)</li>
                        <li><i class="bi bi-check-circle text-success"></i> TKP: <?= $row['jumlah_soal_tkp'] ?> soal (PG <?= $row['passing_grade_tkp'] ?>)</li>
                        <li><i class="bi bi-clock text-primary"></i> Waktu: <?= $row['waktu_menit'] ?> menit</li>
                        <li><i class="bi bi-trophy text-warning"></i> Kumulatif PG: <?= $row['passing_grade_kumulatif'] ?></li>
                    </ul>
                    <?php
                        $btnLink = $status ? ($status['status_lulus'] === 'proses' ? "tryout_kerja.php?paket={$row['id']}" : "tryout_hasil.php?id={$status['id']}") : "tryout_kerja.php?paket={$row['id']}";
                        $btnText = $status ? ($status['status_lulus'] === 'proses' ? 'Lanjutkan' : 'Lihat Hasil') : 'Mulai Try-Out';
                    ?>
                    <a href="<?= $btnLink ?>" class="btn btn-success w-100 fw-bold">
                        <?= $btnText ?> <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
        <?php if ($paket->num_rows === 0): ?>
            <div class="col-12 text-center text-muted py-5">Belum ada paket ujian untuk kategori Anda. Hubungi admin.</div>
        <?php endif; ?>
    </div>
</div>

<nav class="bottom-nav-mobile d-lg-none">
    <a href="dashboard.php"><i class="bi bi-house fs-4"></i> Beranda</a>
    <a href="tryout_list.php" class="active"><i class="bi bi-pencil-square fs-4"></i> Try-Out</a>
    <a href="belajar.php"><i class="bi bi-book fs-4"></i> Belajar</a>
    <a href="rapor.php"><i class="bi bi-file-earmark-bar-graph fs-4"></i> Rapor</a>
</nav>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
