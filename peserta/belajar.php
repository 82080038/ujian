<?php
require_once __DIR__ . '/../includes/functions.php';
requirePeserta();

$pageTitle = 'Belajar - ' . APP_NAME;
$user = getUserById($conn, $_SESSION['user_id']);

// Filter
$jenis_filter = $_GET['jenis'] ?? '';
$topik_filter = $_GET['topik'] ?? '';
$level_filter = $_GET['level'] ?? '';

$where = ['1=1'];
$params = [];
$types = '';

// Sesuaikan dengan target peserta
$targetKat = $conn->query("SELECT id FROM kategori_ujian WHERE nama LIKE '%" . $user['target_ujian'] . "%' LIMIT 1")->fetch_assoc();
if ($targetKat) { $where[] = 'm.kategori_ujian_id = ?'; $params[] = $targetKat['id']; $types .= 'i'; }

if ($jenis_filter) { $where[] = 'm.jenis_tes = ?'; $params[] = $jenis_filter; $types .= 's'; }
if ($topik_filter) { $where[] = 'm.topik LIKE ?'; $params[] = '%' . $topik_filter . '%'; $types .= 's'; }
if ($level_filter) { $where[] = 'm.level = ?'; $params[] = $level_filter; $types .= 's'; }

$sql = 'SELECT m.*, k.nama as kategori_nama FROM materi m LEFT JOIN kategori_ujian k ON m.kategori_ujian_id = k.id WHERE ' . implode(' AND ', $where) . ' ORDER BY m.jenis_tes, m.level, m.topik';
$stmt = $conn->prepare($sql);
if ($params) $stmt->bind_param($types, ...$params);
$stmt->execute();
$materi = $stmt->get_result();

// Topik unik untuk filter
$topik_list = $conn->query("SELECT DISTINCT topik FROM materi WHERE 1 ORDER BY topik LIMIT 50");

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar_peserta.php';
?>

<div class="container py-4">
    <h4 class="fw-bold mb-3"><i class="bi bi-book"></i> Materi Belajar</h4>

    <!-- Filter -->
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-6 col-md-3">
                    <select name="jenis" class="form-select">
                        <option value="">Semua Jenis</option>
                        <option value="twk" <?= $jenis_filter==='twk'?'selected':'' ?>>TWK</option>
                        <option value="tiu" <?= $jenis_filter==='tiu'?'selected':'' ?>>TIU</option>
                        <option value="tkp" <?= $jenis_filter==='tkp'?'selected':'' ?>>TKP</option>
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <select name="topik" class="form-select">
                        <option value="">Semua Topik</option>
                        <?php while ($t = $topik_list->fetch_assoc()): ?>
                            <option value="<?= e($t['topik']) ?>" <?= $topik_filter === $t['topik'] ? 'selected' : '' ?>><?= e($t['topik']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <select name="level" class="form-select">
                        <option value="">Semua Level</option>
                        <?php foreach (['dasar','menengah','lanjut'] as $l): ?>
                            <option value="<?= $l ?>" <?= $level_filter === $l ? 'selected' : '' ?>><?= ucfirst($l) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-6 col-md-3"><button type="submit" class="btn btn-outline-primary w-100"><i class="bi bi-search"></i> Filter</button></div>
            </form>
        </div>
    </div>

    <!-- List Materi -->
    <div class="row g-3">
        <?php while ($row = $materi->fetch_assoc()): ?>
        <div class="col-md-6">
            <div class="card h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-<?= getJenisTesColor($row['jenis_tes']) ?>"><?= getJenisTesLabel($row['jenis_tes']) ?></span>
                        <span class="badge bg-secondary"><?= ucfirst($row['level']) ?></span>
                    </div>
                    <h5 class="card-title fw-bold"><?= e($row['judul']) ?></h5>
                    <p class="text-muted small mb-2"><i class="bi bi-tag"></i> <?= e($row['topik']) ?></p>
                    <p class="card-text text-muted small"><?= mb_strimwidth(strip_tags($row['konten_html'] ?? ''), 0, 150, '...') ?></p>
                    <a href="belajar_detail.php?id=<?= $row['id'] ?>" class="btn btn-outline-primary btn-sm"><i class="bi bi-book"></i> Baca Materi</a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
        <?php if ($materi->num_rows === 0): ?>
            <div class="col-12 text-center text-muted py-5">Belum ada materi untuk filter ini. Hubungi admin.</div>
        <?php endif; ?>
    </div>
</div>

<nav class="bottom-nav-mobile d-lg-none">
    <a href="dashboard.php"><i class="bi bi-house fs-4"></i> Beranda</a>
    <a href="tryout_list.php"><i class="bi bi-pencil-square fs-4"></i> Try-Out</a>
    <a href="belajar.php" class="active"><i class="bi bi-book fs-4"></i> Belajar</a>
    <a href="rapor.php"><i class="bi bi-file-earmark-bar-graph fs-4"></i> Rapor</a>
</nav>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
