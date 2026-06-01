<?php
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

$pageTitle = 'Kelola Paket Ujian - ' . APP_NAME;

// Hapus
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $conn->query("DELETE FROM paket_ujian WHERE id = $id");
    flash('success', 'Paket dihapus.');
    redirect('admin/kelola_paket.php');
}

// Simpan paket
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan_paket'])) {
    $id = intval($_POST['id'] ?? 0);
    $kat = intval($_POST['kategori_ujian_id'] ?? 0);
    $nama = trim($_POST['nama_paket'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $twk = intval($_POST['jumlah_soal_twk'] ?? 0);
    $tiu = intval($_POST['jumlah_soal_tiu'] ?? 0);
    $tkp = intval($_POST['jumlah_soal_tkp'] ?? 0);
    $waktu = intval($_POST['waktu_menit'] ?? 90);
    $status = $_POST['status'] ?? 'aktif';

    if ($id) {
        $stmt = $conn->prepare('UPDATE paket_ujian SET kategori_ujian_id=?, nama_paket=?, deskripsi=?, jumlah_soal_twk=?, jumlah_soal_tiu=?, jumlah_soal_tkp=?, waktu_menit=?, status=? WHERE id=?');
        $stmt->bind_param('issiiiisi', $kat, $nama, $deskripsi, $twk, $tiu, $tkp, $waktu, $status, $id);
    } else {
        $stmt = $conn->prepare('INSERT INTO paket_ujian (kategori_ujian_id, nama_paket, deskripsi, jumlah_soal_twk, jumlah_soal_tiu, jumlah_soal_tkp, waktu_menit, status) VALUES (?,?,?,?,?,?,?,?)');
        $stmt->bind_param('issiiiis', $kat, $nama, $deskripsi, $twk, $tiu, $tkp, $waktu, $status);
    }
    $stmt->execute();
    $paket_id = $id ?: $stmt->insert_id;
    flash('success', 'Paket disimpan. Sekarang pilih soal.');
    redirect('admin/kelola_paket.php?kelola_soal=' . $paket_id);
}

// Simpan soal ke paket
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_soal_paket'])) {
    $paket_id = intval($_POST['paket_id'] ?? 0);
    $soal_ids = $_POST['soal_ids'] ?? [];
    foreach ($soal_ids as $sid) {
        $sid = intval($sid);
        $conn->query("INSERT IGNORE INTO paket_soal (paket_ujian_id, soal_id) VALUES ($paket_id, $sid)");
    }
    flash('success', 'Soal ditambahkan ke paket.');
    redirect('admin/kelola_paket.php?kelola_soal=' . $paket_id);
}

// Hapus soal dari paket
if (isset($_GET['hapus_soal_paket'])) {
    $ps_id = intval($_GET['hapus_soal_paket']);
    $conn->query("DELETE FROM paket_soal WHERE id = $ps_id");
    flash('success', 'Soal dihapus dari paket.');
    redirect('admin/kelola_paket.php?kelola_soal=' . intval($_GET['paket_id']));
}

$kategori_list = getKategoriUjian($conn);
$paket = $conn->query('SELECT p.*, k.nama as kategori_nama FROM paket_ujian p LEFT JOIN kategori_ujian k ON p.kategori_ujian_id = k.id ORDER BY p.id DESC');

$editData = null;
if (isset($_GET['edit'])) {
    $stmt = $conn->prepare('SELECT * FROM paket_ujian WHERE id = ?');
    $stmt->bind_param('i', $_GET['edit']);
    $stmt->execute();
    $editData = $stmt->get_result()->fetch_assoc();
}

$kelolaPaketId = intval($_GET['kelola_soal'] ?? 0);
$soalTerpilih = [];
$soalSisa = [];
if ($kelolaPaketId) {
    $res = $conn->query("SELECT ps.id as ps_id, s.*, o.label as kunci_label FROM paket_soal ps JOIN soal s ON ps.soal_id = s.id LEFT JOIN opsi_jawaban o ON o.soal_id = s.id AND o.is_kunci = 1 WHERE ps.paket_ujian_id = $kelolaPaketId ORDER BY s.jenis_tes, s.id");
    while ($r = $res->fetch_assoc()) $soalTerpilih[] = $r;

    // Soal yang belum dipilih, sesuai kategori paket
    $katId = $conn->query("SELECT kategori_ujian_id FROM paket_ujian WHERE id = $kelolaPaketId")->fetch_assoc()['kategori_ujian_id'] ?? 0;
    $res2 = $conn->query("SELECT s.*, o.label as kunci_label FROM soal s LEFT JOIN opsi_jawaban o ON o.soal_id = s.id AND o.is_kunci = 1 WHERE s.kategori_ujian_id = $katId AND s.id NOT IN (SELECT soal_id FROM paket_soal WHERE paket_ujian_id = $kelolaPaketId) ORDER BY s.jenis_tes, s.id LIMIT 200");
    while ($r = $res2->fetch_assoc()) $soalSisa[] = $r;
}

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar_admin.php';
?>

