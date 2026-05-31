<?php
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

$pageTitle = 'Jawab Forum - ' . APP_NAME;

// Simpan jawaban
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['jawab'])) {
    $tanya_id = intval($_POST['tanya_id']);
    $jawaban = trim($_POST['jawaban']);
    $stmt = $conn->prepare('UPDATE tanya_admin SET jawaban = ?, dijawab_by = ?, dijawab_at = NOW() WHERE id = ?');
    $admin_id = $_SESSION['user_id'];
    $stmt->bind_param('sii', $jawaban, $admin_id, $tanya_id);
    $stmt->execute();
    flash('success', 'Jawaban disimpan.');
    redirect('admin/jawab_forum.php');
}

// Hapus
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $conn->query("DELETE FROM tanya_admin WHERE id = $id");
    flash('success', 'Pertanyaan dihapus.');
    redirect('admin/jawab_forum.php');
}

$belum = $conn->query("SELECT t.*, u.nama as peserta_nama FROM tanya_admin t JOIN users u ON t.user_id = u.id WHERE t.jawaban IS NULL ORDER BY t.created_at DESC");
$sudah = $conn->query("SELECT t.*, u.nama as peserta_nama FROM tanya_admin t JOIN users u ON t.user_id = u.id WHERE t.jawaban IS NOT NULL ORDER BY t.dijawab_at DESC LIMIT 50");

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar_admin.php';
?>

<div class="container py-4">
    <h3 class="fw-bold mb-3"><i class="bi bi-chat-square-text"></i> Forum Tanya - Jawab Peserta</h3>

    <?php if (hasFlash('success')): ?><div class="alert alert-success auto-dismiss"><?= e(getFlash('success')) ?></div><?php endif; ?>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark fw-bold"><i class="bi bi-question-circle"></i> Belum Dijawab (<?= $belum->num_rows ?>)</div>
                <div class="list-group list-group-flush" style="max-height:600px; overflow-y:auto;">
                    <?php while ($b = $belum->fetch_assoc()): ?>
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-bold"><?= e($b['peserta_nama']) ?></div>
                                <small class="text-muted"><?= $b['created_at'] ?></small>
                            </div>
                            <a href="?hapus=<?= $b['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus?')"><i class="bi bi-trash"></i></a>
                        </div>
                        <p class="mt-2"><?= e($b['pertanyaan']) ?></p>
                        <form method="POST" class="mt-2">
                            <input type="hidden" name="tanya_id" value="<?= $b['id'] ?>">
                            <textarea name="jawaban" class="form-control mb-2" rows="2" placeholder="Tulis jawaban..." required></textarea>
                            <button type="submit" name="jawab" class="btn btn-sm btn-primary"><i class="bi bi-send"></i> Kirim Jawaban</button>
                        </form>
                    </div>
                    <?php endwhile; ?>
                    <?php if ($belum->num_rows === 0): ?>
                        <div class="text-center text-muted py-4">Tidak ada pertanyaan menunggu.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-success">
                <div class="card-header bg-success text-white fw-bold"><i class="bi bi-check-circle"></i> Sudah Dijawab</div>
                <div class="list-group list-group-flush" style="max-height:600px; overflow-y:auto;">
                    <?php while ($s = $sudah->fetch_assoc()): ?>
                    <div class="list-group-item">
                        <div class="fw-bold"><?= e($s['peserta_nama']) ?></div>
                        <small class="text-muted"><?= $s['created_at'] ?></small>
                        <p class="mt-1"><strong>Q:</strong> <?= e($s['pertanyaan']) ?></p>
                        <div class="p-2 bg-light rounded"><strong>A:</strong> <?= nl2br(e($s['jawaban'])) ?></div>
                    </div>
                    <?php endwhile; ?>
                    <?php if ($sudah->num_rows === 0): ?>
                        <div class="text-center text-muted py-4">Belum ada jawaban.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
