<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';

requirePeserta();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('peserta/dashboard.php');
}

$hasil_id = intval($_POST['hasil_id'] ?? 0);
$user_id = $_SESSION['user_id'] ?? 0;
$csrf = $_POST['csrf_token'] ?? '';

if (!verifyCSRF($csrf)) {
    flash('error', 'Sesi tidak valid. Silakan submit ulang.');
    redirect('peserta/dashboard.php');
}

if (!$hasil_id || !$user_id) {
    flash('error', 'Data tidak valid.');
    redirect('peserta/dashboard.php');
}

// Ambil data hasil & kategori
$stmt = $conn->prepare('SELECT h.*, p.kategori_ujian_id, k.passing_grade_twk, k.passing_grade_tiu, k.passing_grade_tkp, k.passing_grade_kumulatif FROM hasil_ujian h JOIN paket_ujian p ON h.paket_ujian_id = p.id JOIN kategori_ujian k ON p.kategori_ujian_id = k.id WHERE h.id = ? AND h.user_id = ?');
$stmt->bind_param('ii', $hasil_id, $user_id);
$stmt->execute();
$hasil = $stmt->get_result()->fetch_assoc();

if (!$hasil) {
    flash('error', 'Hasil tidak ditemukan.');
    redirect('peserta/dashboard.php');
}

if ($hasil['status_lulus'] !== 'proses') {
    flash('error', 'Ujian ini sudah pernah disubmit.');
    redirect('peserta/tryout_hasil.php?id=' . $hasil_id);
}

// Hitung skor per jenis
$skorTWK = 0; $skorTIU = 0; $skorTKP = 0;

// TWK & TIU (bobot 5 per benar)
foreach (['twk' => &$skorTWK, 'tiu' => &$skorTIU] as $jenis => &$skorVar) {
    $s = $conn->prepare("SELECT SUM(dj.nilai_diperoleh) as total FROM detail_jawaban dj JOIN soal s ON dj.soal_id = s.id WHERE dj.hasil_ujian_id = ? AND s.jenis_tes = ?");
    $s->bind_param('is', $hasil_id, $jenis);
    $s->execute();
    $skorVar = $s->get_result()->fetch_assoc()['total'] ?? 0;
}

// TKP (skoring 1-5)
$s = $conn->prepare("SELECT SUM(dj.nilai_diperoleh) as total FROM detail_jawaban dj JOIN soal s ON dj.soal_id = s.id WHERE dj.hasil_ujian_id = ? AND s.jenis_tes = 'tkp'");
$s->bind_param('i', $hasil_id);
$s->execute();
$skorTKP = $s->get_result()->fetch_assoc()['total'] ?? 0;

$kumulatif = $skorTWK + $skorTIU + $skorTKP;
$status = getStatusLulus($skorTWK, $skorTIU, $skorTKP, $hasil);

// Update hasil
$stmt2 = $conn->prepare('UPDATE hasil_ujian SET skor_twk = ?, skor_tiu = ?, skor_tkp = ?, skor_kumulatif = ?, status_lulus = ?, tanggal_selesai = NOW() WHERE id = ?');
$stmt2->bind_param('iiiisi', $skorTWK, $skorTIU, $skorTKP, $kumulatif, $status, $hasil_id);
$stmt2->execute();

// Generate rekomendasi belajar
// Analisis topik lemah (< 50% benar)
$stmt3 = $conn->prepare('SELECT jenis_tes, topik, total, benar FROM (SELECT s.jenis_tes, s.topik, COUNT(*) as total, SUM(CASE WHEN dj.nilai_diperoleh > 0 THEN 1 ELSE 0 END) as benar FROM detail_jawaban dj JOIN soal s ON dj.soal_id = s.id WHERE dj.hasil_ujian_id = ? GROUP BY s.jenis_tes, s.topik) t WHERE benar/total < 0.5');
$stmt3->bind_param('i', $hasil_id);
$stmt3->execute();
$rekomendasi = $stmt3->get_result();

while ($row = $rekomendasi->fetch_assoc()) {
    $pct = round(($row['benar'] / $row['total']) * 100, 2);
    $tingkat = $pct < 30 ? 'mudah' : ($pct < 50 ? 'sedang' : 'sulit');
    // Cari materi terkait
    $mat = $conn->prepare("SELECT id FROM materi WHERE jenis_tes = ? AND topik = ? ORDER BY level LIMIT 1");
    $mat->bind_param('ss', $row['jenis_tes'], $row['topik']);
    $mat->execute();
    $matId = $mat->get_result()->fetch_assoc()['id'] ?? null;
    
    $ins = $conn->prepare('INSERT INTO rekomendasi_belajar (hasil_ujian_id, user_id, topik, jenis_tes, skor_persentase, saran_materi_id, tingkat_kesulitan_rekomendasi) VALUES (?,?,?,?,?,?,?)');
    $ins->bind_param('iissdis', $hasil_id, $user_id, $row['topik'], $row['jenis_tes'], $pct, $matId, $tingkat);
    $ins->execute();
}

flash('success', 'Try-out selesai! Lihat hasil & pembahasan.');
redirect('peserta/tryout_hasil.php?id=' . $hasil_id);