<div class="container py-4">
    <h3 class="fw-bold mb-3"><i class="bi bi-calendar-check"></i> Kelola Paket Ujian</h3>

    <?php if (hasFlash('success')): ?>
        <div class="alert alert-success auto-dismiss"><?= e(getFlash('success')) ?></div>
    <?php endif; ?>

    <!-- Form Paket -->
    <div class="card mb-4">
        <div class="card-header bg-white fw-bold"><?= $editData ? 'Edit' : 'Tambah' ?> Paket</div>
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="simpan_paket" value="1">
                <input type="hidden" name="id" value="<?= $editData['id'] ?? 0 ?>">
                <div class="row g-2">
                    <div class="col-md-3">
                        <select name="kategori_ujian_id" class="form-select" required>
                            <option value="">Pilih Kategori</option>
                            <?php foreach ($kategori_list as $k): ?>
                                <option value="<?= $k['id'] ?>" <?= ($editData['kategori_ujian_id'] ?? '') == $k['id'] ? 'selected' : '' ?>><?= e($k['nama']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3"><input type="text" name="nama_paket" class="form-control" placeholder="Nama Paket" required value="<?= e($editData['nama_paket'] ?? '') ?>"></div>
                    <div class="col-md-2"><input type="number" name="jumlah_soal_twk" class="form-control" placeholder="Soal TWK" required value="<?= $editData['jumlah_soal_twk'] ?? 35 ?>"></div>
                    <div class="col-md-2"><input type="number" name="jumlah_soal_tiu" class="form-control" placeholder="Soal TIU" required value="<?= $editData['jumlah_soal_tiu'] ?? 30 ?>"></div>
                    <div class="col-md-2"><input type="number" name="jumlah_soal_tkp" class="form-control" placeholder="Soal TKP" required value="<?= $editData['jumlah_soal_tkp'] ?? 35 ?>"></div>
                    <div class="col-md-2"><input type="number" name="waktu_menit" class="form-control" placeholder="Waktu (menit)" required value="<?= $editData['waktu_menit'] ?? 90 ?>"></div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="aktif" <?= ($editData['status'] ?? '') === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                            <option value="nonaktif" <?= ($editData['status'] ?? '') === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-save"></i> Simpan</button>
                    </div>
                </div>
                <div class="mt-2">
                    <textarea name="deskripsi" class="form-control" rows="2" placeholder="Deskripsi paket..."><?= e($editData['deskripsi'] ?? '') ?></textarea>
                </div>
            </form>
        </div>
    </div>

    <!-- List Paket -->
    <?php if (!$kelolaPaketId): ?>
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr><th>Nama</th><th>Kategori</th><th>TWK/TIU/TKP</th><th>Waktu</th><th>Status</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $paket->fetch_assoc()): ?>
                        <tr>
                            <td><?= e($row['nama_paket']) ?></td>
                            <td><small><?= e($row['kategori_nama'] ?? '-') ?></small></td>
                            <td><?= $row['jumlah_soal_twk'] ?> / <?= $row['jumlah_soal_tiu'] ?> / <?= $row['jumlah_soal_tkp'] ?></td>
                            <td><?= $row['waktu_menit'] ?> menit</td>
                            <td><span class="badge bg-<?= $row['status'] === 'aktif' ? 'success' : 'secondary' ?>"><?= $row['status'] ?></span></td>
                            <td>
                                <a href="?edit=<?= $row['id'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                <a href="?kelola_soal=<?= $row['id'] ?>" class="btn btn-sm btn-info text-white"><i class="bi bi-list-check"></i> Soal</a>
                                <a href="?hapus=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus?')"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Kelola Soal dalam Paket -->
    <?php if ($kelolaPaketId): ?>
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="fw-bold">Soal dalam Paket #<?= $kelolaPaketId ?></h5>
        <a href="kelola_paket.php" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-white fw-bold text-danger">Soal Terpilih (<?= count($soalTerpilih) ?>)</div>
                <div class="card-body p-0" style="max-height:500px; overflow-y:auto;">
                    <ul class="list-group list-group-flush">
                        <?php foreach ($soalTerpilih as $s): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge bg-<?= getJenisTesColor($s['jenis_tes']) ?>"><?= getJenisTesLabel($s['jenis_tes']) ?></span>
                                <small><?= mb_strimwidth(strip_tags($s['pertanyaan']), 0, 60, '...') ?></small>
                            </div>
                            <a href="?hapus_soal_paket=<?= $s['ps_id'] ?>&paket_id=<?= $kelolaPaketId ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus dari paket?')"><i class="bi bi-x"></i></a>
                        </li>
                        <?php endforeach; ?>
                        <?php if (empty($soalTerpilih)): ?><li class="list-group-item text-muted">Belum ada soal.</li><?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-white fw-bold text-success">Tambah Soal (<?= count($soalSisa) ?>)</div>
                <div class="card-body p-0" style="max-height:500px; overflow-y:auto;">
                    <form method="POST">
                        <input type="hidden" name="tambah_soal_paket" value="1">
                        <input type="hidden" name="paket_id" value="<?= $kelolaPaketId ?>">
                        <ul class="list-group list-group-flush">
                            <?php foreach ($soalSisa as $s): ?>
                            <li class="list-group-item">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="soal_ids[]" value="<?= $s['id'] ?>" id="soal<?= $s['id'] ?>">
                                    <label class="form-check-label" for="soal<?= $s['id'] ?>">
                                        <span class="badge bg-<?= getJenisTesColor($s['jenis_tes']) ?>"><?= getJenisTesLabel($s['jenis_tes']) ?></span>
                                        <small><?= mb_strimwidth(strip_tags($s['pertanyaan']), 0, 60, '...') ?></small>
                                    </label>
                                </div>
                            </li>
                            <?php endforeach; ?>
                            <?php if (empty($soalSisa)): ?><li class="list-group-item text-muted">Semua soal sudah dipilih.</li><?php endif; ?>
                        </ul>
                        <?php if (!empty($soalSisa)): ?>
                        <div class="p-2">
                            <button type="submit" class="btn btn-success btn-sm w-100"><i class="bi bi-plus-lg"></i> Tambah ke Paket</button>
                        </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
