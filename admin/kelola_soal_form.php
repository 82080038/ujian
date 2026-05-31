<?php
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

$pageTitle = 'Form Soal - ' . APP_NAME;
$edit = false;
$data = [
    'kategori_ujian_id' => '', 'jenis_tes' => '', 'topik' => '',
    'pertanyaan' => '', 'gambar_url' => '', 'tingkat_kesulitan' => 'sedang',
    'pembahasan' => '', 'tips_triks' => '', 'sumber' => ''
];
$opsi = [
    ['label' => 'A', 'teks_jawaban' => '', 'bobot_nilai' => 0, 'is_kunci' => 0],
    ['label' => 'B', 'teks_jawaban' => '', 'bobot_nilai' => 0, 'is_kunci' => 0],
    ['label' => 'C', 'teks_jawaban' => '', 'bobot_nilai' => 0, 'is_kunci' => 0],
    ['label' => 'D', 'teks_jawaban' => '', 'bobot_nilai' => 0, 'is_kunci' => 0],
    ['label' => 'E', 'teks_jawaban' => '', 'bobot_nilai' => 0, 'is_kunci' => 0],
];

if (isset($_GET['id'])) {
    $edit = true;
    $id = intval($_GET['id']);
    $stmt = $conn->prepare('SELECT * FROM soal WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();

    $stmt2 = $conn->prepare('SELECT * FROM opsi_jawaban WHERE soal_id = ? ORDER BY label');
    $stmt2->bind_param('i', $id);
    $stmt2->execute();
    $res = $stmt2->get_result();
    $opsi = [];
    while ($o = $res->fetch_assoc()) $opsi[] = $o;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kategori = intval($_POST['kategori_ujian_id'] ?? 0);
    $jenis = $_POST['jenis_tes'] ?? '';
    $topik = trim($_POST['topik'] ?? '');
    $pertanyaan = trim($_POST['pertanyaan'] ?? '');
    // Handle gambar upload
    $gambar = trim($_POST['gambar_url'] ?? '');
    if (isset($_FILES['gambar_file']) && $_FILES['gambar_file']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../uploads/soal/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $ext = pathinfo($_FILES['gambar_file']['name'], PATHINFO_EXTENSION);
        $filename = 'soal_' . time() . '_' . uniqid() . '.' . $ext;
        if (move_uploaded_file($_FILES['gambar_file']['tmp_name'], $uploadDir . $filename)) {
            $gambar = 'uploads/soal/' . $filename;
        }
    }
    $kesulitan = $_POST['tingkat_kesulitan'] ?? 'sedang';
    $pembahasan = trim($_POST['pembahasan'] ?? '');
    $tips = trim($_POST['tips_triks'] ?? '');
    $sumber = trim($_POST['sumber'] ?? '');

    if ($edit) {
        $stmt = $conn->prepare('UPDATE soal SET kategori_ujian_id=?, jenis_tes=?, topik=?, pertanyaan=?, gambar_url=?, tingkat_kesulitan=?, pembahasan=?, tips_triks=?, sumber=? WHERE id=?');
        $stmt->bind_param('issssssssi', $kategori, $jenis, $topik, $pertanyaan, $gambar, $kesulitan, $pembahasan, $tips, $sumber, $id);
        $stmt->execute();
        $soal_id = $id;
        // Hapus opsi lama
        $conn->query("DELETE FROM opsi_jawaban WHERE soal_id = $soal_id");
    } else {
        $stmt = $conn->prepare('INSERT INTO soal (kategori_ujian_id, jenis_tes, topik, pertanyaan, gambar_url, tingkat_kesulitan, pembahasan, tips_triks, sumber) VALUES (?,?,?,?,?,?,?,?,?)');
        $stmt->bind_param('issssssss', $kategori, $jenis, $topik, $pertanyaan, $gambar, $kesulitan, $pembahasan, $tips, $sumber);
        $stmt->execute();
        $soal_id = $stmt->insert_id;
    }

    // Simpan opsi
    foreach (['A','B','C','D','E'] as $label) {
        $teks = trim($_POST['opsi_' . $label] ?? '');
        $bobot = intval($_POST['bobot_' . $label] ?? 0);
        $kunci = isset($_POST['kunci']) && $_POST['kunci'] === $label ? 1 : 0;
        $stmt2 = $conn->prepare('INSERT INTO opsi_jawaban (soal_id, label, teks_jawaban, bobot_nilai, is_kunci) VALUES (?,?,?,?,?)');
        $stmt2->bind_param('issii', $soal_id, $label, $teks, $bobot, $kunci);
        $stmt2->execute();
    }

    flash('success', 'Soal berhasil ' . ($edit ? 'diperbarui' : 'ditambah') . '.');
    redirect('admin/kelola_soal.php');
}

$kategori_list = getKategoriUjian($conn);

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar_admin.php';
?>

<div class="container py-4">
    <h3 class="fw-bold mb-3"><?= $edit ? '<i class="bi bi-pencil"></i> Edit' : '<i class="bi bi-plus-lg"></i> Tambah' ?> Soal</h3>

    <div class="card">
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Kategori Ujian</label>
                        <select name="kategori_ujian_id" class="form-select" required>
                            <option value="">Pilih...</option>
                            <?php foreach ($kategori_list as $k): ?>
                                <option value="<?= $k['id'] ?>" <?= ($data['kategori_ujian_id'] ?? '') == $k['id'] ? 'selected' : '' ?>><?= e($k['nama']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Jenis Tes</label>
                        <select name="jenis_tes" class="form-select" required>
                            <option value="">Pilih...</option>
                            <option value="twk" <?= ($data['jenis_tes'] ?? '') === 'twk' ? 'selected' : '' ?>>TWK</option>
                            <option value="tiu" <?= ($data['jenis_tes'] ?? '') === 'tiu' ? 'selected' : '' ?>>TIU</option>
                            <option value="tkp" <?= ($data['jenis_tes'] ?? '') === 'tkp' ? 'selected' : '' ?>>TKP</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Topik</label>
                        <select name="topik" id="topik-select" class="form-select" required>
                            <option value="<?= e($data['topik'] ?? '') ?>" selected><?= e($data['topik'] ?? 'Pilih/Ketik Topik...') ?></option>
                        </select>
                        <input type="text" id="topik_manual" class="form-control mt-2" placeholder="Atau ketik topik baru" value="<?= e($data['topik'] ?? '') ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Pertanyaan</label>
                        <textarea name="pertanyaan" class="form-control" rows="4" required><?= e($data['pertanyaan'] ?? '') ?></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Gambar Soal (opsional)</label>
                        <input type="file" name="gambar_file" class="form-control" accept="image/*">
                        <?php if (!empty($data['gambar_url'])): ?>
                            <small class="text-muted">Gambar saat ini: <a href="<?= BASE_URL . e($data['gambar_url']) ?>" target="_blank">Lihat</a></small>
                            <input type="hidden" name="gambar_url" value="<?= e($data['gambar_url']) ?>">
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tingkat Kesulitan</label>
                        <select name="tingkat_kesulitan" class="form-select">
                            <?php foreach (['sangat_mudah','mudah','sedang','sulit','sangat_sulit'] as $tk): ?>
                                <option value="<?= $tk ?>" <?= ($data['tingkat_kesulitan'] ?? '') === $tk ? 'selected' : '' ?>><?= ucwords(str_replace('_', ' ', $tk)) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <hr class="my-4">
                <h5 class="fw-bold">Opsi Jawaban</h5>
                <p class="text-muted small"><i class="bi bi-info-circle"></i> Untuk TWK/TIU: isi bobot 5 untuk kunci, 0 untuk lainnya. Untuk TKP: isi bobot 1-5 sesuai tingkat kepentingan.</p>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Label</th>
                                <th>Teks Jawaban</th>
                                <th>Bobot Nilai</th>
                                <th>Kunci?</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (['A','B','C','D','E'] as $idx => $label):
                                $o = $opsi[$idx] ?? ['teks_jawaban'=>'','bobot_nilai'=>0,'is_kunci'=>0];
                            ?>
                            <tr>
                                <td class="fw-bold"><?= $label ?></td>
                                <td><input type="text" name="opsi_<?= $label ?>" class="form-control" required value="<?= e($o['teks_jawaban']) ?>"></td>
                                <td><input type="number" name="bobot_<?= $label ?>" class="form-control" min="0" max="5" value="<?= $o['bobot_nilai'] ?>"></td>
                                <td class="text-center"><input type="radio" name="kunci" value="<?= $label ?>" <?= $o['is_kunci'] ? 'checked' : '' ?>></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Pembahasan Lengkap</label>
                        <textarea name="pembahasan" class="form-control" rows="5"><?= e($data['pembahasan'] ?? '') ?></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Tips & Trik Khusus Soal Ini</label>
                        <textarea name="tips_triks" class="form-control" rows="3"><?= e($data['tips_triks'] ?? '') ?></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Sumber (opsional)</label>
                        <input type="text" name="sumber" class="form-control" value="<?= e($data['sumber'] ?? '') ?>">
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary fw-bold"><i class="bi bi-save"></i> Simpan Soal</button>
                    <a href="kelola_soal.php" class="btn btn-secondary">Batal</a>
                </div>
            </form>

<script>
// Dynamic topik based on jenis_tes
const jenisSelect = document.querySelector('select[name="jenis_tes"]');
const topikSelect = document.getElementById('topik-select');
const topikManual = document.getElementById('topik_manual');

function loadTopik(jenis) {
    if (!jenis) return;
    fetch('<?= BASE_URL ?>api/get_topik_by_jenis.php?jenis=' + jenis)
        .then(r => r.json())
        .then(data => {
            topikSelect.innerHTML = '<option value="">Pilih Topik...</option>';
            data.forEach(t => {
                const opt = document.createElement('option');
                opt.value = t; opt.textContent = t;
                topikSelect.appendChild(opt);
            });
        });
}

jenisSelect.addEventListener('change', function() {
    loadTopik(this.value);
});

topikSelect.addEventListener('change', function() {
    topikManual.value = this.value;
});

topikManual.addEventListener('input', function() {
    topikSelect.value = '';
});

// Load on page ready if jenis already selected
if (jenisSelect.value) loadTopik(jenisSelect.value);
</script>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
