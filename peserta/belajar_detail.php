<?php
require_once __DIR__ . '/../includes/functions.php';
requirePeserta();

if (!isset($_GET['id'])) { redirect('peserta/belajar.php'); }
$id = intval($_GET['id']);

$stmt = $conn->prepare('SELECT m.*, k.nama as kategori_nama FROM materi m LEFT JOIN kategori_ujian k ON m.kategori_ujian_id = k.id WHERE m.id = ?');
$stmt->bind_param('i', $id); $stmt->execute();
$materi = $stmt->get_result()->fetch_assoc();

if (!$materi) { redirect('peserta/belajar.php'); }

$pageTitle = e($materi['judul']) . ' - ' . APP_NAME;

// Update status rekomendasi jika ada
$conn->query("UPDATE rekomendasi_belajar SET status = 'selesai' WHERE user_id = {$_SESSION['user_id']} AND topik = '" . $conn->real_escape_string($materi['topik']) . "'");

// Simpan riwayat akses materi
$conn->query("INSERT INTO riwayat_materi (user_id, materi_id, progress_persen, waktu_baca_menit) VALUES ({$_SESSION['user_id']}, $id, 100, 1) ON DUPLICATE KEY UPDATE progress_persen = GREATEST(progress_persen, 100), waktu_baca_menit = waktu_baca_menit + 1");

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar_peserta.php';
?>

<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="belajar.php">Belajar</a></li>
            <li class="breadcrumb-item active"><?= e($materi['judul']) ?></li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <span class="badge bg-<?= getJenisTesColor($materi['jenis_tes']) ?> me-1"><?= getJenisTesLabel($materi['jenis_tes']) ?></span>
                    <span class="badge bg-secondary"><?= ucfirst($materi['level']) ?></span>
                    <span class="badge bg-light text-dark border"><i class="bi bi-tag"></i> <?= e($materi['topik']) ?></span>
                </div>
                <small class="text-muted"><?= e($materi['kategori_nama'] ?? '') ?></small>
            </div>
            <h3 class="fw-bold mb-3"><?= e($materi['judul']) ?></h3>
            <div class="content-html">
                <?= $materi['konten_html'] ?>
            </div>
        </div>
    </div>

    <div class="mt-3 d-flex justify-content-between">
        <a href="belajar.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
        <a href="tryout_list.php" class="btn btn-success"><i class="bi bi-pencil-square"></i> Latihan Soal</a>
    </div>
</div>

<nav class="bottom-nav-mobile d-lg-none">
    <a href="dashboard.php"><i class="bi bi-house fs-4"></i> Beranda</a>
    <a href="tryout_list.php"><i class="bi bi-pencil-square fs-4"></i> Try-Out</a>
    <a href="belajar.php" class="active"><i class="bi bi-book fs-4"></i> Belajar</a>
    <a href="rapor.php"><i class="bi bi-file-earmark-bar-graph fs-4"></i> Rapor</a>
</nav>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
