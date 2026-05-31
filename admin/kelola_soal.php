<?php
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

$pageTitle = 'Kelola Soal - ' . APP_NAME;

// Hapus soal
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $stmt = $conn->prepare('DELETE FROM soal WHERE id = ?');
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        flash('success', 'Soal berhasil dihapus.');
    } else {
        flash('error', 'Gagal menghapus soal.');
    }
    redirect('admin/kelola_soal.php');
}

// Filter
$jenis_filter = $_GET['jenis'] ?? '';
$topik_filter = $_GET['topik'] ?? '';
$kat_filter = $_GET['kategori'] ?? '';

$where = [];
$params = [];
$types = '';

if ($jenis_filter) { $where[] = 's.jenis_tes = ?'; $params[] = $jenis_filter; $types .= 's'; }
if ($kat_filter) { $where[] = 's.kategori_ujian_id = ?'; $params[] = $kat_filter; $types .= 'i'; }
if ($topik_filter) { $where[] = 's.topik LIKE ?'; $params[] = '%' . $topik_filter . '%'; $types .= 's'; }

$sql = 'SELECT s.*, k.nama as kategori_nama FROM soal s LEFT JOIN kategori_ujian k ON s.kategori_ujian_id = k.id';
if ($where) $sql .= ' WHERE ' . implode(' AND ', $where);
$sql .= ' ORDER BY s.id DESC';

$stmt = $conn->prepare($sql);
if ($params) $stmt->bind_param($types, ...$params);
$stmt->execute();
$soal = $stmt->get_result();

$kategori_list = getKategoriUjian($conn);

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar_admin.php';
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold"><i class="bi bi-journal-text"></i> Kelola Soal</h3>
        <a href="kelola_soal_form.php" class="btn btn-primary fw-bold"><i class="bi bi-plus-lg"></i> Tambah Soal</a>
    </div>

    <?php if (hasFlash('success')): ?>
        <div class="alert alert-success auto-dismiss"><?= e(getFlash('success')) ?></div>
    <?php endif; ?>
    <?php if (hasFlash('error')): ?>
        <div class="alert alert-danger auto-dismiss"><?= e(getFlash('error')) ?></div>
    <?php endif; ?>

    <!-- Filter -->
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-3">
                    <select name="kategori" class="form-select">
                        <option value="">Semua Kategori</option>
                        <?php foreach ($kategori_list as $k): ?>
                            <option value="<?= $k['id'] ?>" <?= $kat_filter == $k['id'] ? 'selected' : '' ?>><?= e($k['nama']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="jenis" class="form-select">
                        <option value="">Semua Jenis</option>
                        <option value="twk" <?= $jenis_filter === 'twk' ? 'selected' : '' ?>>TWK</option>
                        <option value="tiu" <?= $jenis_filter === 'tiu' ? 'selected' : '' ?>>TIU</option>
                        <option value="tkp" <?= $jenis_filter === 'tkp' ? 'selected' : '' ?>>TKP</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="text" name="topik" class="form-control" placeholder="Cari topik..." value="<?= e($topik_filter) ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary w-100"><i class="bi bi-search"></i> Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Kategori</th>
                            <th>Jenis</th>
                            <th>Topik</th>
                            <th>Pertanyaan</th>
                            <th>Kesulitan</th>
                            <th style="width:100px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; while ($row = $soal->fetch_assoc()): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><small><?= e($row['kategori_nama'] ?? '-') ?></small></td>
                            <td><span class="badge bg-<?= getJenisTesColor($row['jenis_tes']) ?>"><?= getJenisTesLabel($row['jenis_tes']) ?></span></td>
                            <td><small><?= e($row['topik']) ?></small></td>
                            <td><?= mb_strimwidth(strip_tags($row['pertanyaan']), 0, 80, '...') ?></td>
                            <td><span class="badge bg-secondary"><?= e($row['tingkat_kesulitan']) ?></span></td>
                            <td>
                                <a href="kelola_soal_form.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                <a href="?hapus=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus soal ini?')"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        <?php if ($soal->num_rows === 0): ?>
                            <tr><td colspan="7" class="text-center text-muted py-4">Tidak ada soal.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
