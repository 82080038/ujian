<?php
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

$pageTitle = 'Catatan Pengajar - ' . APP_NAME;

// Simpan catatan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan_catatan'])) {
    $peserta_id = intval($_POST['peserta_id']);
    $catatan = trim($_POST['catatan']);
    $stmt = $conn->prepare('INSERT INTO catatan_pengajar (user_id, catatan, created_by) VALUES (?,?,?) ON DUPLICATE KEY UPDATE catatan = VALUES(catatan), updated_at = NOW()');
    $admin_id = $_SESSION['user_id'];
    $stmt->bind_param('isi', $peserta_id, $catatan, $admin_id);
    $stmt->execute();
    flash('success', 'Catatan disimpan.');
    redirect('admin/catatan_pengajar.php?peserta_id=' . $peserta_id);
}

$peserta_id = intval($_GET['peserta_id'] ?? 0);
$peserta_list = $conn->query("SELECT id, nama, email, target_ujian FROM users WHERE role = 'peserta' ORDER BY nama");

$selected = null;
$catatan = '';
if ($peserta_id) {
    $selected = $conn->query("SELECT * FROM users WHERE id = $peserta_id AND role = 'peserta'")->fetch_assoc();
    $res_catatan = $conn->query("SELECT catatan FROM catatan_pengajar WHERE user_id = $peserta_id ORDER BY updated_at DESC LIMIT 1");
    if ($res_catatan && $res_catatan->num_rows) $catatan = $res_catatan->fetch_assoc()['catatan'];
}

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar_admin.php';
?>

<div class="container py-4">
    <h3 class="fw-bold mb-3"><i class="bi bi-sticky"></i> Catatan Pengajar</h3>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-white fw-bold">Pilih Peserta</div>
                <div class="list-group list-group-flush" style="max-height:500px; overflow-y:auto;">
                    <?php while ($p = $peserta_list->fetch_assoc()): ?>
                    <a href="?peserta_id=<?= $p['id'] ?>" class="list-group-item list-group-item-action <?= $peserta_id == $p['id'] ? 'active' : '' ?>">
                        <div class="fw-bold"><?= e($p['nama']) ?></div>
                        <small><?= e($p['email']) ?> - <?= strtoupper($p['target_ujian']) ?></small>
                    </a>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <?php if ($selected): ?>
            <div class="card">
                <div class="card-header bg-white fw-bold">
                    <i class="bi bi-person"></i> <?= e($selected['nama']) ?>
                    <span class="badge bg-secondary"><?= strtoupper($selected['target_ujian']) ?></span>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="peserta_id" value="<?= $peserta_id ?>">
                        <div class="mb-3">
                            <label class="form-label">Catatan / Arahan untuk Peserta</label>
                            <textarea name="catatan" class="form-control" rows="8" placeholder="Tulis catatan, arahan, atau evaluasi untuk peserta ini..."><?= e($catatan) ?></textarea>
                        </div>
                        <button type="submit" name="simpan_catatan" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Catatan</button>
                    </form>
                </div>
            </div>
            <?php else: ?>
                <div class="alert alert-info">Pilih peserta dari daftar di sebelah kiri.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
