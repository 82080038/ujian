<?php
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

$pageTitle = 'Kelola Materi - ' . APP_NAME;

// Hapus
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $conn->query("DELETE FROM materi WHERE id = $id");
    flash('success', 'Materi dihapus.');
    redirect('admin/kelola_materi.php');
}

// Simpan (tambah/edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $kat = intval($_POST['kategori_ujian_id'] ?? 0);
    $judul = trim($_POST['judul'] ?? '');
    $topik = trim($_POST['topik'] ?? '');
    $jenis = $_POST['jenis_tes'] ?? '';
    $konten = trim($_POST['konten_html'] ?? '');
    $tipe = $_POST['tipe'] ?? 'artikel';
    $level = $_POST['level'] ?? 'dasar';

    if ($id) {
        $stmt = $conn->prepare('UPDATE materi SET kategori_ujian_id=?, judul=?, topik=?, jenis_tes=?, konten_html=?, tipe=?, level=? WHERE id=?');
        $stmt->bind_param('issssssi', $kat, $judul, $topik, $jenis, $konten, $tipe, $level, $id);
    } else {
        $stmt = $conn->prepare('INSERT INTO materi (kategori_ujian_id, judul, topik, jenis_tes, konten_html, tipe, level) VALUES (?,?,?,?,?,?,?)');
        $stmt->bind_param('issssss', $kat, $judul, $topik, $jenis, $konten, $tipe, $level);
    }
    $stmt->execute();
    flash('success', 'Materi disimpan.');
    redirect('admin/kelola_materi.php');
}

$editData = null;
if (isset($_GET['edit'])) {
    $stmt = $conn->prepare('SELECT * FROM materi WHERE id = ?');
    $stmt->bind_param('i', $_GET['edit']);
    $stmt->execute();
    $editData = $stmt->get_result()->fetch_assoc();
}

$materi = $conn->query('SELECT m.*, k.nama as kategori_nama FROM materi m LEFT JOIN kategori_ujian k ON m.kategori_ujian_id = k.id ORDER BY m.id DESC');
$kategori_list = getKategoriUjian($conn);

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar_admin.php';
?>

<div class="container py-4">
    <h3 class="fw-bold mb-3"><i class="bi bi-book"></i> Kelola Materi</h3>

    <?php if (hasFlash('success')): ?>
        <div class="alert alert-success auto-dismiss"><?= e(getFlash('success')) ?></div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header bg-white fw-bold"><?= $editData ? 'Edit' : 'Tambah' ?> Materi</div>
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="id" value="<?= $editData['id'] ?? 0 ?>">
                <div class="row g-2">
                    <div class="col-md-3"><input type="text" name="judul" class="form-control" placeholder="Judul Materi" required value="<?= e($editData['judul'] ?? '') ?>"></div>
                    <div class="col-md-2">
                        <select name="kategori_ujian_id" class="form-select" required>
                            <option value="">Kategori</option>
                            <?php foreach ($kategori_list as $k): ?>
                                <option value="<?= $k['id'] ?>" <?= ($editData['kategori_ujian_id'] ?? '') == $k['id'] ? 'selected' : '' ?>><?= e($k['nama']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="jenis_tes" class="form-select">
                            <option value="">Jenis Tes</option>
                            <option value="twk" <?= ($editData['jenis_tes'] ?? '') === 'twk' ? 'selected' : '' ?>>TWK</option>
                            <option value="tiu" <?= ($editData['jenis_tes'] ?? '') === 'tiu' ? 'selected' : '' ?>>TIU</option>
                            <option value="tkp" <?= ($editData['jenis_tes'] ?? '') === 'tkp' ? 'selected' : '' ?>>TKP</option>
                        </select>
                    </div>
                    <div class="col-md-2"><input type="text" name="topik" class="form-control" placeholder="Topik" required value="<?= e($editData['topik'] ?? '') ?>"></div>
                    <div class="col-md-1">
                        <select name="tipe" class="form-select">
                            <?php foreach (['artikel','video','flashcard','rumus'] as $t): ?>
                                <option value="<?= $t ?>" <?= ($editData['tipe'] ?? '') === $t ? 'selected' : '' ?>><?= ucfirst($t) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <select name="level" class="form-select">
                            <?php foreach (['dasar','menengah','lanjut'] as $l): ?>
                                <option value="<?= $l ?>" <?= ($editData['level'] ?? '') === $l ? 'selected' : '' ?>><?= ucfirst($l) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-save"></i></button>
                    </div>
                </div>
                <div class="mt-2">
                    <textarea name="konten_html" class="form-control" rows="6" placeholder="Konten HTML / Pembahasan / Flashcard..."><?= e($editData['konten_html'] ?? '') ?></textarea>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 align-middle">
                    <thead class="table-light">
                        <tr><th>Judul</th><th>Kategori</th><th>Jenis</th><th>Topik</th><th>Tipe</th><th>Level</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $materi->fetch_assoc()): ?>
                        <tr>
                            <td><?= e($row['judul']) ?></td>
                            <td><small><?= e($row['kategori_nama'] ?? '-') ?></small></td>
                            <td><span class="badge bg-<?= getJenisTesColor($row['jenis_tes']) ?>"><?= getJenisTesLabel($row['jenis_tes']) ?></span></td>
                            <td><small><?= e($row['topik']) ?></small></td>
                            <td><span class="badge bg-secondary"><?= ucfirst($row['tipe']) ?></span></td>
                            <td><?= ucfirst($row['level']) ?></td>
                            <td>
                                <a href="?edit=<?= $row['id'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                <a href="?hapus=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus?')"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
